<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Knowledge;

class TagController extends Controller
{
    /**
     * Tampilkan daftar tag (publik) — bisa dengan pencarian q=...
     * Route suggestion: GET /tags
     */
    public function index(Request $request)
{
    $user = auth()->user();

    $q = Tag::query();

    if ($request->filled('q')) {
        $term = trim($request->input('q'));
        $q->where('name', 'like', "%{$term}%");
    }

    $q->withCount([
        'knowledges as knowledge_count' => function ($qq) use ($user) {

            // GUEST → hanya public + verified
            if (! $user) {
                $qq->where('visibility', 'public')
                   ->whereHas('status', fn ($s) => $s->where('key', 'verified'));
                return;
            }

            // USER → public + internal, tapi HARUS verified
            if ($user->role === 'user') {
                $qq->whereHas('status', fn ($s) => $s->where('key', 'verified'));
                return;
            }

            // ADMIN / VERIFIKATOR → lihat semua (termasuk draft)
        }
    ])
    ->having('knowledge_count', '>', 0)
    ->orderByDesc('knowledge_count')
    ->orderBy('name');

    $tags = $q->paginate(50);

    return view('tags.index', compact('tags'));
}





    /**
     * Tampilkan semua knowledge yang terkait dengan $tag.
     * Route suggestion: GET /tags/{tag}
     * Pastikan route-model-binding tag -> by slug (lebih rapi).
     */
    public function show(Request $request, Tag $tag)
    {
        // Base query: knowledge yg ter-tag ini
        $q = Knowledge::with(['scope','status','tags'])
            ->whereHas('tags', fn($qq) => $qq->where('tags.id', $tag->id));

        $user = auth()->user();

        // Jika guest -> hanya yang visibility=public & status verified
        if (! $user) {
            $q->where('visibility', 'public')
              ->whereHas('status', fn($qq) => $qq->where('key', 'verified'));
        } else {
            // Jika user biasa (role 'user') -> hanya yang berstatus verified (internal/public)
            if ($user->role === 'user') {
                $q->whereHas('status', fn($qq) => $qq->where('key', 'verified'));
            }
            // admin/verifikator/super_admin => lihat semua (no extra filter)
        }

        // optional: search within results (q parameter)
        if ($request->filled('q')) {
            $term = $request->input('q');
            $q->where(function($qq) use ($term) {
                $qq->where('title', 'like', "%{$term}%")
                   ->orWhere('body', 'like', "%{$term}%");
            });
        }

        $knowledges = $q->latest()->paginate(12);

        return view('tags.show', compact('tag','knowledges'));
    }

    /**
     * JSON autocomplete/search endpoint untuk tag (AJAX).
     * Route suggestion: GET /api/tags/search?q=ma
     * Returns: [{id,name,slug,count}, ...]
     */
    public function search(Request $request)
{
    $term = trim((string) $request->input('q', ''));

    $q = Tag::withCount([
        'knowledges as count' => function ($qq) {
            $qq->where('visibility', 'public')
               ->whereHas('status', fn ($s) => $s->where('key', 'verified'));
        }
    ]);

    if ($term !== '') {
        $q->where('name', 'like', "{$term}%");
    }

    return response()->json(
        $q->having('count', '>', 0)
          ->orderByDesc('count')
          ->limit(20)
          ->get(['id','name','slug'])
    );
}

}
