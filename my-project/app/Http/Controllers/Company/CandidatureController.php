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

        $cacheKey = 'candidatures_list_user_' . $user->id
                  . '_page_' . $page
                  . '_statut_' . ($statut ?? 'all');

        $candidatures = Cache::remember($cacheKey, 300, function () use ($statut, $user) {
            $query = Candidature::with(['student', 'offre.entreprise']);

            if ($user->role === UserRole::Entreprise) {
                $query->whereHas('offre', function ($q) use ($user) {
                    $q->where('entreprise_id', $user->id);
                });
            }

            if ($statut) {
                $query->where('statut', $statut);
            }

            return $query->latest()->paginate(10);
        });

        return view('candidatures.index', compact('candidatures'));
    }

    public function updateStatut(UpdateStatutRequest $request, Candidature $candidature)
    {
        $this->authorize('updateStatut', $candidature);

        // Phase 3 fix: cast string to enum before passing to service
        $statut = StatutCandidature::from($request->validated()['statut']);

        $this->candidatureService->updateStatut($candidature, $statut);

        return back()->with('success', __('candidature.status_updated'));
    }
}
