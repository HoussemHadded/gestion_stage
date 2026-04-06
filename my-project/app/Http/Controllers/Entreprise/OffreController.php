<?php

namespace App\Http\Controllers\Entreprise;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOffreRequest;
use App\Http\Requests\Admin\UpdateOffreRequest;
use App\Models\Offre;
use App\Services\OffreService;
use Illuminate\Support\Facades\Cache;

/**
 * OffreController dédié aux entreprises.
 * Phase 3 : remplace la réutilisation de Admin\OffreController pour les routes entreprise.
 * Filtre les offres par entreprise authentifiée et enforce l'autorisation via la policy.
 */
class OffreController extends Controller
{
    public function __construct(
        private readonly OffreService $offreService
    ) {}

    /** Liste uniquement les offres de l'entreprise connectée. */
    public function index()
    {
        $this->authorize('viewAny', Offre::class);

        $user = auth()->user();
        $page = request()->get('page', 1);

        $offres = Cache::remember(
            "entreprise_offres_{$user->id}_page_{$page}",
            300,
            fn () => Offre::with('entreprise')
                ->where('entreprise_id', $user->id)
                ->latest()
                ->paginate(10)
        );

        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        $this->authorize('create', Offre::class);

        return view('offres.create', ['entreprises' => collect([auth()->user()])]);
    }

    public function store(StoreOffreRequest $request)
    {
        $this->authorize('create', Offre::class);

        $data                  = $request->validated();
        $data['entreprise_id'] = auth()->id(); // Force l'identité de l'entreprise connectée

        $this->offreService->store($data);

        return redirect()->route('entreprise.offres.index')
                         ->with('success', __('offre.created'));
    }

    public function edit($id)
    {
        $offre = Offre::findOrFail($id);
        $this->authorize('update', $offre);

        return view('offres.edit', [
            'offre'      => $offre,
            'entreprises' => collect([auth()->user()]),
        ]);
    }

    public function update(UpdateOffreRequest $request, $id)
    {
        $offre = Offre::findOrFail($id);
        $this->authorize('update', $offre);

        $this->offreService->update($offre, $request->validated());

        return redirect()->route('entreprise.offres.index')
                         ->with('success', __('offre.updated'));
    }

    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);
        $this->authorize('delete', $offre);

        $this->offreService->delete($offre);

        return redirect()->route('entreprise.offres.index')
                         ->with('success', __('offre.deleted'));
    }
}
