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
        <div class="lg:col-span-2">
            <div class="grid">
                <div class="card card-grid h-full min-w-full">
                    <div class="card-header">
                        <h3 class="card-title">
                            @foreach($cohorts as $cohort)
                                <div class="p-3 border rounded-md shadow-sm bg-white">
                                    <h4 class="text-base font-semibold text-gray-800">
                                        {{ $cohort->name }}
                                    </h4>
                                    <p class="text-sm text-gray-600">
                                        École : {{ $cohort->school->name ?? 'Non définie' }}<br>
                                        Année : {{ \Carbon\Carbon::parse($cohort->start_date)->format('Y') }}
                                        - {{ \Carbon\Carbon::parse($cohort->end_date)->format('Y') }}
                                    </p>
                                </div>
                            @endforeach

                        </h3>
                    </div>
                    <div class="card-body flex flex-col gap-5">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: grid -->
</x-app-layout>
