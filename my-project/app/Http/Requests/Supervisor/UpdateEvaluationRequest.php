<?php

// app/Http/Requests/Supervisor/UpdateEvaluationRequest.php
// Phase 4 — Gestion de Stage

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la mise à jour partielle d'une évaluation.
 * Toutes les règles utilisent "sometimes" pour permettre les mises à jour partielles (PATCH).
 */
class UpdateEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note'            => ['sometimes', 'integer', 'min:0', 'max:20'],
            'commentaire'     => ['sometimes', 'string', 'min:20', 'max:2000'],
            'date_evaluation' => ['sometimes', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            // note
            'note.integer' => 'La note doit être un nombre entier.',
            'note.min'     => 'La note ne peut pas être inférieure à :min.',
            'note.max'     => 'La note ne peut pas être supérieure à :max.',

            // commentaire
            'commentaire.string' => 'Le commentaire doit être une chaîne de caractères.',
            'commentaire.min'    => 'Le commentaire doit comporter au moins :min caractères.',
            'commentaire.max'    => 'Le commentaire ne doit pas dépasser :max caractères.',

            // date_evaluation
            'date_evaluation.date'            => "La date d'évaluation n'est pas une date valide.",
            'date_evaluation.before_or_equal' => "La date d'évaluation ne peut pas être dans le futur.",
        ];
    }
}
