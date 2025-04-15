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

class StudentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche la liste des étudiants avec leurs écoles.
     */
    public function index()
    {

        // Vérifie les permissions
        $this->authorize('view', User::class);

        // Récupérer tous les utilisateurs
        $users = User::all();

        // Filtre pour ne garder que les étudiants
        $students = $users->filter(function ($user) {
            return $user->school() && $user->school()->pivot->role === 'student';
        });

        // Récupère les écoles sous forme de liste
        $schools = School::pluck('name', 'id');

        // Affiche la vue avec les étudiants et les écoles
        return view('pages.students.index', compact('students','schools'));
    }

    /**
     * Crée un nouvel étudiant et l’associe à une école.
     */
    public function store(Request $request)
    {
        // Vérifie les permissions de création
        $this->authorize('create', User::class);

        // Validation des données du formulaire
        $validatedData = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email',
            'school_id' => 'required|exists:schools,id',

        ]);


        // Génère un mdp aléatoire

        $randomPassword = Str::random(6);

        // Hachage du mdp
        $validatedData['password'] = Hash::make($randomPassword);

        // Crée le nouvel utilisateur étudiant
        $user = User::create($validatedData);

        // Association avec l’école
        UserSchool::create([
            'user_id' => $user->id,
            'school_id' => $validatedData['school_id'],
            'role' => 'student',
        ]);

        // Envoie une notification avec le mot de passe à l'utilisateur
        $user->notify(new SendPasswordNotification($randomPassword));

        // Redirige vers la liste des étudiants
        return redirect()->route('student.index');
    }

    /**
     * Supprime un étudiant et sa relation à l’école.
     */
    public function destroy($id)
    {
        // Vérifie les permissions de suppression
        $this->authorize('delete', User::class);

        // Récupère l'utilisateur à supprimer
        $student = User::findOrFail($id);

        // Vérifie la présence d'une relation avec une école en tant qu'étudiant
        $school = \DB::table('users_schools')
            ->where('user_id', $student->id)
            ->where('role', 'student')
            ->first();

        if ($school) {
            // Supprime la ligne dans users_schools
            \DB::table('users_schools')
                ->where('user_id', $student->id)
                ->delete();

            // Supprime l'utilisateur
            $student->delete();

            // Redirige vers la liste des étudiants
            return redirect()->route('student.index');
        }

        // Redirige vers la liste des étudiants
        return redirect()->route('student.index');
    }

    /**
     * Met à jour les informations d’un étudiant.
     */
    public function update(Request $request, $id)
    {
        // Vérifie les permissions de mise a jour
        $this->authorize('update', User::class);

        // Validation des données à jour
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:student,teacher',
        ]);

        // Récupère l'utilisateur
        $user = User::findOrFail($id);

        // Met à jour les données de base
        $user->update([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'birth_date' => $validated['birth_date'],
            'email' => $validated['email'],
        ]);

        // Mise à jour du rôle dans user_schools
        $school = $user->school();
        if ($school) {
            \DB::table('users_schools')
                ->where('user_id', $user->id)
                ->where('school_id', $school->id)
                ->update(['role' => $validated['role']]);
        }

        return redirect()->route('student.index');
    }

    /**
     * Récupère le formulaire HTML d’édition d’un étudiant pour un affichage AJAX.
     */
    public function getForm(User $student) {

        // Génère le contenu HTML du formulaire à partir de la vue Blade
        $html = view('pages.students.student-form', compact('student'))->render();

        // Retourne le formulaire en JSON
        return response()->json(['html' => $html]);
    }

}
