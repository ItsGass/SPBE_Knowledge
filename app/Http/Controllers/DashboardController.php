<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Knowledge;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Statistik sederhana (opsional)
        $totalKnowledge = Knowledge::count();
        $verifiedCount = Knowledge::whereHas('status', fn($q) => $q->where('key', 'verified'))->count();
        $draftCount = Knowledge::whereHas('status', fn($q) => $q->where('key', 'draft'))->count();

        return view('dashboard', compact('user', 'totalKnowledge', 'verifiedCount', 'draftCount'));
    }
}
