@extends('layouts.modal', [
    'id'    => 'teacher-modal',
    'title'  => 'Informations enseignant',] )

@section('modal-content')
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
        <span class="text-gray-700">
            {{ __('Modifier un enseignant') }}
        </span>
        </h1>
    </x-slot>

    <div class="flex justify-center items-center min-h-screen">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Informations de l’enseignant
                </h3>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>
@overwrite
