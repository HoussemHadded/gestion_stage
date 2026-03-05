<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Offre;

class CandidatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,entreprise')->only(['index', 'edit', 'update', 'destroy']);
        $this->middleware('role:student')->only(['create', 'store']);
    }
    public function index()
    {
        $candidatures = Candidature::with(['student', 'offre.entreprise'])->paginate(10);
        return view('candidatures.index', compact('candidatures'));
    }

    public function create()
    {
        $students = User::where('role', 'student')->get();
        $offres = Offre::all();

        return view('candidatures.create', compact('students', 'offres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'offre_id' => 'required|exists:offres,id',
            'cv' => 'required|string',
            'statut' => 'required|in:en_attente,accepte,refuse',
            'date_candidature' => 'required|date'
        ]);

        // Extra sécurité : vérifier rôle student
        $student = User::where('id', $validated['student_id'])
                       ->where('role', 'student')
                       ->firstOrFail();

        Candidature::create($validated);

        return redirect()->route('offres.index')
                         ->with('success', 'Candidature soumise avec succès.');
    }

    public function edit($id)
    {
        $candidature = Candidature::findOrFail($id);
        $students = User::where('role', 'student')->get();
        $offres = Offre::all();

        return view('candidatures.edit', compact('candidature', 'students', 'offres'));
    }

    public function update(Request $request, $id)
    {
        $candidature = Candidature::findOrFail($id);

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'offre_id' => 'required|exists:offres,id',
            'cv' => 'required|string',
            'statut' => 'required|in:en_attente,accepte,refuse',
            'date_candidature' => 'required|date'
        ]);

        $candidature->update($validated);

        return redirect()->route('candidatures.index')
                         ->with('success', 'Candidature updated successfully');
    }

    public function destroy($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->delete();

        return redirect()->route('candidatures.index')
                         ->with('success', 'Candidature deleted successfully');
    }
}