<?php

// app/Http/Requests/Supervisor/StoreEvaluationRequest.php
// Phase 4 — Gestion de Stage

namespace App\Http\Requests\Supervisor;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la création d'une évaluation par un encadrant.
 */
class StoreEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'       => ['required', 'exists:users,id'],
            'offre_id'         => ['required', 'exists:offres,id'],
            'note'             => ['required', 'integer', 'min:0', 'max:20'],
            'commentaire'      => ['required', 'string', 'min:20', 'max:2000'],
            'date_evaluation'  => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            // student_id
            'student_id.required' => "L'étudiant est obligatoire.",
            'student_id.exists'   => "L'étudiant sélectionné est invalide.",

            // offre_id
            'offre_id.required'   => "L'offre de stage est obligatoire.",
            'offre_id.exists'     => "L'offre de stage sélectionnée est invalide.",

            // note
            'note.required'       => 'La note est obligatoire.',
            'note.integer'        => 'La note doit être un nombre entier.',
            'note.min'            => 'La note ne peut pas être inférieure à :min.',
            'note.max'            => 'La note ne peut pas être supérieure à :max.',

            // commentaire
            'commentaire.required' => 'Le commentaire est obligatoire.',
            'commentaire.string'   => 'Le commentaire doit être une chaîne de caractères.',
            'commentaire.min'      => 'Le commentaire doit comporter au moins :min caractères.',
            'commentaire.max'      => 'Le commentaire ne doit pas dépasser :max caractères.',

            // date_evaluation
            'date_evaluation.required'        => "La date d'évaluation est obligatoire.",
            'date_evaluation.date'            => "La date d'évaluation n'est pas une date valide.",
            'date_evaluation.before_or_equal' => "La date d'évaluation ne peut pas être dans le futur.",
        ];
    }
}
