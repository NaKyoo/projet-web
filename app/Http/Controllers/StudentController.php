<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
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
    public function index()
    {

        $this->authorize('view', User::class);

        // Récupérer tous les utilisateurs
        $users = User::all();

        $students = $users->filter(function ($user) {
            return $user->school() && $user->school()->pivot->role === 'student';
        });

        // Récupération des écoles
        $schools = School::pluck('name', 'id');

        return view('pages.students.index', compact('students','schools'));
    }

    // Fonction pour créer un utilisateur dans la base de donnée
    public function store(Request $request)
    {
        // $this->authorize('create', User::class);

        // Validation des données
        $validatedData = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email',
            'school_id' => 'required|exists:schools,id',

        ]);


        // Génère le mdp
        $randomPassword = Str::random(6);

        // Hachage du mdp
        $validatedData['password'] = Hash::make($randomPassword);

        $user = User::create($validatedData);

        // Association avec l’école
        UserSchool::create([
            'user_id' => $user->id,
            'school_id' => $validatedData['school_id'],
            'role' => 'student',
        ]);

        $user->notify(new SendPasswordNotification($randomPassword));

        return redirect()->route('student.index');
    }

    public function destroy($id)
    {
        $student = User::findOrFail($id);

        // On récupère la ligne manuellement
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

            return redirect()->route('student.index');
        }

        return redirect()->route('student.index');
    }

    public function update(Request $request, $id)
    {
        // Vérification des infos
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:student,teacher',
        ]);

        // Récupération de l'ID
        $user = User::findOrFail($id);

        // Mise à jour des infos
        $user->update([
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'birth_date' => $validated['birth_date'],
            'email' => $validated['email'],
        ]);

        // Mise à jour du rôle
        $school = $user->school();
        if ($school) {
            \DB::table('users_schools')
                ->where('user_id', $user->id)
                ->where('school_id', $school->id)
                ->update(['role' => $validated['role']]);
        }

        return redirect()->route('student.index');
    }


    public function getForm(User $student) {
        $html = view('pages.students.student-form', compact('student'))->render();

        return response()->json(['html' => $html]);
    }

}
