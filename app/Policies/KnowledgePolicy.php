<?php

namespace App\Policies;

use App\Models\Knowledge;
use App\Models\User;

class KnowledgePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Knowledge $knowledge): bool
    {
        if (in_array($user->role, ['super_admin', 'admin', 'verifikator'])) {
            return true;
        }

        if ($user->role === 'user') {
            $isVerified = $knowledge->status && isset($knowledge->status->key) && $knowledge->status->key === 'verified';
            $isCreator  = $knowledge->created_by === $user->id;
            return $isVerified || $isCreator;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['user', 'verifikator', 'admin', 'super_admin']);
    }

    public function update(User $user, Knowledge $knowledge): bool
    {
        if (in_array($user->role, ['super_admin', 'admin'])) {
            return true;
        }
        return $knowledge->created_by === $user->id;
    }

    public function delete(User $user, Knowledge $knowledge): bool
    {
        return in_array($user->role, ['admin', 'super_admin']);
    }

    public function verify(User $user, Knowledge $knowledge): bool
    {
        return in_array($user->role, ['verifikator', 'super_admin']);
    }

    public function restore(User $user, Knowledge $knowledge): bool
    {
        return false;
    }

    public function forceDelete(User $user, Knowledge $knowledge): bool
    {
        return false;
    }
}
