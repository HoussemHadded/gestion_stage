<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Services\CVService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CVController extends Controller
{
    private CVService $cvService;

    public function __construct(CVService $cvService)
    {
        $this->cvService = $cvService;
    }

    /**
     * Display the CV management page.
     */
    public function show()
    {
        $student = Auth::user();
        $skills = $student->skills()->get(); // Load extracted skills via pivot
        return view('student.ai.cv', compact('student', 'skills'));
    }

    /**
     * Save raw text and parse it into structured skills.
     */
    public function parseCV(Request $request)
    {
        $request->validate([
            'cv_text' => 'required|string|max:10000',
        ]);

        $student = Auth::user();
        
        // Save the raw text to DB
        $student->update(['cv_text' => $request->cv_text]);

        try {
            // Service handles AI NLP extraction and DB syncing
            $this->cvService->parseCV($student);
            
            // Recalculate intelligent CV score immediately
            app(\App\Services\CVScoringService::class)->score($student);
            
            return redirect()->route('student.cv.show')->with('success', 'CV analysé avec succès ! Vos compétences ont été extraites et votre profil réévalué.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'analyse du CV : ' . $e->getMessage());
        }
    }

    /**
     * Prepare a targeted CV for a specific offer.
     */
    public function optimizeCV(Offre $offre)
    {
        $student = Auth::user();

        if (empty($student->cv_text)) {
            return redirect()->route('student.cv.show')
                ->with('error', 'Veuillez d\'abord renseigner votre CV textuel avant de l\'optimiser.');
        }

        try {
            // Service handles prompt generation and response parsing
            $optimization = $this->cvService->optimizeCV($student, $offre);

            return view('student.ai.cv-optimize', compact('student', 'offre', 'optimization'));
        } catch (\Exception $e) {
            return redirect()->route('student.match.index')->with('error', 'Erreur lors de l\'optimisation : ' . $e->getMessage());
        }
    }

    /**
     * Download the optimized CV as a PDF.
     */
    public function downloadPDF(Offre $offre)
    {
        $student = Auth::user();
        
        if (empty($student->cv_text)) {
            return redirect()->route('student.cv.show')
                ->with('error', 'Impossible de générer le PDF : Aucun CV trouvé.');
        }

        try {
            // Get skills
            $skills = $student->skills()->get();
            
            // Re-run optimization (ideally this could be cached to save API calls, but we run it for freshness)
            $optimization = $this->cvService->optimizeCV($student, $offre);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.optimized_cv', compact('student', 'offre', 'skills', 'optimization'));
            
            return $pdf->download('CV_Optimise_' . str_replace(' ', '_', $student->name) . '.pdf');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la génération du PDF : ' . $e->getMessage());
        }
    }
}
