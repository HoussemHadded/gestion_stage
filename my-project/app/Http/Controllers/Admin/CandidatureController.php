<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use Illuminate\Support\Facades\Cache;

class CandidatureController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Candidature::class);

        $page = request()->get('page', 1);

        $candidatures = Cache::remember("admin_candidatures_page_{$page}", 300, function () {
            return Candidature::with(['student', 'offre.entreprise'])
                ->latest()
                ->paginate(15);
        });

        return view('admin.candidatures', compact('candidatures'));
    }
}
