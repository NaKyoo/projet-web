@extends('layouts.modal', [
    'id'    => 'cohort-modal',
    'title'  => 'Informations promotion',] )

@section('modal-content')

    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
        <span class="text-gray-700">
            {{ __('Modifier une cohorte') }}
        </span>
        </h1>
    </x-slot>

    <div class="flex justify-center items-center min-h-screen">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Informations de la promotion
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('cohort.update', $cohort->id) }}">
                    @csrf
                    @method('PUT')

                    <x-forms.input
                        name="name"
                        :label="__('Nom')"
                        :value="old('name', $cohort->name)"
                        :messages="$errors->get('name')"
                    />

                    <x-forms.input
                        name="description"
                        :label="__('Description')"
                        :value="old('description', $cohort->description)"
                        :messages="$errors->get('description')"
                    />

                    <x-forms.input
                        type="date"
                        name="start_date"
                        :label="__('Date de début')"
                        :value="old('start_date', $cohort->start_date)"
                        :messages="$errors->get('start_date')"
                    />

                    <x-forms.input
                        type="date"
                        name="end_date"
                        :label="__('Date de fin')"
                        :value="old('end_date', $cohort->end_date)"
                        :messages="$errors->get('end_date')"
                    />

                    <x-forms.dropdown
                        name="school_id"
                        label="Choisir une école"
                        :messages="$errors->get('school_id')"
                    >
                        @foreach($schools as $id => $name)
                            <option value="{{ $id }}" @selected(old('school_id', $cohort->school_id) == $id)>{{ $name }}</option>
                        @endforeach
                    </x-forms.dropdown>

                    <x-forms.primary-button class="mt-4">
                        {{ __('Enregistrer les modifications') }}
                    </x-forms.primary-button>
                </form>
            </div>
        </div>
    </div>


@overwrite
