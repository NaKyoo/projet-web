<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                {{ __('Dashboard') }}
            </span>
        </h1>
    </x-slot>

    <!-- begin: grid -->
    <div class="grid lg:grid-cols-3 gap-5 lg:gap-7.5 items-stretch">
        <div class="card card-grid lg:col-span-1 h-full min-w-full">
            <div class="card-header">
                <h3 class="card-title flex justify-between">
                    Nombre d'étudiants  <span style="margin-left: 100px; font-size: 20px">{{ $studentsCount }}</span>
                </h3>
            </div>
            <a href="{{ route('student.index') }}" class="btn btn-primary">Voir les étudiants</a>
        </div>

        <div class="card card-grid lg:col-span-1 h-full min-w-full">
            <div class="card-header">
                <h3 class="card-title">
                    Nombre d'enseignants  <span style="margin-left: 100px; font-size: 20px">{{ $teachersCount }}</span>
                </h3>
            </div>
            <a href="{{ route('teacher.index') }}" class="btn btn-primary">Voir les enseignants</a>

        </div>

        <div class="card card-grid lg:col-span-1 h-full min-w-full">
            <div class="card-header">
                <h3 class="card-title">
                    Nombre de promotions  <span style="margin-left: 100px; font-size: 20px">{{ $cohortsCount }}</span>
                </h3>
            </div>
            <a href="{{ route('cohort.index') }}" class="btn btn-primary">Voir les promotions</a>

        </div>

        <div class="card card-grid lg:col-span-1 h-full min-w-full">
            <div class="card-header">
                <h3 class="card-title">
                    Nombre de groupes  <span style="margin-left: 100px; font-size: 20px">0</span>
                </h3>
            </div>
            <a href="{{ route('group.index') }}" class="btn btn-primary">Voir les groupes</a>

        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>
