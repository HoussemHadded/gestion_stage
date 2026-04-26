<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Offre;
use App\Enums\UserRole;
use App\Services\CandidatureService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
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

    public function store(Request $request, $id): RedirectResponse
    {
        \Illuminate\Support\Facades\Log::info("Tentative de postuler à l'offre $id", ['user_id' => auth()->id()]);

        // ✅ FIX: The route is already protected by role:etudiant middleware.
        // Manual check must use the correct enum value 'etudiant', not 'student'.
        // We use the isEtudiant() helper for clean, maintainable code.
        $user = auth()->user();
        if (! $user->isEtudiant()) {
            return back()->with('error', 'Seuls les étudiants peuvent postuler à une offre.');
        }

        // Ensure the offer exists
        $offre = Offre::find($id);
        if (! $offre) {
            return back()->with('error', "L'offre demandée n'existe pas ou a été supprimée.");
        }

        // Prevent duplicate applications
        $alreadyApplied = Candidature::where('student_id', $user->id)
            ->where('offre_id', $id)
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $this->candidatureService->store([
            'student_id'  => $user->id,
            'offre_id'    => $id,
            'cv'          => 'Candidature simplifiée',
            'cv_version'  => 'original',
        ]);

        return back()->with('success', 'Candidature envoyée avec succès !');
    }

    public function applyOptimized(Request $request, Offre $offre): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isEtudiant()) {
            return back()->with('error', 'Seuls les étudiants peuvent postuler à une offre.');
        }

        $alreadyApplied = Candidature::where('student_id', $user->id)
            ->where('offre_id', $offre->id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()->route('student.match.index')->with('error', 'Vous avez déjà postulé à cette offre.');
        }

        $this->candidatureService->store([
            'student_id' => $user->id,
            'offre_id'   => $offre->id,
            'cv'         => 'CV Optimisé via IA',
            'cv_version' => 'optimized',
        ]);

        return redirect()->route('student.candidatures.index')
            ->with('success', 'Candidature avec le CV optimisé envoyée avec succès !');
    }
}
