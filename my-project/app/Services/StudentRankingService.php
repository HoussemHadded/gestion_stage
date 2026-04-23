<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;

class StudentRankingService
{
    /**
     * Gets ordered list of top students for the leaderboard.
     */
    public function getTopStudents(int $limit = 10)
    {
        // For performance and dynamic ranking, we fetch all students with their candidatures
        // In a massively scaled app, this would be computed via background jobs or direct DB complex joins.
        $students = User::where('role', UserRole::Etudiant)
            ->withCount(['candidatures as accepted_candidatures_count' => function ($query) {
                // Assuming StatutCandidature::ACCEPTEE string is 'acceptee' or similar
                $query->where('statut', 'acceptee');
            }])
            ->withAvg('candidatures as average_match', 'match_percentage')
            ->orderBy('cv_score', 'desc')
            ->orderBy('average_match', 'desc')
            ->take($limit)
            ->get();

        // Calculate Global Rank Score dynamically (just for display if needed)
        // Global Score = (CV Score * 0.5) + (Average Match * 0.3) + (Accepted * 20 max capped)
        $students->each(function ($student) {
            $acceptedBonus = min($student->accepted_candidatures_count * 10, 20);
            $student->global_rank_score = round(
                (($student->cv_score ?? 0) * 0.5) +
                (($student->average_match ?? 0) * 0.3) +
                $acceptedBonus
            );
        });

        // Final sort purely by the computed global rank score descending
        return $students->sortByDesc('global_rank_score')->values();
    }
}
