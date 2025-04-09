<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\School;
use App\Models\UserSchool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $schools = School::pluck('name', 'id');
        return view('pages.teachers.index', compact('schools'));
    }

    // Fonction pour créer un utilisateur dans la base de donnée
    public function store(Request $request)
    {
        $this->authorize('create', new User());

        // Validation des données
        $validatedData = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'email' => 'required|email|unique:users,email',
            'school_id' => 'required|exists:schools,id',

        ]);

        // Hachage du mdp
        $validatedData['password'] = Hash::make(Str::random(6));

        $user = User::create($validatedData);

        // Association avec l’école
        UserSchool::create([
            'user_id' => $user->id,
            'school_id' => $validatedData['school_id'],
            'role' => 'teacher',
        ]);


        return redirect()->route('teacher.index');
    }
}
