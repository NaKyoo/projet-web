<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function updateInformation(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        // Met à jour les informations de l'utilisateur
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;

        // Mise à jour de l'avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully');
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $request->user()->id],
        ]);

        $user = $request->user();

        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        // Retourner avec un message de succès
        return redirect()->route('profile.edit')->with('status', 'Email updated successfully');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
