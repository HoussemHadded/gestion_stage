<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\OffreMatch;
use App\Services\MatchingService;
use Illuminate\Support\Facades\Auth;

class MatchController extends Controller
{
    private MatchingService $matchingService;

    public function __construct(MatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    /**
     * Display a list of all offers with their match scores.
     */
    public function index()
    {
        $student = Auth::user();
        
        // Eager load matches for the current student to display scores efficiently
        $offres = Offre::with(['matches' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])->latest('date_publication')->paginate(12);

        return view('student.ai.match-index', compact('offres'));
    }

    /**
     * Calculate and display the match result for a specific offer.
     */
    public function calculate(Offre $offre)
    {
        $student = Auth::user();
        
        // Use service to calculate and store the score
        $result = $this->matchingService->calculate($student, $offre);

        if (!$result) {
            return back()->with('error', 'Veuillez remplir votre profil ou ajouter des compétences pour calculer le match.');
        }

        // Fetch the created/updated match record with its casts applied
        $match = OffreMatch::find($result['match_id']);

        return view('student.ai.match-result', compact('offre', 'match'));
    }
}
