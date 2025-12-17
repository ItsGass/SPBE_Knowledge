<?php

namespace App\Policies;

use App\Models\KnowledgeComment;
use App\Models\User;

class KnowledgeCommentPolicy
{
    public function update(User $user, KnowledgeComment $comment): bool
    {
        return $user->id === $comment->user_id;
    }

    public function delete(User $user, KnowledgeComment $comment): bool
    {
        return $user->id === $comment->user_id;
    }
}
