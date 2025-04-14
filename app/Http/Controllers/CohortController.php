<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\School;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

use Carbon\Carbon;


class CohortController extends Controller
{
    /**
     * Display all available cohorts
     * @return Factory|View|Application|object
     */
    public function index() {

        // Récupérer les promotions avec écoles + étudiants
        $cohorts = Cohort::with('school', 'students')->get();



        // Récupérer les écoles pour le dropdown
        $schools = School::pluck('name', 'id');

        return view('pages.cohorts.index', compact('cohorts', 'schools'));
    }


    /**
     * Display a specific cohort
     * @param Cohort $cohort
     * @return Application|Factory|object|View
     */
    public function show(Cohort $cohort) {

        $students = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->select('users.*')
            ->get();



        $cohortStudents = $cohort->students;


        return view('pages.cohorts.show', [
            'cohort' => $cohort,
            'students' => $students,
            'cohortStudents' => $cohortStudents
        ]);
    }



    public function store(Request $request)
    {
        // Validation
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'school_id' => 'required|exists:schools,id',
        ]);

        // Création de la cohorte
        Cohort::create($validated);

        return redirect()->route('cohort.index');
    }

    public function destroy($id)
    {
        // Récupérer l'ID
        $cohort = Cohort::findOrFail($id);

        // Supprimer la cohorte
        $cohort->delete();

        return redirect()->route('cohort.index');
    }

    public function update(Request $request, $id)
    {
        // Trouver l'ID
        $cohort = Cohort::findOrFail($id);

        // Validation
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'school_id' => 'required|exists:schools,id',
        ]);


        // Mettre à jour les informations
        $cohort->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'school_id' => $validatedData['school_id'],
        ]);

        return redirect()->route('cohort.index');
    }


    public function addStudent(Request $request, Cohort $cohort)
    {
        // Valider l'ID de l'étudiant
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Ajouter l'étudiant à la cohorte
        $cohort->students()->attach($validated['user_id']);

        return redirect()->route('cohort.show', $cohort);
    }

    public function deleteStudent(Cohort $cohort, $userId)
    {

        $cohort->students()->detach($userId);
        return redirect()->route('cohort.show', $cohort);

    }

    public function getForm(Cohort $cohort)
    {
        $schools = School::pluck('name', 'id');

        $html = view('pages.cohorts.cohort-form', compact('cohort', 'schools'))->render();

        return response()->json(['html' => $html]);
    }

}
