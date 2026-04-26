<?php

namespace App\Services;

use App\Models\User;
use App\Enums\UserRole;

class StudentRankingService
{
    /**
     * Gets ordered list of top candidates for the leaderboard.
     */
    public function getTopCandidates(int $limit = 10)
    {
        // Fetch candidates with their activity level and average match
        $students = User::where('role', UserRole::Etudiant)
            ->withCount('candidatures as activity_level')
            ->withAvg('candidatures as average_match', 'match_percentage')
            ->get();

        // Sort by CV Score (1st), Average Match (2nd), Activity Level (3rd)
        $sortedStudents = $students->sort(function ($a, $b) {
            $scoreA = $a->cv_score ?? 0;
            $scoreB = $b->cv_score ?? 0;
            if ($scoreA !== $scoreB) {
                return $scoreB <=> $scoreA;
            }

            $matchA = $a->average_match ?? 0;
            $matchB = $b->average_match ?? 0;
            if ($matchA !== $matchB) {
                return $matchB <=> $matchA;
            }

            $activityA = $a->activity_level ?? 0;
            $activityB = $b->activity_level ?? 0;
            return $activityB <=> $activityA;
        })->take($limit)->values();

        // Assign dynamic medals
        $medals = ['🥇', '🥈', '🥉'];
        $sortedStudents->each(function ($student, $index) use ($medals) {
            $student->medal = $index < 3 ? $medals[$index] : null;
            $student->global_rank = $index + 1;
        });

        return $sortedStudents;
    }
}
