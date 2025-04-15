<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /**
         * Détermine les règles de validation pour la mise à jour du profil utilisateur.
         *
         * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
         */

        return [
            // Validation pour le prénom et nom
            'first_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],

            // Validation de l'email
            'email' => [
                'nullable',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // Validation pour le mot de passe
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],

            // Validation pour l'avatar
            'avatar' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],


        ];
    }
}
