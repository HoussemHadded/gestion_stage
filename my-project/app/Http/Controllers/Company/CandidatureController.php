<?php

namespace App\Http\Controllers\Company;

use App\Enums\StatutCandidature;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Entreprise\UpdateStatutRequest;
use App\Models\Candidature;
use App\Services\CandidatureService;
use Illuminate\Support\Facades\Cache;

/**
 * CandidatureController pour les entreprises.
 * Phase 3 : updateStatut() transmet désormais un enum StatutCandidature typé au service.
 */
class CandidatureController extends Controller
{
    public function __construct(
        private readonly CandidatureService $candidatureService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Candidature::class);

        $user   = auth()->user();
        $page   = request()->get('page', 1);
        $statut = request('statut');
        $sort   = request('sort', 'score'); // default sorting by Match Score

        $cacheKey = 'candidatures_list_user_' . $user->id
                  . '_page_' . $page
                  . '_statut_' . ($statut ?? 'all')
                  . '_sort_' . $sort;

        // Note: For real-time updates and search, bypassing cache during dev/phase 2 is sometimes better,
        // but we keep it here and use withQueryString().
        $candidatures = Cache::remember($cacheKey, 60, function () use ($statut, $sort, $user) {
            
            // Start query and select strictly from base table to avoid collision
            $query = Candidature::select('candidatures.*', 'offre_matches.score as match_score', 'users.cv_score as exp_level')
                ->with(['student', 'offre.entreprise']);

            // Limit to company's offers
            if ($user->role === UserRole::Entreprise) {
                $query->whereHas('offre', function ($q) use ($user) {
                    $q->where('entreprise_id', $user->id);
                });
            }

            if ($statut) {
                $query->where('candidatures.statut', $statut);
            }

            // JOIN specific tables for sorting attributes
            // 1. Join for AI Match score evaluating CV vs Offer
            $query->leftJoin('matches', function($join) {
                $join->on('candidatures.student_id', '=', 'matches.student_id')
                     ->on('candidatures.offre_id', '=', 'matches.offre_id');
            });
            // 2. Join users for raw experience cv_score
            $query->leftJoin('users', 'candidatures.student_id', '=', 'users.id');

            // Apply Hybrid Sorting Logic Request
            if ($sort === 'date') {
                $query->orderBy('candidatures.created_at', 'desc');
            } elseif ($sort === 'level') {
                $query->orderByRaw('COALESCE(users.cv_score, 0) DESC');
            } else {
                // Default fallback is 'score' (Match Score Descending)
                $query->orderByRaw('COALESCE(matches.score, 0) DESC');
            }

            return $query->paginate(10)->withQueryString();
        });

        return view('candidatures.index', compact('candidatures', 'sort'));
    }

    public function accept($id)
    {
        $candidature = Candidature::with('offre')->findOrFail($id);

        if ($candidature->offre->entreprise_id !== auth()->id()) {
            abort(403, "Vous ne pouvez pas accepter une candidature qui ne vous appartient pas.");
        }

        $this->candidatureService->updateStatut($candidature, StatutCandidature::Acceptee);

        return back()->with('success', 'Candidature acceptée !');
    }

    public function reject($id)
    {
        $candidature = Candidature::with('offre')->findOrFail($id);

        if ($candidature->offre->entreprise_id !== auth()->id()) {
            abort(403, "Vous ne pouvez pas refuser une candidature qui ne vous appartient pas.");
        }

        $this->candidatureService->updateStatut($candidature, StatutCandidature::Refusee);

        return back()->with('success', 'Candidature refusée !');
    }

    /**
     * Render the Kanban Pipeline view.
     */
    public function kanban()
    {
        $this->authorize('viewAny', Candidature::class);
        $user = auth()->user();
        
        $candidatures = Candidature::with(['student', 'offre'])
            ->whereHas('offre', function ($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            })
            // Left join with matches to pull Match Score
            ->leftJoin('matches', function($join) {
                $join->on('candidatures.student_id', '=', 'matches.student_id')
                     ->on('candidatures.offre_id', '=', 'matches.offre_id');
            })
            ->select('candidatures.*', 'matches.score as match_score')
            ->orderBy('candidatures.created_at', 'desc')
            ->get();

        // Group by status
        $pipeline = [
            'en_attente' => $candidatures->where('statut', StatutCandidature::EnAttente)->values(),
            'shortlisted' => $candidatures->where('statut', StatutCandidature::Shortlisted)->values(),
            'interview' => $candidatures->where('statut', StatutCandidature::Interview)->values(),
            'accepte' => $candidatures->where('statut', StatutCandidature::Acceptee)->values(),
            'refuse' => $candidatures->where('statut', StatutCandidature::Refusee)->values(),
        ];

        return view('candidatures.kanban', compact('pipeline'));
    }

    /**
     * Handle drag & drop updates via AJAX
     */
    public function updateKanbanStatus(\Illuminate\Http\Request $request, $id)
    {
        $candidature = Candidature::with('offre')->findOrFail($id);

        if ($candidature->offre->entreprise_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $newStatusString = $request->input('status');
        $newStatus = StatutCandidature::tryFrom($newStatusString);

        if (!$newStatus) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $this->candidatureService->updateStatut($candidature, $newStatus);

        return response()->json(['success' => true]);
    }
}
