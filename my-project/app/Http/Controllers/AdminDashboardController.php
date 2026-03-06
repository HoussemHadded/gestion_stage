<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Affiche le dashboard admin avec statistiques et graphiques.
     */
    public function index()
    {
        // Statistiques clés
        $nbEtudiants = User::where('role', 'student')->count();
        $nbEntreprises = User::where('role', 'entreprise')->count();
        $nbOffresActives = Offre::count();

        // Candidatures par statut
        $candidaturesByStatut = Candidature::selectRaw('statut, count(*) as total')
            ->groupBy('statut')
            ->pluck('total', 'statut')
            ->toArray();

        // Assurer que tous les statuts apparaissent même si 0
        $statuts = ['en_attente', 'accepte', 'refuse'];
        $labels = [
            'en_attente' => 'En attente',
            'accepte'    => 'Acceptées',
            'refuse'     => 'Refusées',
        ];

        $chartLabels = [];
        $chartData = [];
        foreach ($statuts as $statut) {
            $chartLabels[] = $labels[$statut];
            $chartData[] = $candidaturesByStatut[$statut] ?? 0;
        }

        return view('admin.dashboard', compact(
            'nbEtudiants',
            'nbEntreprises',
            'nbOffresActives',
            'chartLabels',
            'chartData'
        ));
    }
}