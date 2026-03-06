<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidature;
use App\Models\User;
use App\Models\Offre;
use App\Notifications\NouvelleCandidatureNotification;
use App\Notifications\CandidatureAcceptéeNotification;
use App\Notifications\CandidatureRefuséeNotification;

class CandidatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,entreprise')->only(['index', 'edit', 'update', 'destroy', 'updateStatut']);
        $this->middleware('role:student')->only(['create', 'store']);
    }

    public function index()
    {
        $query = Candidature::with(['student', 'offre.entreprise']);

        if (request('statut')) {
            $query->where('statut', request('statut'));
        }

        $candidatures = $query->latest()->paginate(10);

        return view('candidatures.index', compact('candidatures'));
    }

    public function create()
    {
        $offres = Offre::all();

        return view('candidatures.create', compact('offres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'cv'       => 'required|string',
        ]);

        $candidature = Candidature::create([
            'student_id'       => auth()->id(),
            'offre_id'         => $validated['offre_id'],
            'cv'               => $validated['cv'],
            'statut'           => 'en_attente',
            'date_candidature' => now(),
        ]);

        $candidature->load('offre.entreprise');
        $candidature->offre->entreprise->notify(new NouvelleCandidatureNotification($candidature));

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
            'student_id'       => 'required|exists:users,id',
            'offre_id'         => 'required|exists:offres,id',
            'cv'               => 'required|string',
            'statut'           => 'required|in:en_attente,accepte,refuse',
            'date_candidature' => 'required|date',
        ]);

        $candidature->update($validated);

        return redirect()->route('candidatures.index')
                         ->with('success', 'Candidature mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $candidature = Candidature::findOrFail($id);
        $candidature->delete();

        return redirect()->route('candidatures.index')
                         ->with('success', 'Candidature supprimée avec succès.');
    }

    /**
     * Update candidature status (accepte or refuse) and notify the student.
     */
    public function updateStatut(Request $request, Candidature $candidature)
    {
        $validated = $request->validate([
            'statut' => 'required|in:accepte,refuse',
        ]);

        $candidature->update(['statut' => $validated['statut']]);

        $candidature->load('student');
        $student = $candidature->student;

        if ($validated['statut'] === 'accepte') {
            $student->notify(new CandidatureAcceptéeNotification($candidature));
        } else {
            $student->notify(new CandidatureRefuséeNotification($candidature));
        }

        return redirect()->route('candidatures.index')
                         ->with('success', 'Statut de la candidature mis à jour.');
    }
}