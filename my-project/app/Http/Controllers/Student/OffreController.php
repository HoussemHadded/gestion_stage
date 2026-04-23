<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OffreController extends Controller
{
    /**
     * Affiche la liste des offres avec recherche avancée.
     */
    public function index(Request $request)
    {
        $query = Offre::with('entreprise')->latest();

        // Advanced Search Filters
        if ($q = $request->query('q')) {
            $query->where(function($sq) use ($q) {
                $sq->where('titre', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($lieu = $request->query('lieu')) {
            $query->where('lieu', 'like', "%{$lieu}%");
        }

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $hasFilters = $request->hasAny(['q', 'lieu', 'type']);
        $page = $request->get('page', 1);

        if ($hasFilters) {
            $offres = $query->paginate(10)->withQueryString();
        } else {
            $offres = Cache::remember('student_offres_page_' . $page, 300, function () use ($query) {
                return $query->paginate(10);
            });
        }

        // For the UI to show saved state
        $savedOffresIds = auth()->user()->savedOffres()->pluck('offre_id')->toArray();

        return view('student.offres', compact('offres', 'savedOffresIds'));
    }

    /**
     * Affiche le détail d'une offre & Match Score
     */
    public function show(int $id)
    {
        $offre = Offre::with('entreprise')->findOrFail($id);

        // Fetch AI Match score for this student and this offer if available
        $matchScore = auth()->user()->matches()->where('offre_id', $offre->id)->first();
        
        $isSaved = auth()->user()->savedOffres()->where('offre_id', $id)->exists();

        return view('student.offre_show', compact('offre', 'matchScore', 'isSaved'));
    }

    /**
     * Toggle "Favoris" status for an internship offer via AJAX.
     */
    public function toggleSave(int $id)
    {
        $user = auth()->user();
        if ($user->savedOffres()->where('offre_id', $id)->exists()) {
            $user->savedOffres()->detach($id);
            return response()->json(['status' => 'unsaved', 'message' => 'Offre retirée des favoris.']);
        } else {
            $user->savedOffres()->attach($id);
            return response()->json(['status' => 'saved', 'message' => 'Offre ajoutée aux favoris!']);
        }
    }
}
