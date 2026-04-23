<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatController extends Controller
{
    /**
     * Display a grid of candidates applying to the company's offers,
     * enriched with AI scores and filters.
     */
    public function index(Request $request)
    {
        $entreprise = Auth::user();

        // Base query: fetch candidatures restricted to this company's offers
        $query = Candidature::whereHas('offre', function ($q) use ($entreprise) {
            $q->where('entreprise_id', $entreprise->id);
        })->with(['student', 'offre']);

        // Handle Filters
        if ($request->filled('offre_id')) {
            $query->where('offre_id', $request->offre_id);
        }

        // Handle Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'cv_score') {
            // Join students to sort securely by their cv_score
            $query->join('users', 'candidatures.student_id', '=', 'users.id')
                  ->orderBy('users.cv_score', 'desc')
                  ->select('candidatures.*');
        } elseif ($sort === 'match') {
            $query->orderBy('match_percentage', 'desc');
        } else {
            $query->latest();
        }

        $candidates = $query->paginate(12)->withQueryString();
        $offres = $entreprise->offres()->select('id', 'titre')->get();

        return view('entreprise.candidats.index', compact('candidates', 'offres', 'sort'));
    }
}
