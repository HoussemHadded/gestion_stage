<?php

namespace App\Http\Requests\Entreprise;

use App\Enums\StatutCandidature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * Validation pour la mise à jour du statut d'une candidature par l'entreprise.
 */
class UpdateStatutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', new Enum(StatutCandidature::class)],
        ];
    }

    public function messages(): array
    {
        return [
            'statut.required' => 'Le statut de la candidature est obligatoire.',
            'statut.enum'     => 'Le statut sélectionné est invalide.',
        ];
    }
}
