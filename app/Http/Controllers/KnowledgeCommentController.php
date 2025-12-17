<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\KnowledgeComment;
use Illuminate\Http\Request;

class KnowledgeCommentController extends Controller
{
    public function store(Request $request, Knowledge $knowledge)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        KnowledgeComment::create([
            'knowledge_id' => $knowledge->id,
            'user_id'      => auth()->id(),
            'comment'      => $request->comment,
        ]);

        return back()->with('success', 'Komentar ditambahkan.');
    }

    public function update(Request $request, KnowledgeComment $comment)
    {
        abort_unless(auth()->id() === $comment->user_id, 403);

        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment->update([
            'comment' => $request->comment,
        ]);

        return back();
    }

    public function destroy(KnowledgeComment $comment)
    {
        abort_unless(auth()->id() === $comment->user_id, 403);

        $comment->delete();

        return back();
    }

}
