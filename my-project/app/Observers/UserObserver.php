<?php

namespace App\Observers;

use App\Models\User;
use App\Services\CVScoringService;
use App\Events\CvScoreUpdated;

class UserObserver
{
    /**
     * Handle the User "saved" event.
     */
    public function saved(User $user): void
    {
        // If cv_text was changed, we trigger the scoring service
        if ($user->isDirty('cv_text') && $user->role === \App\Enums\UserRole::Etudiant) {
            $scoringService = app(CVScoringService::class);
            $scoringService->score($user);
            
            // Dispatch Real-time Event
            event(new CvScoreUpdated($user));
        }
    }
}
