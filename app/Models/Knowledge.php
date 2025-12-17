<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Traits\HasTags;

class Knowledge extends Model
{
    use HasFactory, HasTags;

    protected $table = 'knowledge';

    protected $fillable = [
        'title',
        'body',
        'scope_id',
        'status_id',
        'attachment_path',
        'created_by',
        'verified_by',
        'verified_at',
        'visibility',
        'slug',

        // fitur baru
        'type',          // pdf | image | video
        'youtube_url',   // nullable
        'thumbnail',     // nullable
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /* =========================
     | RELATIONSHIPS
     | ========================= */

    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'knowledge_tags')
                    ->withTimestamps();
    }

    public function views()
    {
        return $this->hasMany(KnowledgeView::class);
    }

    public function ratings()
    {
        return $this->hasMany(KnowledgeRating::class);
    }

    public function comments()
    {
        return $this->hasMany(KnowledgeComment::class)
                    ->where('is_approved', true)
                    ->latest();
    }

    /* =========================
     | QUERY SCOPES
     | ========================= */

    public function scopePublic(Builder $query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeVerified(Builder $query)
    {
        return $query->whereHas('status', fn ($q) =>
            $q->where('key', 'verified')
        );
    }
}
