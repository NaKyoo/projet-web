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

    /**
     * Détermine si l'utilisateur peut **voir** des ressources liées aux utilisateurs
     */
    public function view(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Détermine si l'utilisateur peut **créer** un nouvel utilisateur
     */
    public function create(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Détermine si l'utilisateur peut **mettre à jour** un utilisateur
     */
    public function update(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }

    /**
     * Détermine si l'utilisateur peut **supprimer** un utilisateur
     */
    public function delete(User $user): bool
    {
        return \DB::table('users_schools')
            ->where('user_id', $user->id)
            ->where('role', 'admin')
            ->exists();
    }
}
