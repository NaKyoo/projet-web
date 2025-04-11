<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        // Mise à jour des informations de base (nom, prénom, email)
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

                // Vérifie si le mdp est différent de l'ancien
                if ($request->current_password === $request->new_password) {
                    return back()->withErrors(['new_password' => 'Le nouveau mot de passe doit être différent de l\'ancien.']);
                }

                // Vérification des nouveaux mdp
                if ($request->new_password !== $request->new_password_confirmation) {
                    return back()->withErrors(['new_password' => 'Les nouveaux mots de passe ne correspondent pas.']);
                }

                // Mise à jour du mdp
                $user->password = bcrypt($request->new_password);
            } else {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
        }

        // Mise à jour de l'avatar si une nouvelle image est envoyée
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->input('action') === 'deactivate') {
            // Désactivation du compte
            $user->is_active = false;
            $user->save();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'Votre compte a été désactivé.');
        }

        if ($request->input('action') === 'delete') {
            // Validation pour suppression
            $request->validateWithBag('userDeletion', [
                'confirm_delete' => ['required', 'accepted'],
            ]);

            Auth::logout();
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('status', 'Votre compte a été supprimé.');
        }

        return back()->withErrors(['action' => 'Aucune action valide spécifiée.']);
    }

}
