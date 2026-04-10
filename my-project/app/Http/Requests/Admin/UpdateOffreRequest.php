<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la mise à jour d'une offre de stage.
 */
class UpdateOffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'titre'            => ['required', 'string', 'max:255'],
            'description'      => ['required', 'string'],
            'lieu'             => ['nullable', 'string', 'max:255'],
            'date_publication' => ['required', 'date'],
        ];

        if (auth()->user() && auth()->user()->isAdmin()) {
            $rules['entreprise_id'] = ['required', 'exists:users,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'titre.required'            => 'Le titre de l\'offre est obligatoire.',
            'titre.max'                 => 'Le titre ne doit pas dépasser 255 caractères.',
            'description.required'      => 'La description de l\'offre est obligatoire.',
            'lieu.max'                  => 'Le lieu ne doit pas dépasser 255 caractères.',
            'date_publication.required' => 'La date de publication est obligatoire.',
            'date_publication.date'     => 'La date de publication doit être une date valide.',
            'entreprise_id.required'    => "L'entreprise est obligatoire.",
            'entreprise_id.exists'      => "L'entreprise sélectionnée est invalide.",
        ];
    }
}
