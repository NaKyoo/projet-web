<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Etudiants') }}
            </span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Liste des étudiants</h3>
                        <div class="input input-sm max-w-48">
                            <i class="ki-filled ki-magnifier"></i>
                            <input placeholder="Rechercher un étudiant" type="text"/>
                        </div>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="5">
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
                                        <th class="w-[70px]"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            <tr>
                                                <td>{{ $student->last_name }}</td>
                                                <td>{{ $student->first_name }}</td>
                                                <td>{{ $student->birth_date }}</td>
                                                <td>
                                                    <div class="flex items-center justify-between">
                                                        <a href="#">
                                                            <i class="text-success ki-filled ki-shield-tick"></i>
                                                        </a>
                                                        <a href="#" class="hover:text-primary cursor-pointer"
                                                           data-modal-toggle="#student-modal"
                                                           data-student="{{ route('student.form', $student) }}">
                                                            <i class="ki-filled ki-cursor"></i>
                                                        </a>
                                                        <form action="{{ route('student.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-danger">
                                                                <i class="ki-filled ki-shield-cross"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
                        Ajouter un étudiant
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                    <!-- Formulaire d'ajout et de suppression d'étudiant -->
                    <form method="POST" action="{{ route('student.store') }}">
                        @csrf

                        <div class="card-body flex flex-col gap-5">

                            <x-forms.input name="last_name" :label="__('Nom')" :messages="$errors->get('last_name')"/>

                            <x-forms.input name="first_name" :label="__('Prénom')" :messages="$errors->get('first_name')" />

                            <x-forms.input type="date" name="birth_date" :label="__('Date de naissance')" placeholder="" :messages="$errors->get('email')" />

                            <x-forms.input type="email" name="email" :label="__('Email')" :messages="$errors->get('email')" />

                            <x-forms.dropdown name="school_id" label="Choisissez une école" :messages="$errors->get('school_id')">
                                @foreach($schools as $id => $name)
                                    <option value="{{ $id }}" @selected(old('school_id') == $id)>{{ $name }}</option>
                                @endforeach
                            </x-forms.dropdown>


                            <x-forms.primary-button>
                                {{ __('Valider') }}
                            </x-forms.primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>

@include('pages.students.student-modal')
