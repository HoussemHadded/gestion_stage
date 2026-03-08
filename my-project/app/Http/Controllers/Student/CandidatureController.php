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

    public function create()
    {
        $this->authorize('create', Candidature::class);

        $offres = Cache::remember('offres_all_list', 300, function () {
            return Offre::all();
        });

        return view('candidatures.create', compact('offres'));
    }

    public function store(StoreCandidatureRequest $request)
    {
        $this->authorize('create', Candidature::class);

        $validated = $request->validated();

        // Business rule: one candidature per student per offer
        $alreadyApplied = Candidature::where('student_id', auth()->id())
            ->where('offre_id', $validated['offre_id'])
            ->exists();

        if ($alreadyApplied) {
            return back()->withErrors([
                'offre_id' => __('candidature.already_applied')
            ])->withInput();
        }

        $data = $validated;
        $data['student_id'] = auth()->id();

        $this->candidatureService->store($data);

        return redirect()->route('dashboard')
                         ->with('success', __('candidature.created'));
    }
}
