<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Knowledge;

class KnowledgeTagController extends Controller
{
    // Sync tags (replace existing tags)
    public function sync(Request $request, Knowledge $knowledge)
    {
        $tags = $request->input('tags', []);

        // tags bisa berupa "magang,pns" atau array ["magang","pns"]
        if (! is_array($tags)) {
            $tags = array_filter(array_map('trim', explode(',', $tags)));
        }

        $knowledge->syncTags($tags);

        return back()->with('success', 'Tags diperbarui.');
    }

    // Add one tag (tanpa menghapus tag lain)
    public function add(Request $request, Knowledge $knowledge)
    {
        $tag = $request->input('tag');

        if (! $tag) {
            return back()->withErrors('Tag wajib diisi.');
        }

        $knowledge->addTag($tag);

        return back()->with('success', 'Tag ditambahkan.');
    }

    // Remove one tag
    public function remove(Request $request, Knowledge $knowledge)
{
    $tagName = $request->input('tag');
    if (! $tagName) {
        return back()->withErrors('Tag wajib diisi.');
    }

    // normalisasi nama seperti di HasTags trait
    $name = trim($tagName);
    $name = trim($name, "# \t\n\r\0\x0B");
    $name = \Illuminate\Support\Str::lower($name);

    $tag = \App\Models\Tag::where('name', $name)->first();
    if (! $tag) {
        return back()->with('success','Tag sudah tidak ada.');
    }

    // detach
    $knowledge->tags()->detach($tag->id);

    // jika tidak ada lagi relasi ke knowledge, hapus tag
    $still = \DB::table('knowledge_tags')->where('tag_id', $tag->id)->exists();
    if (! $still) {
        $tag->delete();
    } else {
        // optional: recalc count
        $tag->update(['count' => \DB::table('knowledge_tags')->where('tag_id',$tag->id)->count()]);
    }

    return back()->with('success', 'Tag dihapus.');
}

}
