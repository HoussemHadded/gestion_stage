<?php

// app/Http/Controllers/Company/DashboardController.php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $offres       = Offre::where('entreprise_id', $user->id)->withCount('candidatures')->latest()->take(5)->get();
        $totalOffres  = Offre::where('entreprise_id', $user->id)->count();
        $totalCandidatures = $offres->sum('candidatures_count');

        return view('entreprise.dashboard', compact('offres', 'totalOffres', 'totalCandidatures'));
    }
}
