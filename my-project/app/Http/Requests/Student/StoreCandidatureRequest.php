<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la soumission d'une candidature par un étudiant.
 */
class StoreCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offre_id' => ['required', 'exists:offres,id'],
            'cv'       => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'offre_id.required' => "L'offre de stage est obligatoire.",
            'offre_id.exists'   => "L'offre de stage sélectionnée est invalide.",
            'cv.required'       => 'Le CV / la lettre de motivation est obligatoire.',
            'cv.max'            => 'Le CV / la lettre de motivation ne doit pas dépasser 5000 caractères.',
        ];
    }
}
