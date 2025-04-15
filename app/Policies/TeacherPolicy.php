<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Vérifie si l'utilisateur peut voir la page des enseignants.
     */
    public function view(User $user): bool
    {
        // Autoriser l'accès aux enseignants uniquement aux admins
        if ($user->school()->pivot->role == 'admin') {
            return true;
        }

        // Empêcher les teachers d'accéder à la page des enseignants
        return false;
    }
}
