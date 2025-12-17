<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\ActivityLog
 *
 * @property int $id
 * @property string $action
 * @property array|null $meta
 * @property int|null $performed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 */
class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['action','meta','performed_by'];
    protected $casts = ['meta' => 'array'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'performed_by');
    }
}
