<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use App\Http\Requests\Student\StoreCandidatureRequest;
use App\Services\CandidatureService;
use Illuminate\Support\Facades\Cache;

class CandidatureController extends Controller
{
    public function __construct(
        private readonly CandidatureService $candidatureService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Candidature::class);

        $studentId = auth()->id();
        $page = request()->get('page', 1);

        $candidatures = Cache::remember("student_candidatures_{$studentId}_page_{$page}", 300, function () use ($studentId) {
            return Candidature::with('offre.entreprise')
                ->where('student_id', $studentId)
                ->latest()
                ->paginate(10);
        });

        return view('student.candidatures', compact('candidatures'));
    }

    public function store(\Illuminate\Http\Request $request, $id)
    {
        \Illuminate\Support\Facades\Log::info("Tentative de postuler à l'offre $id", ['user_id' => auth()->id()]);
        // Replace isStudent() with strict role check requested by user
        if (auth()->user()->role->value !== 'student') {
            abort(403, 'Accès non autorisé.');
        }

        $alreadyApplied = Candidature::where('student_id', auth()->id())
            ->where('offre_id', $id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        // Pass only the strictly required fields to the service
        // CandidatureService will automatically inject the correct Enum STATUS and DATE
        $this->candidatureService->store([
            'student_id' => auth()->id(),
            'offre_id' => $id,
            'cv' => 'Candidature simplifiée',
        ]);

        return back()->with('success', 'Candidature envoyée avec succès');
    }
}
