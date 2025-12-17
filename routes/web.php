<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\KnowledgeRatingController;
use App\Http\Controllers\KnowledgeCommentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScopeController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ------------------ PUBLIC (guest) routes ------------------

// Welcome (homepage)
Route::get('/', function () {
    $publicKnowledges = \App\Models\Knowledge::with(['scope','status'])
        ->where('visibility', 'public')
        ->whereHas('status', fn($q) => $q->where('key', 'verified'))
        ->latest()
        ->take(6)
        ->get();

    return view('welcome', compact('publicKnowledges'));
})->name('welcome');

// Public knowledge listing & detail
Route::get('/knowledge/public', [KnowledgeController::class, 'publicIndex'])->name('knowledge.public.index');
Route::get('/knowledge/public/{knowledge}', [KnowledgeController::class, 'publicShow'])->name('knowledge.public.show');

// Public Tag routes (by slug)
Route::get('/tags', [TagController::class, 'index'])->name('tags.index');
Route::get('/tags/{tag}', [TagController::class, 'show'])->name('tags.show');


// ------------------ AUTHENTICATED routes ------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Knowledge CRUD (auth)
    Route::resource('knowledge', KnowledgeController::class)->except(['destroy']);

    // Knowledge destroy (hard delete) - restricted
    Route::delete('knowledge/{knowledge}', [KnowledgeController::class, 'destroy'])
        ->name('knowledge.destroy')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin,super_admin');

    // Verify / Publish / Toggle (restricted)
    Route::post('knowledge/{knowledge}/verify', [KnowledgeController::class, 'verify'])
        ->name('knowledge.verify')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class . ':verifikator,super_admin');

    Route::post('/knowledge/{knowledge}/publish', [KnowledgeController::class, 'publish'])
        ->name('knowledge.publish')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class . ':verifikator,super_admin');

    Route::post('/knowledge/{knowledge}/toggle-visibility', [KnowledgeController::class, 'toggleVisibility'])
        ->name('knowledge.toggleVisibility')
        ->middleware(\App\Http\Middleware\RoleMiddleware::class . ':verifikator,super_admin');

    // ------------------ TAGGING OPERATIONS (Auth required) ------------------
    Route::post('/knowledge/{knowledge}/tags/sync', [\App\Http\Controllers\KnowledgeTagController::class, 'sync'])
        ->name('knowledge.tags.sync');

    Route::post('/knowledge/{knowledge}/tags/add', [\App\Http\Controllers\KnowledgeTagController::class, 'add'])
        ->name('knowledge.tags.add');

    Route::post('/knowledge/{knowledge}/tags/remove', [\App\Http\Controllers\KnowledgeTagController::class, 'remove'])
        ->name('knowledge.tags.remove');

    // Profile (auth)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Scope management (admin + super_admin)
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin,super_admin')->group(function () {
        Route::resource('scope', ScopeController::class)->except(['show']);
    });

    // User management (super_admin)
    Route::middleware(\App\Http\Middleware\RoleMiddleware::class . ':super_admin')->group(function () {
        Route::resource('user-management', UserManagementController::class)
            ->parameters(['user-management' => 'user']);

        // Activity logs
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity.logs');
        Route::get('activity-logs/export', [ActivityLogController::class, 'exportCsv'])->name('activity.logs.export');

        // User trash management (trashed / restore / force delete)
        Route::get('/user-management/trashed', [UserManagementController::class, 'trashed'])
            ->name('user-management.trashed');

        Route::post('/user-management/{id}/restore', [UserManagementController::class, 'restore'])
            ->name('user-management.restore');

        Route::delete('/user-management/{id}/force-delete', [UserManagementController::class, 'forceDelete'])
            ->name('user-management.forceDelete');
    });

    Route::middleware('web')->group(function () {
        Route::get('/knowledge/{knowledge}', [KnowledgeController::class, 'show'])
            ->name('knowledge.show');
    });

        Route::post('/knowledge/{knowledge}/rate', [KnowledgeRatingController::class, 'store'])
            ->middleware('auth')
            ->name('knowledge.rate');

        Route::post('/knowledge/{knowledge}/comment', [KnowledgeCommentController::class, 'store'])
            ->middleware('auth')
            ->name('knowledge.comment.store');

        Route::put('/comment/{comment}', [KnowledgeCommentController::class, 'update'])
            ->middleware('auth')
            ->name('knowledge.comment.update');

        Route::delete('/comment/{comment}', [KnowledgeCommentController::class, 'destroy'])
            ->middleware('auth')
            ->name('knowledge.comment.destroy');


    

}); // end auth group

require __DIR__ . '/auth.php';
