<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name','email','password','role'];
    protected $hidden = ['password','remember_token'];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        // Deleting event triggers on both soft-delete and forceDelete.
        // Use isForceDeleting() to only clean relations on permanent delete.
        static::deleting(function (User $user) {
            // capture id early to avoid null later if model state changes
            $uid = $user->id;

            // If this is a force delete, perform cascade/deep cleanup
            if ($user->isForceDeleting()) {
                try {
                    // Delete admin/superadmin profile rows (if exist)
                    if ($user->adminProfile()->withTrashed()->exists()) {
                        $admin = $user->adminProfile()->withTrashed()->first();
                        if ($admin) {
                            // prefer forceDelete if model uses SoftDeletes, otherwise delete
                            if (method_exists($admin, 'forceDelete')) {
                                $admin->forceDelete();
                            } else {
                                $admin->delete();
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to cleanup admin profile on forceDelete for user', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }

                try {
                    if ($user->superAdminProfile()->withTrashed()->exists()) {
                        $sa = $user->superAdminProfile()->withTrashed()->first();
                        if ($sa) {
                            if (method_exists($sa, 'forceDelete')) {
                                $sa->forceDelete();
                            } else {
                                $sa->delete();
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to cleanup super admin profile on forceDelete for user', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }

                // Delete knowledge if you want hard deletion here (FK may already handle cascade)
                // wrapped in try-catch to avoid breaking deletion if something unexpected occurs
                try {
                    // If you prefer to remove all related knowledge on force delete, uncomment:
                    // if (Schema::hasTable('knowledge')) {
                    //     DB::table('knowledge')->where('created_by', $uid)->delete();
                    // }
                } catch (\Throwable $e) {
                    Log::warning('Failed to cleanup knowledge on forceDelete for user', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }

                // Remove sessions and personal access tokens safely (use DB::table so it doesn't rely on Session model)
                try {
                    if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
                        DB::table('sessions')->where('user_id', $uid)->delete();
                    } else {
                        Log::info('Sessions table or user_id column missing; skipping sessions cleanup', ['user_id' => $uid]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete sessions for user during forceDelete', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }

                try {
                    if (Schema::hasTable('personal_access_tokens') && Schema::hasColumn('personal_access_tokens', 'tokenable_id')) {
                        DB::table('personal_access_tokens')->where('tokenable_id', $uid)->delete();
                    } else {
                        Log::info('personal_access_tokens table or tokenable_id column missing; skipping cleanup', ['user_id' => $uid]);
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to cleanup personal_access_tokens for user during forceDelete', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }

                // Delete activity logs created by user (optional; schema may use ON DELETE SET NULL)
                try {
                    if ($user->activityLogs()->exists()) {
                        $user->activityLogs()->delete();
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete activity logs for user during forceDelete', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }
            } else {
                // Soft delete path: we generally don't remove related models to preserve data.
                // Optionally, you may deactivate tokens/sessions when an account is soft-deleted.
                try {
                    if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'user_id')) {
                        DB::table('sessions')->where('user_id', $uid)->delete();
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete sessions for user during soft delete', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }

                try {
                    if (Schema::hasTable('personal_access_tokens') && Schema::hasColumn('personal_access_tokens', 'tokenable_id')) {
                        DB::table('personal_access_tokens')->where('tokenable_id', $uid)->delete();
                    }
                } catch (\Throwable $e) {
                    Log::warning('Failed to delete personal_access_tokens for user during soft delete', ['user_id' => $uid, 'error' => $e->getMessage()]);
                }
            }
        });

        // On restoring user, if you soft-deleted related profiles and want to restore them:
        static::restoring(function (User $user) {
            try {
                if ($user->adminProfile()->withTrashed()->exists()) {
                    $user->adminProfile()->withTrashed()->restore();
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to restore admin profile for user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            try {
                if ($user->superAdminProfile()->withTrashed()->exists()) {
                    $user->superAdminProfile()->withTrashed()->restore();
                }
            } catch (\Throwable $e) {
                Log::warning('Failed to restore super admin profile for user', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }
        });
    }

    // --- Relasi ---
    public function adminProfile()
    {
        return $this->hasOne(\App\Models\Admin::class, 'user_id');
    }

    public function superAdminProfile()
    {
        return $this->hasOne(\App\Models\SuperAdmin::class, 'user_id');
    }

    public function knowledgeCreated()
    {
        return $this->hasMany(\App\Models\Knowledge::class, 'created_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(\App\Models\ActivityLog::class, 'performed_by');
    }

        public function knowledgeRatings()
    {
        return $this->hasMany(KnowledgeRating::class);
    }

    public function knowledgeComments()
    {
        return $this->hasMany(KnowledgeComment::class);
    }

}

/**
 * Helper: check if table exists (avoid import Schema to top of file)
 */
if (!function_exists('SchemaHasTable')) {
    function SchemaHasTable($table)
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasTable($table);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
