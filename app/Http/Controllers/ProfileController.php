<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche le formulaire d'édition du profil de l'utilisateur.
     * Cette méthode est utilisée pour afficher les informations actuelles de l'utilisateur dans un formulaire.
     *
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        // Retourne la vue avec les données de l'utilisateur
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Met à jour les informations du profil de l'utilisateur.
     * Cette méthode permet de mettre à jour les informations de base, le mot de passe et l'avatar de l'utilisateur.
     *
     * @param ProfileUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {

        // Récupère l'utilisateur actuellement connecté
        $user = $request->user();

        // Mise à jour des informations de base (prénom, nom, email) si les champs sont remplis
        if ($request->filled('first_name')) {
            $user->first_name = $request->input('first_name');
        }

        if ($request->filled('last_name')) {
            $user->last_name = $request->input('last_name');
        }

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }


        // Mise à jour du mdp
        if ($request->filled('current_password') && $request->filled('new_password') && $request->filled('new_password_confirmation')) {
            // Vérification du mdp actuel
            if (Hash::check($request->current_password, $user->password)) {

                // Vérifie si le mdp est identique à l'ancien
                if ($request->current_password === $request->new_password) {
                    return back()->withErrors(['new_password' => 'Le nouveau mot de passe doit être différent de l\'ancien.']);
                }

                // Vérification si les nouveaux mdp correspondent
                if ($request->new_password !== $request->new_password_confirmation) {
                    return back()->withErrors(['new_password' => 'Les nouveaux mots de passe ne correspondent pas.']);
                }

                // Mise à jour du mdp
                $user->password = bcrypt($request->new_password);
            } else {
                // Si le mdp actuel ne correspond pas
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
        }


        // Changement ou suppression de l'avatar
        if ($request->has('avatar_remove') && $request->avatar_remove == 1) {
            // Si l'avatar doit être supprimé
            if ($user->avatar) {
                // Supprimer le fichier existant du stockage
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = 'metronic/media/avatars/300-2.png'; // Mettre le chemin de l'image par défaut
        } elseif ($request->hasFile('avatar')) {
            // Si un avatar existant est présent, on le supprime
            if ($user->avatar) {
                // Supprimer l'ancien fichier du stockage
                Storage::disk('public')->delete($user->avatar);
            }

            // Récupérer le fichier téléchargé
            $file = $request->file('avatar');

            // Créer un nom unique pour le fichier d'avatar
            $filename = uniqid('avatar_', true) . '.' . $file->getClientOriginalExtension();

            // Stocke l'avatar sous un nom unique dans le dossier 'avatars'
            $path = $file->storeAs('avatars', $filename, 'public');

            // Met à jour l'avatar de l'utilisateur avec le chemin du fichier
            $user->avatar = $path;
        }

        // Sauvegarde les informations mises à jour de l'utilisateur
        $user->save();


        // Redirige l'utilisateur vers la page de modification de profil
        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully!');
    }

    /**
     * Supprime le compte utilisateur ou le désactive.
     * Cette méthode permet de supprimer ou de désactiver un compte utilisateur en fonction de l'action choisie.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Récupère l'utilisateur actuellement connecté
        $user = $request->user();

        // Si l'utilisateur souhaite désactiver son compte
        if ($request->input('action') === 'deactivate') {
            // Désactivation du compte
            $user->is_active = false;
            $user->save();

            // Déconnexion de l'utilisateur et invalidation de la session
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirige l'utilisateur vers la page d'accueil
            return redirect('/')->with('status', 'Votre compte a été désactivé.');
        }

        // Si l'utilisateur souhaite supprimer son compte
        if ($request->input('action') === 'delete') {
            // Validation avant suppression
            $request->validateWithBag('userDeletion', [
                'confirm_delete' => ['required', 'accepted'],
            ]);

            // Déconnexion de l'utilisateur et suppression de son compte
            Auth::logout();
            $user->delete();

            // Invalidations de la session et régénération du token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirige l'utilisateur vers la page d'accueil
            return redirect('/')->with('status', 'Votre compte a été supprimé.');
        }
        // Si aucune action valide n'a été spécifiée
        return back()->withErrors(['action' => 'Aucune action valide spécifiée.']);
    }

}
