<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">{{ $cohort->name }}</span>
        </h1>
    </x-slot>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @elseif(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Etudiants</h3>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="30">
                            <div class="scrollable-x-auto">
                                <table class="table table-border" data-datatable-table="true">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[135px]">
                                            <span class="sort asc">
                                                 <span class="sort-label">Nom</span>
                                                 <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Prénom</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Date de naissance</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="max-w-[50px]"></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($cohortStudents as $cohortStudent)
                                        <tr>
                                            <td>{{ $cohortStudent->last_name }}</td>
                                            <td>{{ $cohortStudent->first_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($cohortStudent->birth_date)->format('d/m/Y') }}</td>


                                            <td class="cursor-pointer pointer">
                                                <form method="POST" action="{{ route('cohorts.deleteStudent', ['cohort' => $cohort->id, 'userId' => $cohortStudent->id]) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit">
                                                        <i class="ki-filled ki-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                        <tr>
                                        <td>Doe</td>
                                        <td>John</td>
                                        <td>10/02/2000</td>
                                        <td class="cursor-pointer pointer">
                                            <i class="ki-filled ki-trash"></i>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer justify-center md:justify-between flex-col md:flex-row gap-5 text-gray-600 text-2sm font-medium">
                                <div class="flex items-center gap-2 order-2 md:order-1">
                                    Show
                                    <select class="select select-sm w-16" data-datatable-size="true" name="perpage"></select>
                                    per page
                                </div>
                                <div class="flex items-center gap-4 order-1 md:order-2">
                                    <span data-datatable-info="true"></span>
                                    <div class="pagination" data-datatable-pagination="true"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="card h-full">
                <div class="card-header">
                    <h3 class="card-title">
                        Ajouter un étudiant à la promotion
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                    <form method="POST" action="{{ route('cohorts.addStudent', ['cohort' => $cohort->id]) }}">
                        @csrf
                        <x-forms.dropdown name="user_id" :label="__('Etudiant')">
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </option>

                            @endforeach
                        </x-forms.dropdown>

                        <x-forms.primary-button>
                            {{ __('Valider') }}
                        </x-forms.primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>
