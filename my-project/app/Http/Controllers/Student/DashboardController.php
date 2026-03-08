<?php

// app/Http/Controllers/Student/DashboardController.php
// Phase 4 — Gestion de Stage

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Services\EvaluationService;

/**
 * Tableau de bord de l'étudiant.
 * Affiche ses candidatures et ses évaluations reçues.
 */
class DashboardController extends Controller
{
    public function __construct(
        private EvaluationService $evaluationService
    ) {}

    public function index()
    {
        $studentId = auth()->id();

        // Candidatures de l'étudiant avec l'offre et l'entreprise
        $candidatures = Candidature::with(['offre.entreprise'])
            ->where('student_id', $studentId)
            ->latest('date_candidature')
            ->get();

        // Évaluations reçues par l'étudiant
        $evaluations = $this->evaluationService->getForStudent($studentId);

        return view('student.dashboard', compact('candidatures', 'evaluations'));
    }
}

