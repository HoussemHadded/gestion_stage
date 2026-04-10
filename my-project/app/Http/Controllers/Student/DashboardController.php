<?php

// app/Http/Controllers/Student/DashboardController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidature;

use App\Services\DashboardService;

/**
 * Tableau de bord de l'étudiant.
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index()
    {
        $user = auth()->user();
        $stats = $this->dashboardService->getStudentStats($user);

        // Keep recent candidatures for the table
        $candidatures = Candidature::with(['offre.entreprise'])
            ->where('student_id', $user->id)
            ->latest('date_candidature')
            ->take(5)
            ->get();

        return view('student.dashboard', array_merge($stats, compact('candidatures')));
    }
}
