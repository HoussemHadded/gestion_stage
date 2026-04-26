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

        // Optimized query: Eager load student skills to avoid N+1
        $query = Candidature::whereHas('offre', function ($q) use ($entreprise) {
            $q->where('entreprise_id', $entreprise->id);
        })->with(['student', 'student.skills', 'offre'])
          ->select('candidatures.*'); // Ensure we select only candidature columns primarily

        // Handle Filters
        if ($request->filled('offre_id')) {
            $query->where('offre_id', $request->offre_id);
        }

        if ($request->filled('min_match')) {
            $query->where('match_percentage', '>=', $request->min_match);
        }

        if ($request->filled('min_score')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('cv_score', '>=', $request->min_score);
            });
        }

        if ($request->filled('skill')) {
            $query->whereHas('student.skills', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->skill . '%');
            });
        }

        // Handle Sorting
        $sort = $request->get('sort', 'latest');
        $direction = $request->get('dir', 'desc') === 'asc' ? 'asc' : 'desc';

        if ($sort === 'cv_score') {
            $query->join('users', 'candidatures.student_id', '=', 'users.id')
                  ->orderBy('users.cv_score', $direction);
        } elseif ($sort === 'match') {
            $query->orderBy('match_percentage', $direction);
        } else {
            $query->orderBy('candidatures.created_at', $direction);
        }

        $candidates = $query->paginate(12)->withQueryString();
        $offres = $entreprise->offres()->select('id', 'titre')->get();

        return view('entreprise.candidats.index', compact('candidates', 'offres', 'sort', 'direction'));
    }
}
