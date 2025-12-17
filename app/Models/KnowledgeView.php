<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeView extends Model
{
    protected $fillable = [
        'knowledge_id',
        'user_id',
        'ip_address',
        'user_agent',
    ];
}
