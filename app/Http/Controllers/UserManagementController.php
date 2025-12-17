<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\SuperAdmin;
use App\Models\Verifikator;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserManagementController extends Controller
{
    public function __construct()
    {
        // hanya super admin bisa semua fitur ini
        $this->middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':super_admin']);
    }

    // ============================
    // INDEX
    // ============================
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('user_management.index', compact('users'));
    }

    // ============================
    // CREATE
    // ============================
    public function create()
    {
        return view('user_management.create');
    }

    // ============================
    // STORE
    // ============================
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6','confirmed'],
            'role' => ['required', Rule::in(['user','admin','verifikator','super_admin'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Profile Role
        match($user->role) {
            'admin'        => Admin::create(['user_id' => $user->id]),
            'super_admin'  => SuperAdmin::create(['user_id' => $user->id]),
            'verifikator'  => Verifikator::create(['user_id' => $user->id]),
            default        => null
        };

        ActivityLog::create([
            'action' => 'create_user',
            'meta' => json_encode(['id' => $user->id, 'role' => $user->role]),
            'performed_by' => auth()->id()
        ]);

        return redirect()->route('user-management.index')->with('success', 'User berhasil dibuat.');
    }

    // ============================
    // SHOW
    // ============================
    public function show(User $user)
    {
        return view('user_management.show', compact('user'));
    }

    // ============================
    // EDIT
    // ============================
    public function edit(User $user)
    {
        return view('user_management.edit', compact('user'));
    }

    // ============================
    // UPDATE
    // ============================
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            'password' => ['nullable','string','min:6','confirmed'],
            'role' => ['required', Rule::in(['user','verifikator','super_admin'])],
        ]);

        $update = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'],
        ];

        if (!empty($data['password'])) {
            $update['password'] = Hash::make($data['password']);
        }

        $user->update($update);

        // Role handling
        if ($user->role === 'admin' && !$user->adminProfile) {
            Admin::create(['user_id' => $user->id]);
        } elseif ($user->role !== 'admin' && $user->adminProfile) {
            $user->adminProfile()->delete();
        }

        if ($user->role === 'super_admin' && !$user->superAdminProfile) {
            SuperAdmin::create(['user_id' => $user->id]);
        } elseif ($user->role !== 'super_admin' && $user->superAdminProfile) {
            $user->superAdminProfile()->delete();
        }

        if ($user->role === 'verifikator' && !$user->verifikatorProfile) {
            Verifikator::create(['user_id' => $user->id]);
        } elseif ($user->role !== 'verifikator' && $user->verifikatorProfile) {
            $user->verifikatorProfile()->delete();
        }

        ActivityLog::create([
            'action' => 'update_user',
            'meta' => json_encode(['id' => $user->id, 'role' => $user->role]),
            'performed_by' => auth()->id()
        ]);

        return redirect()->route('user-management.index')->with('success', 'User berhasil diperbarui.');
    }

    // ============================
    // DESTROY (soft delete / force)
    // ============================
    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        $force = $request->boolean('force');

        DB::beginTransaction();
        try {
            if ($force) {
                $uid = $user->id;
                $role = $user->role;

                $user->forceDelete();

                ActivityLog::create([
                    'action' => 'delete_user_permanent',
                    'meta' => json_encode(['id' => $uid, 'role' => $role]),
                    'performed_by' => auth()->id(),
                ]);

                DB::commit();
                return redirect()->route('user-management.index')->with('success', 'User dihapus permanen.');
            }

            $user->delete();

            ActivityLog::create([
                'action' => 'soft_delete_user',
                'meta' => json_encode(['id' => $user->id, 'role' => $user->role]),
                'performed_by' => auth()->id()
            ]);

            DB::commit();
            return redirect()->route('user-management.index')->with('success', 'User dihapus (soft delete).');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal hapus user: '.$e->getMessage());
            return back()->with('error', 'Gagal menghapus user.');
        }
    }

    // ============================
    // TRASHED LIST
    // ============================
    public function trashed(Request $request)
    {
        $q = User::onlyTrashed();

        if ($request->filled('q')) {
            $term = $request->q;
            $q->where(function($qq) use ($term) {
                $qq->where('name', 'like', "%$term%")
                   ->orWhere('email', 'like', "%$term%");
            });
        }

        $users = $q->latest('deleted_at')->paginate(20);

        return view('user_management.trashed', compact('users'));
    }

    // ============================
    // RESTORE USER
    // ============================
    public function restore($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        $user->restore();

        ActivityLog::create([
            'action' => 'restore_user',
            'meta' => json_encode(['id' => $user->id]),
            'performed_by' => auth()->id()
        ]);

        return redirect()->route('user-management.trashed')->with('success','User berhasil dikembalikan.');
    }

    // ============================
    // FORCE DELETE USER
    // ============================
    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        if (auth()->id() === $user->id) {
            return back()->with('error','Tidak bisa menghapus akun sendiri.');
        }

        $user->forceDelete();

        ActivityLog::create([
            'action' => 'force_delete_user',
            'meta' => json_encode(['id' => $user->id]),
            'performed_by' => auth()->id()
        ]);

        return redirect()->route('user-management.trashed')->with('success','User dihapus permanen.');
    }
}
