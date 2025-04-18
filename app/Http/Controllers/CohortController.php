<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\School;
use App\Models\User;
use App\Notifications\UserAddedToCohort;
use App\Notifications\UserRemovedFromCohort;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class CohortController extends Controller
{
    use AuthorizesRequests;

    /**
     * Affiche toutes les cohortes disponibles
     * Cette méthode est responsable de récupérer et d'afficher toutes les cohortes en fonction des rôles des utilisateurs
     *
     * @return Factory|View|Application|object
     */
    public function index() {

        $user = auth()->user();

        // Vérification des permissions pour voir les cohortes
        $this->authorize('viewAny', Cohort::class);

        // Si l'utilisateur est un admin, on récupère toutes les cohortes
        if ($user->school()->pivot->role == 'admin') {
            // Récupérer toutes les cohortes avec écoles et étudiants
            $cohorts = Cohort::with('school', 'students')->get();
        } else {
            // Si l'utilisateur est un teacher, on récupère uniquement les cohortes auxquelles il est associé
            $cohorts = $user->cohorts()
            ->with('school', 'students')
                ->get();
        }

        // Récupérer les écoles pour le dropdown
        $schools = School::pluck('name', 'id');

        // Retourner la vue avec les cohortes et les écoles
        return view('pages.cohorts.index', compact('cohorts', 'schools'));
    }


    /**
     * Affiche une cohorte spécifique
     *
     * @param Cohort $cohort
     * @return Application|Factory|object|View
     */
    public function show(Cohort $cohort) {

        // Récupère tous les étudiants de la base de données
        $students = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->select('users.*')
            ->get();

        // Récupère tous les enseignants de la base de données
        $teachers = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'teacher')
            ->select('users.*')
            ->get();

        // Récupère les étudiants et enseignants associés à la cohorte spécifiée
        $cohortStudents = $cohort->students;
        $cohortTeachers = $cohort->teachers;

        // Retourne la vue 'show' avec les détails de la cohorte, étudiants et enseignants
        return view('pages.cohorts.show', [
            'cohort' => $cohort,
            'students' => $students,
            'cohortStudents' => $cohortStudents,
            'teachers' => $teachers,
            'cohortTeachers' => $cohortTeachers,
            ]);

    }


    /**
     * Crée une nouvelle cohorte
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Vérification des permissions pour créer une cohorte
        $this->authorize('create', Cohort::class);

        // Validation des données du formulaire
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    $endDate = \Carbon\Carbon::parse($value);
                    if ($startDate->diffInDays($endDate) > 366) {
                        $fail('La durée de la promotion ne peut pas dépasser un an.');
                    }
                },
            ],
            'school_id' => 'required|exists:schools,id',
        ]);



        // Création de la cohorte avec les données validées
        Cohort::create($validated);

        // Redirection vers la liste des cohortes
        return redirect()->route('cohort.index');
    }

    /**
     * Supprime une cohorte
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Trouver la cohorte à supprimer
        $cohort = Cohort::findOrFail($id);

        // Vérification des permissions pour supprimer la cohorte
        $this->authorize('delete', $cohort);

        // Supprimer la cohorte
        $cohort->delete();

        // Redirection vers la liste des cohortes
        return redirect()->route('cohort.index');
    }

    /**
     * Met à jour une cohorte
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Trouver la cohorte à mettre à jour
        $cohort = Cohort::findOrFail($id);

        // Vérification des permissions pour modifier la cohorte
        $this->authorize('update', $cohort);

        // Validation des données du formulaire
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => [
                'required',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    $startDate = \Carbon\Carbon::parse($request->start_date);
                    $endDate = \Carbon\Carbon::parse($value);
                    if ($startDate->diffInDays($endDate) > 366) {
                        $fail('La durée de la promotion ne peut pas dépasser un an.');
                    }
                },
            ],
            'school_id' => 'required|exists:schools,id',
        ]);


        // Mise à jour de la cohorte avec les nouvelles données
        $cohort->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'school_id' => $validatedData['school_id'],
        ]);

        // Redirection vers la liste des cohortes
        return redirect()->route('cohort.index');
    }

    /**
     * Ajoute un étudiant à une cohorte
     *
     * @param Request $request
     * @param Cohort $cohort
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addStudent(Request $request, Cohort $cohort)
    {
        // Vérification des permissions pour ajouter un étudiant
        $this->authorize('addStudent', $cohort);

        // Validation de l'ID de l'étudiant
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Ajoute l'étudiant à la cohorte
        $cohort->students()->attach($validated['user_id']);

        // Envoie la notification à l’étudiant concerné
        $user = User::findOrFail($validated['user_id']);
        $user->notify(new UserAddedToCohort($cohort));


        // Redirection vers la page de la cohorte
        return redirect()->route('cohort.show', $cohort);
    }

    /**
     * Supprime un étudiant d'une cohorte
     *
     * @param Cohort $cohort
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteStudent(Cohort $cohort, $userId)
    {

        // Vérification des permissions pour supprimer un étudiant
        $this->authorize('deleteStudent', $cohort);

        // Supprime l'étudiant de la cohorte
        $cohort->students()->detach($userId);

        // Récupérer l'utilisateur à retirer de la cohorte
        $user = User::find($userId);

        // Envoyer la notification de retrait de cohorte
        $user->notify(new UserRemovedFromCohort($cohort));

        // Redirection vers la page de la cohorte
        return redirect()->route('cohort.show', $cohort);

    }

    /**
     * Ajoute un enseignant à une cohorte
     *
     * @param Request $request
     * @param Cohort $cohort
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addTeacher(Request $request, Cohort $cohort)
    {
        // Vérification des permissions pour ajouter un enseignant
        $this->authorize('addTeacher', $cohort);

        // Validation de l'ID de l'enseignant
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Ajoute l'enseignant à la cohorte
        $cohort->teachers()->attach($validated['user_id']);

        // Envoie la notification à l’étudiant concerné
        $user = User::findOrFail($validated['user_id']);
        $user->notify(new UserAddedToCohort($cohort));

        // Redirection vers la page de la cohorte
        return redirect()->route('cohort.show', $cohort);
    }

    /**
     * Supprime un enseignant d'une cohorte
     *
     * @param Cohort $cohort
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */

    public function deleteTeacher(Cohort $cohort, $userId)
    {
        // Vérification des permissions pour supprimer un enseignant
        $this->authorize('deleteTeacher', $cohort);

        // Supprime l'enseignant de la cohorte
        $cohort->teachers()->detach($userId);

        // Récupérer l'utilisateur à retirer de la cohorte
        $user = User::find($userId);

        // Envoyer la notification de retrait de cohorte
        $user->notify(new UserRemovedFromCohort($cohort));

        // Redirection vers la page de la cohorte
        return redirect()->route('cohort.show', $cohort);
    }

    /**
     * Retourne un formulaire HTML pour modifier une cohorte
     *
     * @param Cohort $cohort
     * @return \Illuminate\Http\JsonResponse
     */
    public function getForm(Cohort $cohort)
    {
        // Récupère la liste des écoles pour le dropdown dans le formulaire
        $schools = School::pluck('name', 'id');

        // Génère et retourne la vue HTML du formulaire
        $html = view('pages.cohorts.cohort-form', compact('cohort', 'schools'))->render();

        return response()->json(['html' => $html]);
    }

}
