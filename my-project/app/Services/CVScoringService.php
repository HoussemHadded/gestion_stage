<?php

namespace App\Services;

use App\Models\User;

class CVScoringService
{
    /**
     * Calculates the overall CV Quality Score (0-100) for a student.
     * and persists it to the database.
     */
    public function score(User $student): int
    {
        $score = 0;
        
        $cvText = strtolower($student->cv_text ?? '');

        // 1. Skills Map (40 Points Max)
        // Count total valid skills attached to profile
        $skillsCount = $student->skills()->count();
        // Give 5 points per skill up to 40
        $score += min($skillsCount * 5, 40);

        // 2. Education Level (20 Points Max)
        if (preg_match('/\b(master|mastere|ingénieur|engineer|doctorat|phd)\b/i', $cvText)) {
            $score += 20;
        } elseif (preg_match('/\b(licence|bachelor|dut|bts|bac)\b/i', $cvText)) {
            $score += 10;
        }

        // 3. Experience (20 Points Max)
        if (preg_match_all('/\b(stage|internship|expérience|experience|projet|project)\b/i', $cvText, $matches)) {
            // Count occurrences, max 4 mentions = 20 pts
            $mentions = count($matches[0]);
            $score += min($mentions * 5, 20);
        }

        // 4. Platform Activity (20 Points Max)
        $candidaturesCount = $student->candidatures()->count();
        // 5 points per candidature up to 20
        $score += min($candidaturesCount * 5, 20);

        // Persist
        $student->update(['cv_score' => $score]);

        return $score;
    }
}
