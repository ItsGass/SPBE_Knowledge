<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Verifikator extends Model
{
    use SoftDeletes;

    protected $table = 'verifikator';
    protected $fillable = ['user_id', /* add other columns here */];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
