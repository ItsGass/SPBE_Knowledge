<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = ['name','slug'];

    // jika ingin otomatis atur timestamp
    public $timestamps = true;

    // agar Route Model Binding memakai 'slug' bukan id
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // relation ke Knowledge
    public function knowledges(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Knowledge::class, 'knowledge_tags', 'tag_id', 'knowledge_id')->withTimestamps();
    }

    // helper: tampilkan dengan '#'
    public function getDisplayAttribute(): string
    {
        return '#' . $this->name;
    }
}
