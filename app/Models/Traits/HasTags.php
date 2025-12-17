<?php
namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Support\Str;

trait HasTags
{
    // relation many-to-many
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'knowledge_tags', 'knowledge_id', 'tag_id')->withTimestamps();
    }

    /**
     * Sync tags dari array string (['magang','pns'] atau ['#magang','pns'])
     */
    public function syncTags(array $rawTags)
    {
        $tagIds = collect($rawTags)
            ->map(function($t){
                $t = trim((string) $t);
                $t = trim($t, "# \t\n\r\0\x0B");
                $t = Str::lower($t);
                if ($t === '') return null;

                $slug = Str::slug($t);

                $tag = Tag::firstOrCreate(
                    ['name' => $t],
                    ['slug' => $slug]
                );

                return $tag->id;
            })
            ->filter() // buang null/empty
            ->unique()
            ->values()
            ->all();

        // sync pivot
        $this->tags()->sync($tagIds);

        // optional: update counts (simple recalc)
        \DB::table('tags')
            ->whereIn('id', $tagIds)
            ->update(['count' => \DB::raw('(SELECT COUNT(*) FROM knowledge_tags WHERE knowledge_tags.tag_id = tags.id)')]);

        return $this->tags()->get();
    }

    /**
     * Add single tag (tanpa menghapus tag yang lain)
     */
    public function addTag(string $rawTag)
    {
        $this->syncTags(array_merge($this->tags->pluck('name')->toArray(), [$rawTag]));
    }

    /**
     * Remove single tag
     */
    public function removeTag(string $rawTag)
    {
        $t = trim($rawTag);
        $t = trim($t, "# \t\n\r\0\x0B");
        $t = \Illuminate\Support\Str::lower($t);
        $tag = Tag::where('name', $t)->first();
        if ($tag) {
            $this->tags()->detach($tag->id);

            // recalc count
            \DB::table('tags')
                ->where('id', $tag->id)
                ->update(['count' => \DB::raw('(SELECT COUNT(*) FROM knowledge_tags WHERE knowledge_tags.tag_id = tags.id)')]);
        }
    }
}
