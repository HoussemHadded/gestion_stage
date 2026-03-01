<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\User;

class OffreController extends Controller
{
    // Appliquer middleware pour sécuriser l'accès
    public function __construct()
    {
        $this->middleware('auth');
        // Exemple : seulement admin et entreprise peuvent créer/update/destroy
        $this->middleware('role:admin,entreprise')->except(['index']);
    }

    public function index()
    {
        // Eager load entreprise
        $offres = Offre::with('entreprise')->get();
        return view('offres.index', compact('offres'));
    }

    public function create()
    {
        // Si admin crée l'offre, il choisit l'entreprise
        $entreprises = User::where('role', 'entreprise')->get();
        return view('offres.create', compact('entreprises'));
    }

    public function store(Request $request)
    {
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

        Offre::create($validated);

        return redirect()->route('offres.index')
                         ->with('success', 'Offre created successfully');
    }

    public function edit($id)
    {
        $offre = Offre::findOrFail($id);
        $entreprises = User::where('role', 'entreprise')->get();
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

        return redirect()->route('offres.index')
                         ->with('success', 'Offre updated successfully');
    }

    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);
        $offre->delete();

        return redirect()->route('offres.index')
                         ->with('success', 'Offre deleted successfully');
    }
}