<?php

// app/Http/Controllers/Student/OffreController.php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use Illuminate\Support\Facades\Cache;

/**
 * OffreController dédié aux étudiants.
 * Affiche toutes les offres de stage disponibles.
 * Pas de policy admin — tout étudiant authentifié peut consulter les offres.
 */
class OffreController extends Controller
{
    /**
     * Affiche la liste de toutes les offres de stage.
     */
    public function index()
    {
        $page = request()->get('page', 1);

        $offres = Cache::remember('student_offres_page_' . $page, 300, function () {
            return Offre::with('entreprise')
                ->latest()
                ->paginate(10);
        });

        return view('student.offres', compact('offres'));
    }

    /**
     * Affiche le détail d'une offre.
     */
    public function show(int $id)
    {
        $offre = Offre::with('entreprise')->findOrFail($id);

        return view('student.offre_show', compact('offre'));
    }
}
