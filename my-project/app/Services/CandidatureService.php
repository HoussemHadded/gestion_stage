<?php

namespace App\Services;

use App\Enums\StatutCandidature;
use App\Models\Candidature;
use Illuminate\Support\Facades\Log;

/**
 * Service de gestion des candidatures.
 *
 * ─── Notification Strategy ───────────────────────────────────────────────────
 * Notifications are sent SYNCHRONOUSLY (no ShouldQueue on the notification
 * classes) so they appear immediately without needing a queue worker.
 * The notification classes also write to the `database` channel so entries
 * appear in auth()->user()->notifications instantly after any mutation.
 *
 * ─── Cache Strategy ──────────────────────────────────────────────────────────
 * All write methods bust:
 *   • Global candidatures list (company & admin list views)
 *   • Student's personal candidatures list ("Mes Candidatures")
 *   • Student's dashboard stats
 *   • The receiving enterprise's dashboard stats
 *   • Admin dashboard stats
 */
class CandidatureService
{
    public function __construct(
        private CacheService $cacheService
    ) {}

    // ──────────────────────────────────────────────────────────────────────────
    // STORE
    // ──────────────────────────────────────────────────────────────────────────

    public function store(array $data): Candidature
    {
        $data['date_candidature'] = now();
        $data['statut']           = StatutCandidature::EnAttente;

        $candidature = Candidature::create($data);

        // Load all relationships needed downstream
        $candidature->load('student', 'offre', 'offre.entreprise');

        Log::info('[CandidatureService] New candidature created', [
            'candidature_id' => $candidature->id,
            'student_id'     => $candidature->student_id,
            'offre_id'       => $candidature->offre_id,
            'entreprise_id'  => $candidature->offre->entreprise_id ?? null,
        ]);

        // Compute AI Match Percentage (async via observer/job)
        app(\App\Services\MatchService::class)->calculate($candidature);

        // ── Notify the enterprise (database + mail, synchronous) ──────────────
        $entreprise = $candidature->offre->entreprise;
        if ($entreprise) {
            try {
                $entreprise->notify(
                    new \App\Notifications\NouvelleCandidatureNotification($candidature)
                );
                Log::info('[CandidatureService] Enterprise notified', [
                    'entreprise_id' => $entreprise->id,
                    'candidature_id' => $candidature->id,
                ]);
            } catch (\Throwable $e) {
                Log::error('[CandidatureService] Failed to notify enterprise', [
                    'error'         => $e->getMessage(),
                    'entreprise_id' => $entreprise->id,
                ]);
            }
        } else {
            Log::warning('[CandidatureService] No enterprise found for offre', [
                'offre_id' => $candidature->offre_id,
            ]);
        }

        // ── Bust all affected caches ──────────────────────────────────────────
        $entrepriseId = $candidature->offre->entreprise_id;
        $this->cacheService->forgetCandidatures();
        $this->cacheService->forgetStudentCandidatures($candidature->student_id);
        $this->cacheService->forgetStudentDashboard($candidature->student_id);
        $this->cacheService->forgetEntrepriseDashboard($entrepriseId);
        $this->cacheService->forgetAdminDashboard();

        return $candidature;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE (general fields, not statut)
    // ──────────────────────────────────────────────────────────────────────────

    public function update(Candidature $candidature, array $data): bool
    {
        $result = $candidature->update($data);

        $this->cacheService->forgetCandidatures();

        return $result;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // UPDATE STATUT
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Met à jour le statut d'une candidature et notifie l'étudiant.
     * Invalide le dashboard entreprise ET étudiant car les compteurs changent.
     */
    public function updateStatut(Candidature $candidature, StatutCandidature $statut): bool
    {
        // Load before update so we still have relation data
        $candidature->loadMissing('student', 'offre', 'offre.entreprise');

        $result = $candidature->update(['statut' => $statut]);

        Log::info('[CandidatureService] Statut updated', [
            'candidature_id' => $candidature->id,
            'new_statut'     => $statut->value,
            'student_id'     => $candidature->student_id,
        ]);

        // ── Notify the student based on new status ────────────────────────────
        $student = $candidature->student;
        if ($student) {
            try {
                $notification = match ($statut) {
                    StatutCandidature::Acceptee => new \App\Notifications\CandidatureAccepteeNotification($candidature),
                    StatutCandidature::Refusee  => new \App\Notifications\CandidatureRefuseeNotification($candidature),
                    default => new \App\Notifications\CandidatureStatusNotification(
                        $candidature,
                        'Votre candidature est passée au statut : ' . $statut->label()
                    ),
                };

                $student->notify($notification);

                Log::info('[CandidatureService] Student notified', [
                    'student_id'     => $student->id,
                    'candidature_id' => $candidature->id,
                    'statut'         => $statut->value,
                ]);
            } catch (\Throwable $e) {
                Log::error('[CandidatureService] Failed to notify student', [
                    'error'      => $e->getMessage(),
                    'student_id' => $student->id ?? null,
                ]);
            }
        }

        // ── Bust caches: status change affects both entreprise and student ─────
        $entrepriseId = $candidature->offre->entreprise_id;
        $this->cacheService->forgetCandidatures();
        $this->cacheService->forgetStudentDashboard($candidature->student_id);
        $this->cacheService->forgetEntrepriseDashboard($entrepriseId);
        $this->cacheService->forgetAdminDashboard();

        return $result;
    }

    // ──────────────────────────────────────────────────────────────────────────
    // DELETE
    // ──────────────────────────────────────────────────────────────────────────

    public function delete(Candidature $candidature): bool
    {
        // Capture IDs before deletion (they're gone after delete())
        $studentId    = $candidature->student_id;
        $entrepriseId = $candidature->offre?->entreprise_id;

        $result = $candidature->delete();

        Log::info('[CandidatureService] Candidature deleted', [
            'student_id'    => $studentId,
            'entreprise_id' => $entrepriseId,
        ]);

        // ── Bust all affected caches ──────────────────────────────────────────
        $this->cacheService->forgetCandidatures();
        $this->cacheService->forgetStudentCandidatures($studentId);
        $this->cacheService->forgetStudentDashboard($studentId);
        if ($entrepriseId) {
            $this->cacheService->forgetEntrepriseDashboard($entrepriseId);
        }
        $this->cacheService->forgetAdminDashboard();

        return $result;
    }
}
