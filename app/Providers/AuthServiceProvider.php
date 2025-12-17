<?php

namespace App\Providers;

use App\Models\KnowledgeComment;
use App\Policies\KnowledgeCommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        KnowledgeComment::class => KnowledgeCommentPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
