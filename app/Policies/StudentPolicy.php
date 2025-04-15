<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si l'utilisateur peut voir la page des étudiants.
     */
    public function view(User $user): bool
    {
        // Autoriser l'accès aux étudiants uniquement aux admins
        if ($user->school()->pivot->role == 'admin') {
            return true;
        }

        // Empêcher les teachers d'accéder à la page des étudiants
        return false;
    }
}
