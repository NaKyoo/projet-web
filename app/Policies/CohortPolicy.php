<?php

namespace App\Policies;

use App\Models\Cohort;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CohortPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->school()->pivot->role == 'admin') {
            return true;
        }

        // Si l'utilisateur est un teacher, il peut voir les cohortes auxquelles il est assigné
        if ($user->school()->pivot->role == 'teacher') {
            // Vérifier si l'utilisateur est associé à au moins une cohorte
            return $user->cohorts()->exists();  // Vérifie s'il a des cohortes attribuées
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cohort $cohort): bool
    {
        return false;
    }

    /**
     * Vérifie si l'utilisateur est un admin pour créer une cohorte.
     */
    public function create(User $user): bool
    {
        return $user->school()->pivot->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un admin pour mettre à jour une cohorte.
     */
    public function update(User $user, Cohort $cohort): bool
    {
        return $user->school()->pivot->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un admin pour supprimer une cohorte.
     */
    public function delete(User $user, Cohort $cohort): bool
    {
        return $user->school()->pivot->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un admin pour ajouter un étudiant.
     */
    public function addStudent(User $user, Cohort $cohort): bool
    {
        return $user->school()->pivot->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un admin pour supprimer un étudiant.
     */
    public function deleteStudent(User $user, Cohort $cohort): bool
    {
        return $user->school()->pivot->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un admin pour ajouter un enseignant.
     */
    public function addTeacher(User $user, Cohort $cohort): bool
    {
        return $user->school()->pivot->role === 'admin';
    }

    /**
     * Vérifie si l'utilisateur est un admin pour supprimer un enseignant.
     */
    public function deleteTeacher(User $user, Cohort $cohort): bool
    {
        return $user->school()->pivot->role === 'admin';
    }
}
