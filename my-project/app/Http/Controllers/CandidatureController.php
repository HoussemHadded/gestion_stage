<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Offre;

class CandidatureController extends Controller
{
    public function index() {
        $candidatures = Candidature::with(['student', 'offre'])->get();
        return view('candidatures.index', compact('candidatures'));
    }

    public function create() {
        $students = User::where('role', 'student')->get();
        $offres = Offre::all();
        return view('candidatures.create', compact('students', 'offres'));
    }

    public function store(Request $request) {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'offre_id' => 'required|exists:offres,id',
            'cv' => 'required',
            'statut' => 'required|in:en_attente,accepte,refuse',
            'date_candidature' => 'required|date'
        ]);

        Candidature::create($request->all());
        return redirect()->route('candidatures.index')->with('success', 'Candidature created successfully');
    }

    public function edit($id) {
        $candidature = Candidature::findOrFail($id);
        $students = User::where('role', 'student')->get();
        $offres = Offre::all();
        return view('candidatures.edit', compact('candidature', 'students', 'offres'));
    }

    public function update(Request $request, $id) {
        $candidature = Candidature::findOrFail($id);

        $request->validate([
            'student_id' => 'required|exists:users,id',
            'offre_id' => 'required|exists:offres,id',
            'cv' => 'required',
            'statut' => 'required|in:en_attente,accepte,refuse',
            'date_candidature' => 'required|date'
        ]);

        $candidature->update($request->all());
        return redirect()->route('candidatures.index')->with('success', 'Candidature updated successfully');
    }

    public function destroy($id) {
        $candidature = Candidature::findOrFail($id);
        $candidature->delete();
        return redirect()->route('candidatures.index')->with('success', 'Candidature deleted successfully');
    }
}