<form method="POST" action="{{ route('teacher.update', $teacher->id) }}">
    @csrf
    @method('PUT')

    <x-forms.input
        name="last_name"
        :label="__('Nom')"
        :value="old('last_name', $teacher->last_name)"
        :messages="$errors->get('last_name')"
    />

    <x-forms.input
        name="first_name"
        :label="__('Prénom')"
        :value="old('first_name', $teacher->first_name)"
        :messages="$errors->get('first_name')"
    />

    <x-forms.input
        type="date"
        name="birth_date"
        :label="__('Date de naissance')"
        :value="old('birth_date', $teacher->birth_date)"
        :messages="$errors->get('birth_date')"
    />

    <x-forms.input
        type="email"
        name="email"
        :label="__('Email')"
        :value="old('email', $teacher->email)"
        :messages="$errors->get('email')"
    />

    <x-forms.dropdown
        name="role"
        label="Statut"
        :messages="$errors->get('role')"
    >
        <option value="student" @selected(old('role', $teacher->school()?->pivot->role) === 'student')>
            Étudiant
        </option>
        <option value="teacher" @selected(old('role', $teacher->school()?->pivot->role) === 'teacher')>
            Enseignant
        </option>
    </x-forms.dropdown>

    <x-forms.primary-button class="mt-4">
        {{ __('Enregistrer les modifications') }}
    </x-forms.primary-button>
</form>
