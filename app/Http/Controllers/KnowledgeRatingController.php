<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\KnowledgeRating;
use Illuminate\Http\Request;

class KnowledgeRatingController extends Controller
{
    public function store(Request $request, Knowledge $knowledge)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $existingRating = KnowledgeRating::where('knowledge_id', $knowledge->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
            return back()->with('rating_warning', 'Anda sudah memberikan rating untuk knowledge ini.');
        }

        KnowledgeRating::create([
            'knowledge_id' => $knowledge->id,
            'user_id'      => auth()->id(),
            'rating'       => $request->rating,
        ]);

        return back()->with('rating_success', 'Terima kasih atas penilaian Anda.');
    }


}
