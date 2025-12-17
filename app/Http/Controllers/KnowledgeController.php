<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\KnowledgeView;
use App\Models\Scope;
use App\Models\Status;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;

class KnowledgeController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\RoleMiddleware::class . ':verifikator,super_admin')
             ->only(['verify','publish','toggleVisibility']);
    }

    /* =========================
     | INDEX
     | ========================= */
    public function index(Request $request)
    {
        $user = auth()->user();
        if (! $user) {
            return redirect()->route('welcome');
        }

        $q = Knowledge::with(['scope','status','tags'])
            ->withCount(['views','comments','ratings'])
            ->withAvg('ratings', 'rating');

        // ================= SEARCH =================
if ($request->filled('q')) {
    $keyword = $request->q;

    $q->where(function ($query) use ($keyword) {
        $query->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('body', 'LIKE', "%{$keyword}%")
              ->orWhereHas('tags', function ($t) use ($keyword) {
                  $t->where('name', 'LIKE', "%{$keyword}%");
              });
    });
}


        if ($request->filled('visibility')) {
            $q->where('visibility', $request->visibility);
        }

        if ($user->role === 'user') {
            $q->whereHas('status', fn ($qr) =>
                $qr->where('key', 'verified')
            );
        }

        $knowledges = $q->latest()->paginate(10);

        return view('knowledge.index', compact('knowledges'));
    }

    

    /* =========================
     | CREATE
     | ========================= */
    public function create()
    {
        $this->authorize('create', Knowledge::class);

        return view('knowledge.create', [
            'scopes' => Scope::all(),
            'popularTags' => \App\Models\Tag::orderByDesc('count')->limit(30)->get()
        ]);
    }

    /* =========================
 | EDIT  
 | ========================= */
public function edit(Knowledge $knowledge)
{
    $this->authorize('update', $knowledge);

    $knowledge->load('tags');

    return view('knowledge.edit', [
        'knowledge' => $knowledge,
        'scopes'    => Scope::all(),
    ]);
}


    /* =========================
     | STORE
     | ========================= */
    public function store(Request $request)
    {
        $this->authorize('create', Knowledge::class);

        $data = $request->validate([
            'title'       => 'required|max:255',
            'body'        => 'required',
            'scope_id'    => 'nullable|exists:scopes,id',
            'visibility'  => 'nullable|in:public,internal',

            // TYPE FINAL (NO IMAGE)
            'type'        => 'required|in:pdf,video',

            // PDF ONLY
            'attachment'  => 'nullable|required_if:type,pdf|file|mimes:pdf|max:10240',

            // VIDEO ONLY
            'youtube_url' => 'nullable|required_if:type,video|url',

            // PREVIEW
            'thumbnail'   => 'nullable|image|max:2048',

            'tags'        => 'nullable',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')
                ->store('attachments','public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails','public');
        }

        $data['created_by'] = auth()->id();
        $data['visibility'] = $data['visibility'] ?? 'internal';

        $status = Status::firstWhere('key', 'draft')
            ?? Status::create(['key' => 'draft', 'label' => 'Draft']);

        $data['status_id'] = $status->id;

        $knowledge = Knowledge::create($data);

        if ($request->filled('tags')) {
            $tags = array_filter(array_map('trim', explode(',', $request->tags)));
            $knowledge->syncTags($tags);
        }

        ActivityLog::create([
            'action' => 'create_knowledge',
            'meta' => json_encode(['id' => $knowledge->id]),
            'performed_by' => auth()->id()
        ]);

        return redirect()->route('knowledge.index')->with('success', 'Knowledge dibuat.');
    }

    /* =========================
     | SHOW
     | ========================= */
    public function show(Knowledge $knowledge)
    {
        $this->recordView($knowledge);
        $this->authorize('view', $knowledge);

        $knowledge->load([
            'scope',
            'status',
            'tags',
            'ratings',
            'comments.user',
        ]);

        $knowledge->loadCount(['views','comments','ratings']);
        $knowledge->loadAvg('ratings','rating');

        return view('knowledge.show', compact('knowledge'));
    }

    /* =========================
     | UPDATE
     | ========================= */
    public function update(Request $request, Knowledge $knowledge)
    {
        $this->authorize('update', $knowledge);

        $data = $request->validate([
            'title'       => 'required|max:255',
            'body'        => 'required',
            'scope_id'    => 'nullable|exists:scopes,id',
            'visibility'  => 'nullable|in:public,internal',

            'type'        => 'required|in:pdf,video',

            'attachment'  => 'nullable|required_if:type,pdf|file|mimes:pdf|max:10240',
            'youtube_url' => 'nullable|required_if:type,video|url',
            'thumbnail'   => 'nullable|image|max:2048',

            'tags'        => 'nullable',
        ]);

        if ($request->hasFile('attachment')) {
            if ($knowledge->attachment_path) {
                Storage::disk('public')->delete($knowledge->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')
                ->store('attachments','public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($knowledge->thumbnail) {
                Storage::disk('public')->delete($knowledge->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails','public');
        }

        $knowledge->update($data);

        if ($request->has('tags')) {
            $tags = is_string($request->tags)
                ? array_filter(array_map('trim', explode(',', $request->tags)))
                : $request->tags;

            $knowledge->syncTags($tags ?? []);
        }

        ActivityLog::create([
            'action' => 'update_knowledge',
            'meta' => json_encode(['id' => $knowledge->id]),
            'performed_by' => auth()->id()
        ]);

        return redirect()->route('knowledge.show', $knowledge)
            ->with('success', 'Knowledge diperbarui.');
    }

    /* =========================
 | DESTROY  
 | ========================= */
public function destroy(Knowledge $knowledge)
{
    $this->authorize('delete', $knowledge);

    // hapus file lampiran jika ada
    if ($knowledge->attachment_path && Storage::disk('public')->exists($knowledge->attachment_path)) {
        Storage::disk('public')->delete($knowledge->attachment_path);
    }

    // hapus thumbnail jika ada
    if ($knowledge->thumbnail && Storage::disk('public')->exists($knowledge->thumbnail)) {
        Storage::disk('public')->delete($knowledge->thumbnail);
    }

    ActivityLog::create([
        'action' => 'delete_knowledge',
        'meta' => json_encode(['id' => $knowledge->id]),
        'performed_by' => auth()->id()
    ]);

    $knowledge->delete();

    return redirect()
        ->route('knowledge.index')
        ->with('success', 'Knowledge berhasil dihapus.');
}

/* =========================
 | PUBLIC SHOW (GUEST)
 | ========================= */
public function publicShow(Knowledge $knowledge)
{
    // proteksi: hanya public + verified
    if (
        $knowledge->visibility !== 'public' ||
        optional($knowledge->status)->key !== 'verified'
    ) {
        abort(404);
    }

    // catat view
    $this->recordView($knowledge);

    $knowledge->load([
        'scope',
        'status',
        'tags',
        'ratings',
        'comments.user',
    ]);

    $knowledge->loadCount(['views','comments','ratings']);
    $knowledge->loadAvg('ratings','rating');

    return view('knowledge.show', compact('knowledge'));
}


    /* =========================
     | VIEW TRACKING
     | ========================= */
    protected function recordView(Knowledge $knowledge): void
    {
        if (auth()->check()) {
            if (KnowledgeView::where('knowledge_id', $knowledge->id)
                ->where('user_id', auth()->id())->exists()) {
                return;
            }

            KnowledgeView::create([
                'knowledge_id' => $knowledge->id,
                'user_id' => auth()->id(),
                'ip_address' => request()->ip(),
            ]);

            return;
        }

        $cookie = 'guest_view_knowledge_'.$knowledge->id;
        if (Cookie::has($cookie)) return;

        KnowledgeView::create([
            'knowledge_id' => $knowledge->id,
            'ip_address' => request()->ip(),
        ]);

        cookie()->queue(cookie($cookie, true, 1440));
    }

    /* =========================
     | verify
     | ========================= */
    public function verify(Knowledge $knowledge)
{
    $this->authorize('verify', $knowledge);

    $status = Status::firstWhere('key', 'verified');
    if (! $status) {
        $status = Status::create([
            'key' => 'verified',
            'label' => 'Verified'
        ]);
    }

    $knowledge->update([
        'status_id' => $status->id
    ]);

    ActivityLog::create([
        'action' => 'verify_knowledge',
        'meta' => json_encode(['id' => $knowledge->id]),
        'performed_by' => auth()->id()
    ]);

    return back()->with('success', 'Knowledge berhasil diverifikasi.');
}
    /* =========================
     | publish
     | ========================= */
    public function publish(Knowledge $knowledge)
    {
        $this->authorize('publish', $knowledge);

        $status = Status::firstWhere('key', 'published');
        if (! $status) {
            $status = Status::create([
                'key' => 'published',
                'label' => 'Published'
            ]);
        }

        $knowledge->update([
            'status_id' => $status->id
        ]);

        ActivityLog::create([
            'action' => 'publish_knowledge',
            'meta' => json_encode(['id' => $knowledge->id]),
            'performed_by' => auth()->id()
        ]);

        return back()->with('success', 'Knowledge berhasil dipublikasikan.');
    }
    public function toggleVisibility(Knowledge $knowledge)
{
    // hanya verifikator & super_admin
    $this->authorize('verify', $knowledge);

    $knowledge->update([
        'visibility' => $knowledge->visibility === 'public'
            ? 'internal'
            : 'public'
    ]);

    ActivityLog::create([
        'action' => 'toggle_visibility',
        'meta' => json_encode([
            'id' => $knowledge->id,
            'visibility' => $knowledge->visibility
        ]),
        'performed_by' => auth()->id()
    ]);

    return back()->with('success', 'Visibility berhasil diubah.');
}

}   
