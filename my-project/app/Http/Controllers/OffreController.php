<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
    /**
     * Affiche le détail d'une offre.
     * Accessible par tous les utilisateurs authentifiés.
     */
    public function show(Offre $offre)
    {
        // Initialisation des variables spécifiques aux étudiants (null par défaut)
        $matchScore = null;
        $isSaved = false;

        // Si l'utilisateur est un étudiant, on récupère ses données spécifiques
        if (auth()->check() && auth()->user()->isEtudiant()) {
            $matchScore = auth()->user()->matches()->where('offre_id', $offre->id)->first();
            $isSaved = auth()->user()->savedOffres()->where('offre_id', $offre->id)->exists();
        }

        return view('offres.show', compact('offre', 'matchScore', 'isSaved'));
    }
}
