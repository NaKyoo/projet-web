<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    public function create(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    public function update(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    public function delete(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }
}
