<?php

// app/Http/Controllers/Company/DashboardController.php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $stats = $this->dashboardService->getEntrepriseStats($user);

        // Keep 5 recent offers for quick view if needed
        $offres = Offre::where('entreprise_id', $user->id)
            ->withCount('candidatures')
            ->latest()
            ->take(5)
            ->get();

        return view('entreprise.dashboard', array_merge($stats, compact('offres')));
    }
}
