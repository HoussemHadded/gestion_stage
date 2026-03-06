<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Offre;
use App\Models\Candidature;
use Illuminate\Support\Facades\Cache;

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
        $cacheKey = 'admin_dashboard_stats';
        $ttl = 300; // 5 minutes

        $data = Cache::remember($cacheKey, $ttl, function () {
            $nbEtudiants = User::where('role', 'student')->count();
            $nbEntreprises = User::where('role', 'entreprise')->count();
            $nbOffresActives = Offre::count();

            $candidaturesByStatut = Candidature::selectRaw('statut, count(*) as total')
                ->groupBy('statut')
                ->pluck('total', 'statut')
                ->toArray();

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

            return compact('nbEtudiants', 'nbEntreprises', 'nbOffresActives', 'chartLabels', 'chartData');
        });

        return view('admin.dashboard', $data);
    }
}