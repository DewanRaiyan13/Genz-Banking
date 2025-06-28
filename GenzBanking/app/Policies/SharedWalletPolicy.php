<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\SharedWallet;
use App\Models\User;

class SharedWalletPolicy
{
    public function view(User $user, SharedWallet $wallet)
    {
        // Allow if user is creator or a member
        return $wallet->creator_id === $user->id || $wallet->members->contains($user->id);
    }

    public function update(User $user, SharedWallet $wallet)
    {
        return $wallet->creator_id === $user->id || $wallet->members->contains($user->id);
    }
}
