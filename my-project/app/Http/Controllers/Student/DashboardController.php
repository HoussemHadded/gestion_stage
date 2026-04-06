<?php

// app/Http/Controllers/Student/DashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidature;

/**
 * Tableau de bord de l'étudiant.
 * Affiche ses candidatures (sans évaluations — rôle encadrant supprimé).
 */
class DashboardController extends Controller
{
    public function index()
    {
        $studentId = auth()->id();

        // Candidatures de l'étudiant avec l'offre et l'entreprise
        $candidatures = Candidature::with(['offre.entreprise'])
            ->where('student_id', $studentId)
            ->latest('date_candidature')
            ->get();

        return view('student.dashboard', compact('candidatures'));
    }
}

