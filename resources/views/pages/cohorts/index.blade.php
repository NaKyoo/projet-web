<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Promotions') }}
            </span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">Mes promotions</h3>
                    </div>
                    <div class="card-body">
                        <div data-datatable="true" data-datatable-page-size="100">
                            <div class="scrollable-x-auto">
                                <table class="table table-border" data-datatable-table="true">
                                    <thead>
                                    <tr>
                                        <th class="min-w-[280px]">
                                            <span class="sort asc">
                                                 <span class="sort-label">Promotion</span>
                                                 <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Année</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                        <th class="min-w-[135px]">
                                            <span class="sort">
                                                <span class="sort-label">Etudiants</span>
                                                <span class="sort-icon"></span>
                                            </span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cohorts as $cohort)
                                            <tr>
                                                <td>
                                                    <div class="flex flex-col gap-2">
                                                        <a class="leading-none font-medium text-sm text-gray-900 hover:text-primary"
                                                           href="{{ route('cohort.show', $cohort->id) }}">
                                                            {{ $cohort->name }}
                                                        </a>
                                                        <span class="text-2sm text-gray-700 font-normal leading-3">
                                                            {{ $cohort->school->name }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($cohort->start_date)->format('Y') }} - {{ \Carbon\Carbon::parse($cohort->end_date)->format('Y') }}
                                                </td>
                                                <td>{{ $cohort->students->count() }}</td>

                                                <td>
                                                    <a class="hover:text-primary cursor-pointer" href="#"
                                                       data-modal-toggle="#cohort-modal"
                                                       data-cohort="{{ route('cohort.form', $cohort) }}">
                                                        <i class="ki-filled ki-cursor"></i>
                                                    </a>

                                                    <form action="{{ route('cohort.destroy', $cohort->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette promotion ?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-danger">
                                                            <i class="ki-filled ki-shield-cross"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    <tr>
                                        <td>
                                            <div class="flex flex-col gap-2">
                                                <a class="leading-none font-medium text-sm text-gray-900 hover:text-primary"
                                                   href="{{ route('cohort.show', 1) }}">
                                                    Promotion B1
                                                </a>
                                                <span class="text-2sm text-gray-700 font-normal leading-3">
                                                    Cergy
                                                </span>
                                            </div>
                                        </td>
                                        <td>2024-2025</td>
                                        <td>34</td>
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
                        Ajouter une promotion
                    </h3>
                </div>
                <div class="card-body flex flex-col gap-5">
                    <form method="POST" action="{{ route('cohort.store') }}">
                        @csrf

                        <x-forms.input name="name" :label="__('Nom')" :messages="$errors->get('name')" required />

                        <x-forms.input name="description" :label="__('Description')" :messages="$errors->get('description')" required />

                        <x-forms.input type="date" name="start_date" :label="__('Début de l\'année')" :messages="$errors->get('start_date')" required />

                        <x-forms.input type="date" name="end_date" :label="__('Fin de l\'année')" :messages="$errors->get('end_date')" required />
                        <x-forms.dropdown name="school_id" label="Choisissez une école" :messages="$errors->get('school_id')">
                            <option value="">{{ __('Sélectionnez une école') }}</option>
                            @foreach($schools as $id => $name)
                                <option value="{{ $id }}" @selected(old('school_id') == $id)>{{ $name }}</option>
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
@include('pages.cohorts.cohort-modal')
