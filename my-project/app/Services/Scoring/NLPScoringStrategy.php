<?php

namespace App\Services\Scoring;

use App\Contracts\CVScoringStrategy;
use App\Models\User;

class NLPScoringStrategy implements CVScoringStrategy
{
    public function score(User $student): int
    {
        $score = 0;
        $cvText = strtolower($student->cv_text ?? '');

        // 1. Skills Match (40 Points Max)
        // Give 5 points per linked skill in DB
        $skillsCount = $student->skills()->count();
        $score += min($skillsCount * 5, 40);

        // 2. Education Relevance (30 Points Max)
        if (preg_match('/\b(doctorat|phd|expert|postdoc)\b/i', $cvText)) {
            $score += 30;
        } elseif (preg_match('/\b(master|mastere|ingénieur|engineer|bac\+5|msc)\b/i', $cvText)) {
            $score += 25;
        } elseif (preg_match('/\b(licence|bachelor|bac\+3|bsc)\b/i', $cvText)) {
            $score += 15;
        } elseif (preg_match('/\b(dut|bts|bac\+2|associate)\b/i', $cvText)) {
            $score += 10;
        }

        // 3. Experience Level (30 Points Max)
        // More robust parsing for experience indicators
        $experienceKeywords = ['stage', 'internship', 'expérience', 'experience', 'projet', 'project', 'freelance', 'cdi', 'cdd', 'lead', 'senior'];
        $mentions = 0;
        
        foreach ($experienceKeywords as $keyword) {
            $mentions += substr_count($cvText, $keyword);
        }

        $score += min($mentions * 5, 30);

        return (int) min(max($score, 0), 100);
    }
}
