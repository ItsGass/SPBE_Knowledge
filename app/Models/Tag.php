<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name','slug'];

    public $timestamps = true;

    // Route Model Binding pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // relation ke Knowledge
    public function knowledges(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\Knowledge::class,
            'knowledge_tags',
            'tag_id',
            'knowledge_id'
        )->withTimestamps();
    }

    // helper: tampilkan dengan '#'
    public function getDisplayAttribute(): string
    {
        return '#' . $this->name;
    }

    /**
     * =========================
     * FIX 3 — SINGLE SOURCE COUNT
     * =========================
     * Hitung jumlah knowledge VALID (public + verified)
     * TANPA menyentuh database
     */
    public function scopeWithValidKnowledgeCount($query)
    {
        return $query->withCount([
            'knowledges as count' => function ($q) {
                $q->where('visibility', 'public')
                  ->whereHas('status', fn ($s) => $s->where('key', 'verified'));
            }
        ]);
    }
}
