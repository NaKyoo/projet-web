@extends('layouts.modal', [
    'id'    => 'student-modal',
    'title'  => 'Informations étudiant',] )

@section('modal-content')

    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
        <span class="text-gray-700">
            {{ __('Modifier un étudiant') }}
        </span>
        </h1>
    </x-slot>

    <div class="flex justify-center items-center min-h-screen">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Informations de l’étudiant
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('student.update', $student->id) }}">
                    @csrf
                    @method('PUT')

                    <x-forms.input
                        name="last_name"
                        :label="__('Nom')"
                        :value="old('last_name', $student->last_name)"
                        :messages="$errors->get('last_name')"
                    />

                    <x-forms.input
                        name="first_name"
                        :label="__('Prénom')"
                        :value="old('first_name', $student->first_name)"
                        :messages="$errors->get('first_name')"
                    />

                    <x-forms.input
                        type="date"
                        name="birth_date"
                        :label="__('Date de naissance')"
                        :value="old('birth_date', $student->birth_date)"
                        :messages="$errors->get('birth_date')"
                    />

                    <x-forms.input
                        type="email"
                        name="email"
                        :label="__('Email')"
                        :value="old('email', $student->email)"
                        :messages="$errors->get('email')"
                    />

                    <x-forms.dropdown
                        name="role"
                        label="Statut"
                        :messages="$errors->get('role')"
                    >
                        <option value="student" @selected(old('role', $student->school()?->pivot->role) === 'student')>
                            Étudiant
                        </option>
                        <option value="teacher" @selected(old('role', $student->school()?->pivot->role) === 'teacher')>
                            Enseignant
                        </option>
                    </x-forms.dropdown>

                    <x-forms.primary-button class="mt-4">
                        {{ __('Enregistrer les modifications') }}
                    </x-forms.primary-button>
                </form>
            </div>
        </div>
    </div>


@overwrite
