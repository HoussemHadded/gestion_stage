<?php

namespace App\Http\Controllers\Company;

use App\Enums\StatutCandidature;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Services\CandidatureService;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    public function __construct(
        private readonly CandidatureService $candidatureService
    ) {}

    public function index(Request $request)
    {
        $this->authorize('viewAny', Candidature::class);

        $user   = auth()->user();
        $statut = $request->get('statut');
        $sort   = $request->get('sort', 'match');

        $query = Candidature::select('candidatures.*')
            ->with(['student', 'offre.entreprise']);

        if ($user->role === UserRole::Entreprise) {
            $query->whereHas('offre', function ($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            });
        }

        if ($statut) {
            $query->where('statut', $statut);
        }

        if ($sort === 'date') {
            $query->orderBy('candidatures.created_at', 'desc');
        } elseif ($sort === 'level') {
            $query->join('users', 'candidatures.student_id', '=', 'users.id')
                  ->orderBy('users.cv_score', 'desc');
        } else {
            // Default sort by Match Score Descending
            $query->orderBy('candidatures.match_percentage', 'desc');
        }

        $candidatures = $query->paginate(10)->withQueryString();

        return view('candidatures.index', compact('candidatures', 'sort'));
    }

    public function accept($id)
    {
        $candidature = Candidature::with('offre')->findOrFail($id);
        if ($candidature->offre->entreprise_id !== auth()->id()) {
            abort(403, "Non autorisé.");
        }
        $this->candidatureService->updateStatut($candidature, StatutCandidature::Acceptee);
        return back()->with('success', 'Candidature acceptée !');
    }

    public function reject($id)
    {
        $candidature = Candidature::with('offre')->findOrFail($id);
        if ($candidature->offre->entreprise_id !== auth()->id()) {
            abort(403, "Non autorisé.");
        }
        $this->candidatureService->updateStatut($candidature, StatutCandidature::Refusee);
        return back()->with('success', 'Candidature refusée !');
    }

    public function kanban()
    {
        $this->authorize('viewAny', Candidature::class);
        $user = auth()->user();
        
        $candidatures = Candidature::with(['student', 'offre'])
            ->whereHas('offre', function ($q) use ($user) {
                $q->where('entreprise_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $pipeline = [
            'en_attente' => $candidatures->where('statut', StatutCandidature::EnAttente)->values(),
            'shortlisted' => $candidatures->where('statut', StatutCandidature::Shortlisted)->values(),
            'interview' => $candidatures->where('statut', StatutCandidature::Interview)->values(),
            'accepte' => $candidatures->where('statut', StatutCandidature::Acceptee)->values(),
            'refuse' => $candidatures->where('statut', StatutCandidature::Refusee)->values(),
        ];

        return view('candidatures.kanban', compact('pipeline'));
    }

    public function updateKanbanStatus(Request $request, $id)
    {
        $candidature = Candidature::with('offre')->findOrFail($id);
        if ($candidature->offre->entreprise_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $newStatus = StatutCandidature::tryFrom($request->input('status'));
        if (!$newStatus) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $this->candidatureService->updateStatut($candidature, $newStatus);
        return response()->json(['success' => true]);
    }
}
