<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\User;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;

class OffreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,entreprise,student')->only(['index']);
        $this->middleware('role:admin,entreprise')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $page = request()->get('page', 1);
        $cacheKey = 'offres_list_page_' . $page;

        $offres = Cache::remember($cacheKey, 300, function () {
            return Offre::with('entreprise')->paginate(10);
        });

        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        $entreprises = collect();
        if (auth()->user()->role === 'admin') {
            $entreprises = Cache::remember('entreprises_list', 300, function () {
                return User::where('role', 'entreprise')->get();
            });
        }

        return view('offres.create', compact('entreprises'));
    }

    public function store(Request $request)
    {
        $rules = [
            'titre'            => 'required|string|max:255',
            'description'      => 'required|string',
            'date_publication' => 'required|date',
        ];

        // Seul l'admin doit choisir l'entreprise
        if (auth()->user()->role === 'admin') {
            $rules['entreprise_id'] = 'required|exists:users,id';
        }

        $validated = $request->validate($rules);

        // Auto-assigner entreprise_id pour le rôle entreprise
        if (auth()->user()->role === 'entreprise') {
            $validated['entreprise_id'] = auth()->id();
        }

        Offre::create($validated);

        CacheService::forgetOffres();

        return redirect()->route('offres.index')
                         ->with('success', 'Offre créée avec succès.');
    }

    public function edit($id)
    {
        $offre = Offre::findOrFail($id);
        $entreprises = Cache::remember('entreprises_list', 300, function () {
            return User::where('role', 'entreprise')->get();
        });
        return view('offres.edit', compact('offre', 'entreprises'));
    }

    public function update(Request $request, $id)
    {
        $offre = Offre::findOrFail($id);

        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'date_publication' => 'required|date',
            'entreprise_id' => 'required|exists:users,id'
        ]);

        // Vérifier que entreprise_id est bien une entreprise
        $entreprise = User::where('id', $validated['entreprise_id'])
                           ->where('role', 'entreprise')
                           ->firstOrFail();

        $offre->update($validated);

        CacheService::forgetOffres();

        return redirect()->route('offres.index')
                         ->with('success', 'Offre updated successfully');
    }

    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);
        $offre->delete();

        CacheService::forgetOffres();
        CacheService::forgetCandidatures();

        return redirect()->route('offres.index')
                         ->with('success', 'Offre deleted successfully');
    }
}