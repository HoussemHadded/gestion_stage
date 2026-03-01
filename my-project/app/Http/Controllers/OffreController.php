<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offre;
use App\Models\User;

class OffreController extends Controller
{
    public function index() {
        $offres = Offre::with('entreprise')->get();
        return view('offres.index', compact('offres'));
    }

    public function create() {
        $entreprises = User::where('role', 'entreprise')->get();
        return view('offres.create', compact('entreprises'));
    }

    public function store(Request $request) {
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'date_publication' => 'required|date',
            'entreprise_id' => 'required|exists:users,id'
        ]);

        Offre::create($request->all());
        return redirect()->route('offres.index')->with('success', 'Offre created successfully');
    }

    public function edit($id) {
        $offre = Offre::findOrFail($id);
        $entreprises = User::where('role', 'entreprise')->get();
        return view('offres.edit', compact('offre', 'entreprises'));
    }

    public function update(Request $request, $id) {
        $offre = Offre::findOrFail($id);

        $request->validate([
            'titre' => 'required',
            'description' => 'required',
            'date_publication' => 'required|date',
            'entreprise_id' => 'required|exists:users,id'
        ]);

        $offre->update($request->all());
        return redirect()->route('offres.index')->with('success', 'Offre updated successfully');
    }

    public function destroy($id) {
        $offre = Offre::findOrFail($id);
        $offre->delete();
        return redirect()->route('offres.index')->with('success', 'Offre deleted successfully');
    }
}