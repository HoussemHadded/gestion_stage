<?php

namespace App\Services;

use App\Models\Candidature;

class MatchService
{
    /**
     * Calculates the match percentage for a specific candidature tracking
     * how well the candidate fits the actual offer.
     */
    public function calculate(Candidature $candidature): int
    {
        $student = $candidature->student;
        $offre = $candidature->offre;

        if (!$student || !$offre) {
            return 0;
        }

        // Fetch offer required skills (assuming basic extraction or relationship)
        // If the platform already has offer_skills, use them.
        $offerSkills = $offre->skills()->pluck('name')->map(fn($name) => strtolower($name))->toArray();
        $studentSkills = $student->skills()->pluck('name')->map(fn($name) => strtolower($name))->toArray();

        $score = 0;

        // 1. Direct Skill Matches (Up to 70%)
        if (count($offerSkills) > 0) {
            $matchedSkills = array_intersect($offerSkills, $studentSkills);
            $skillsMatchRatio = count($matchedSkills) / count($offerSkills);
            $score += $skillsMatchRatio * 70;
        } else {
            // Unspecified skills -> default fallback based on general student skill volume
            $score += min(count($studentSkills) * 5, 50);
        }

        // 2. Keyword Context mapping (Up to 30%)
        $offerText = strtolower($offre->titre . ' ' . $offre->description);
        $studentCv = strtolower($student->cv_text ?? '');
        
        // Very basic NLP fallback keywords
        $buzzwords = ['laravel', 'php', 'react', 'vue', 'javascript', 'python', 'java', 'marketing', 'design', 'finance', 'agile'];
        
        $offerBuzzwordsContext = array_filter($buzzwords, fn($word) => str_contains($offerText, $word));
        $studentBuzzwordsContext = array_filter($buzzwords, fn($word) => str_contains($studentCv, $word));

        if (count($offerBuzzwordsContext) > 0) {
            $contextMatch = array_intersect($offerBuzzwordsContext, $studentBuzzwordsContext);
            $score += (count($contextMatch) / count($offerBuzzwordsContext)) * 30;
        } else {
            $score += 20; // Default context score for general unspecific offers
        }

        $percentage = (int) round(min($score, 100));

        // Persist dynamically recalculatable score
        $candidature->update(['match_percentage' => $percentage]);

        return $percentage;
    }
}
