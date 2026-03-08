<?php

// app/Http/Controllers/Supervisor/EvaluationController.php
// Phase 4 — Gestion de Stage

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supervisor\StoreEvaluationRequest;
use App\Http\Requests\Supervisor\UpdateEvaluationRequest;
use App\Models\Evaluation;
use App\Models\Offre;
use App\Models\User;
use App\Services\EvaluationService;
use App\Enums\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Gestion des évaluations de stage par l'encadrant.
 * Utilise EvaluationService pour toute la logique métier.
 */
class EvaluationController extends Controller
{
    public function __construct(
        private EvaluationService $evaluationService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | index — liste des évaluations de l'encadrant connecté
    |--------------------------------------------------------------------------
    */
    public function index(): View
    {
        $this->authorize('viewAny', Evaluation::class);

        $evaluations = $this->evaluationService->getForEncadrant(auth()->id());

        return view('encadrant.evaluations.index', compact('evaluations'));
    }

    /*
    |--------------------------------------------------------------------------
    | create — formulaire de création
    |--------------------------------------------------------------------------
    */
    public function create(): View
    {
        $this->authorize('create', Evaluation::class);

        $students = User::where('role', UserRole::Student)->orderBy('name')->get();
        $offres   = Offre::orderBy('titre')->get();

        return view('encadrant.evaluations.create', compact('students', 'offres'));
    }

    /*
    |--------------------------------------------------------------------------
    | store — persistance après validation
    |--------------------------------------------------------------------------
    */
    public function store(StoreEvaluationRequest $request): RedirectResponse
    {
        $this->authorize('create', Evaluation::class);

        $this->evaluationService->store(auth()->id(), $request->validated());

        return redirect()
            ->route('encadrant.evaluations.index')
            ->with('success', __('evaluation.created'));
    }

    /*
    |--------------------------------------------------------------------------
    | edit — formulaire de modification pré-rempli
    |--------------------------------------------------------------------------
    */
    public function edit(Evaluation $evaluation): View
    {
        $this->authorize('update', $evaluation);

        $students = User::where('role', UserRole::Student)->orderBy('name')->get();
        $offres   = Offre::orderBy('titre')->get();

        return view('encadrant.evaluations.edit', compact('evaluation', 'students', 'offres'));
    }

    /*
    |--------------------------------------------------------------------------
    | update — persistance de la mise à jour
    |--------------------------------------------------------------------------
    */
    public function update(UpdateEvaluationRequest $request, Evaluation $evaluation): RedirectResponse
    {
        $this->authorize('update', $evaluation);

        $this->evaluationService->update($evaluation, $request->validated());

        return redirect()
            ->route('encadrant.evaluations.index')
            ->with('success', __('evaluation.updated'));
    }

    /*
    |--------------------------------------------------------------------------
    | destroy — suppression
    |--------------------------------------------------------------------------
    */
    public function destroy(Evaluation $evaluation): RedirectResponse
    {
        $this->authorize('delete', $evaluation);

        $this->evaluationService->delete($evaluation);

        return redirect()
            ->route('encadrant.evaluations.index')
            ->with('success', __('evaluation.deleted'));
    }
}
