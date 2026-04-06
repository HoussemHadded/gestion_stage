<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOffreRequest;
use App\Http\Requests\Admin\UpdateOffreRequest;
use App\Models\Offre;
use App\Models\User;
use App\Enums\UserRole;
use App\Services\OffreService;
use Illuminate\Support\Facades\Cache;

class OffreController extends Controller
{
    public function __construct(
        private readonly OffreService $offreService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Offre::class);

        $page = request()->get('page', 1);
        $offres = Cache::remember('offres_list_page_' . $page, 300, function () {
            return Offre::with('entreprise')->paginate(10);
        });

        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        $this->authorize('create', Offre::class);
        
        $entreprises = Cache::remember('entreprises_list', 3600, function () {
            return User::where('role', UserRole::Entreprise)->get();
        });

        return view('offres.create', compact('entreprises'));
    }

    public function store(StoreOffreRequest $request)
    {
        $this->authorize('create', Offre::class);

        $this->offreService->store($request->validated());

        return redirect()->route('admin.offres.index')
                         ->with('success', __('offre.created'));
    }

    public function edit($id)
    {
        $offre = Offre::findOrFail($id);
        $this->authorize('update', $offre);

        $entreprises = User::where('role', UserRole::Entreprise)->get();

        return view('offres.edit', compact('offre', 'entreprises'));
    }

    public function update(UpdateOffreRequest $request, $id)
    {
        $offre = Offre::findOrFail($id);
        $this->authorize('update', $offre);

        $this->offreService->update($offre, $request->validated());

        return redirect()->route('admin.offres.index')
                         ->with('success', __('offre.updated'));
    }

    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);
        $this->authorize('delete', $offre);

        $this->offreService->delete($offre);

        return redirect()->route('admin.offres.index')
                         ->with('success', __('offre.deleted'));
    }
}
