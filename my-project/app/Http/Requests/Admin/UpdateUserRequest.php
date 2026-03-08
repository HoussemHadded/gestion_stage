<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * Validation pour la mise à jour d'un utilisateur par l'administrateur.
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:255'],
            'email'           => ['required', 'email', 'max:255', 'unique:users,email,' . $this->route('id')],
            'role'            => ['required', new Enum(UserRole::class)],
            'company_name'    => ['nullable', 'string', 'max:255'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'password'        => ['nullable', 'string', 'min:8'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Le champ nom est obligatoire.',
            'name.max'          => 'Le nom ne doit pas dépasser 255 caractères.',
            'email.required'    => "L'adresse e-mail est obligatoire.",
            'email.email'       => "L'adresse e-mail doit être valide.",
            'email.unique'      => 'Cette adresse e-mail est déjà utilisée par un autre compte.',
            'role.required'     => 'Le rôle est obligatoire.',
            'role.enum'         => 'Le rôle sélectionné est invalide.',
            'password.min'      => 'Le mot de passe doit contenir au moins :min caractères.',
        ];
    }
}
