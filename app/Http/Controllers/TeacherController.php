<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\UserSchool;
use App\Notifications\SendPasswordNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des enseignants avec leurs écoles.
     */
    public function index()
    {
        // Vérifie si l'utilisateur a le droit de voir
        $this->authorize('view', User::class);

        // Récupère tous les utilisateurs
        $users = User::all();

        // Filtre les utilisateurs pour ne garder que les enseignants
        $teachers = $users->filter(function ($user) {
            return $user->school() && $user->school()->pivot->role === 'teacher';
        });

        // Liste des écoles pour le formulaire
        $schools = School::pluck('name', 'id');

        // Affiche la vue avec les enseignants et écoles disponibles
        return view('pages.teachers.index', compact('teachers', 'schools'));
    }

    /**
     * Crée un nouvel enseignant et l’associe à une école.
     */
    public function store(Request $request)
    {
        // Vérifie les permissions
        $this->authorize('create', User::class);

        // Validation des données envoyées depuis le formulaire
        $validatedData = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email',
            'school_id' => 'required|exists:schools,id',

        ]);

        // Génère le mdp aléatoirement
        $randomPassword = Str::random(6);

        // Hachage du mdp
        $validatedData['password'] = Hash::make($randomPassword);

        // Création de l'utilisateur
        $user = User::create($validatedData);

        // Association avec l’école
        UserSchool::create([
            'user_id' => $user->id,
            'school_id' => $validatedData['school_id'],
            'role' => 'teacher',
        ]);

        // Envoie une notification à l'enseignant avec son mot de passe
        $user->notify(new SendPasswordNotification($randomPassword));


        return redirect()->route('teacher.index');
    }

    /**
     * Supprime un enseignant ainsi que son lien avec une école.
     */
    public function destroy($id)
    {
        // Vérifie les permissions
        $this->authorize('delete', User::class);

        // Recherche de l'utilisateur
        $teacher = User::findOrFail($id);

        // Vérifie que cet utilisateur est bien un enseignant
        $school = DB::table('users_schools')
            ->where('user_id', $teacher->id)
            ->where('role', 'teacher')
            ->first();

        if ($school) {
            // Supprime la ligne dans users_schools
            DB::table('users_schools')
                ->where('user_id', $teacher->id)
                ->delete();

            // Supprime l'utilisateur
            $teacher->delete();
        }

        return redirect()->route('teacher.index');
    }

    /**
     * Met à jour les informations d’un enseignant.
     */
    public function update(Request $request, $id)
    {
        // vérifie les permissions
        $this->authorize('update', User::class);

        // Validation des données
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:student,teacher',
        ]);

        // Récupère l'utilisateur à mettre à jour
        $user = User::findOrFail($id);

        // Met à jour les champs de l'utilisateur
        $user->update([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'birth_date' => $validated['birth_date'],
            'email' => $validated['email'],
        ]);

        // Mise à jour du rôle dans la table user_schools
        $school = $user->school();
        if ($school) {
            DB::table('users_schools')
                ->where('user_id', $user->id)
                ->where('school_id', $school->id)
                ->update(['role' => $validated['role']]);
        }

        return redirect()->route('teacher.index');
    }

    /**
     * Retourne le formulaire HTML pour éditer un enseignant (utilisé avec AJAX).
     */
    public function getForm(User $teacher) {

        // Charge la vue du formulaire avec l'enseignant passé en paramètre
        $html = view('pages.teachers.teacher-form', compact('teacher'))->render();

        // Renvoie le HTML dans une réponse JSON
        return response()->json(['html' => $html]);
    }
}
