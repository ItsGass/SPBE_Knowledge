<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeRating extends Model
{
    protected $fillable = [
        'knowledge_id',
        'user_id',
        'rating',
    ];
}
