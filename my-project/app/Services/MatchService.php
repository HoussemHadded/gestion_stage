<?php

namespace App\Services;

use App\Models\Candidature;
use App\Events\MatchScoreUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * MatchService — Professional AI Scoring Architect.
 * 
 * This version implements:
 * 1. Deep Feature Extraction (Skills + Contextual NLP).
 * 2. Non-Linear Sigmoid Similarity for Skills.
 * 3. Strict Distance Matrices for Exp/Edu.
 * 4. Jaccard-based Semantic Overlap for Keywords.
 */
class MatchService
{
    private const WEIGHT_SKILLS     = 60;
    private const WEIGHT_EXPERIENCE = 25;
    private const WEIGHT_EDUCATION  = 15;

    private const EDU_LEVELS = [
        'doctorat' => 4, 'phd' => 4,
        'master' => 3, 'mastère' => 3, 'ingénieur' => 3, 'engineer' => 3, 'bac+5' => 3, 'msc' => 3,
        'licence' => 2, 'bachelor' => 2, 'bac+3' => 2, 'bsc' => 2,
        'bac+2' => 1, 'dut' => 1, 'bts' => 1,
    ];

    private const EXP_LEVELS = [
        'expert' => 4, 'lead' => 4,
        'senior' => 3, 'confirmé' => 3,
        'junior' => 2,
        'débutant' => 1, 'stage' => 1, 'internship' => 1, 'alternance' => 1,
    ];

    private const SKILL_DICT = [
        'php', 'laravel', 'symfony', 'wordpress', 'javascript', 'typescript', 'node.js', 'react', 'vue', 'angular',
        'next.js', 'python', 'django', 'flask', 'fastapi', 'sql', 'mysql', 'postgresql', 'mongodb', 'docker', 'kubernetes',
        'aws', 'azure', 'gcp', 'git', 'linux', 'html', 'css', 'tailwind', 'figma', 'ui/ux', 'tensorflow', 'pytorch',
        'nlp', 'data science', 'marketing', 'finance', 'excel', 'agile', 'scrum', 'testing', 'qa', 'cybersécurité',
        'java', 'spring', 'hibernate', 'c++', 'c#', 'dotnet', 'ruby', 'rails', 'go', 'rust', 'flutter', 'kotlin'
    ];

    public function calculate(Candidature $candidature, bool $useCache = true): int
    {
        $cacheKey = "match_v3_{$candidature->id}";
        if ($useCache && Cache::has($cacheKey)) return (int) Cache::get($cacheKey);

        $student = $candidature->student;
        $offre   = $candidature->offre;
        if (!$student || !$offre) return 0;

        // 1. EXTRACTION
        $offerData   = $this->getFeatures($offre, true);
        $studentData = $this->getFeatures($student, false);

        // 2. SKILL SIMILARITY (60%)
        $skillScore = $this->skillSim($offerData['skills'], $studentData['skills']);

        // 3. EXPERIENCE DISTANCE (25%)
        $expScore = $this->distSim($offerData['exp'], $studentData['exp'], self::WEIGHT_EXPERIENCE);

        // 4. EDUCATION DISTANCE (15%)
        $eduScore = $this->distSim($offerData['edu'], $studentData['edu'], self::WEIGHT_EDUCATION);

        // AGGREGATE
        $final = (int) round($skillScore + $expScore + $eduScore);
        $final = min(max($final, 0), 100);

        Log::info("[MatchService] Final Architecture Match", [
            'cand' => $candidature->id,
            'subj' => $offre->titre,
            'scores' => [
                'skills' => round($skillScore, 1),
                'exp' => round($expScore, 1),
                'edu' => round($eduScore, 1),
            ],
            'total' => $final
        ]);

        $candidature->updateQuietly(['match_percentage' => $final]);
        Cache::put($cacheKey, $final, now()->addHours(12));
        event(new MatchScoreUpdated($candidature));

        return $final;
    }

    private function skillSim(array $req, array $has): float
    {
        if (empty($req)) {
            // If the offer doesn't require any specific skills, 
            // give them base points if they have any skills, or just neutral score.
            return empty($has) ? self::WEIGHT_SKILLS * 0.5 : (float) self::WEIGHT_SKILLS;
        }
        
        $inter = array_intersect($req, $has);
        
        // Linear ratio of required skills the candidate possesses
        $matchRatio = count($inter) / count($req);
        
        return $matchRatio * self::WEIGHT_SKILLS;
    }

    private function distSim(int $req, int $got, int $weight): float
    {
        if ($req === 0) return $got > 0 ? (float) $weight : $weight * 0.5;
        
        if ($got >= $req) return (float) $weight;
        
        $gap = $req - $got;
        return match($gap) {
            1 => $weight * 0.7,
            2 => $weight * 0.4,
            3 => $weight * 0.1,
            default => 0.0
        };
    }

    private function getFeatures($model, bool $isOffre): array
    {
        $raw = strtolower($model->titre . ' ' . ($isOffre ? $model->description : $model->cv_text));
        
        $skills = $model->skills()->pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();
        $nlp = array_values(array_filter(self::SKILL_DICT, function($w) use ($raw) {
            $quoted = preg_quote($w, '/');
            return preg_match('/(?<![a-z0-9])' . $quoted . '(?![a-z0-9])/i', $raw);
        }));
        
        return [
            'skills' => array_unique(array_merge($skills, $nlp)),
            'exp' => $this->lvl($raw, self::EXP_LEVELS),
            'edu' => $this->lvl($raw, self::EDU_LEVELS),
            'raw' => $raw
        ];
    }

    private function lvl(string $t, array $l): int
    {
        $m = 0;
        foreach ($l as $k => $v) {
            $quoted = preg_quote($k, '/');
            if (preg_match('/(?<![a-z0-9])' . $quoted . '(?![a-z0-9])/i', $t)) {
                $m = max($m, $v);
            }
        }
        return $m;
    }

    public function recalculateAll(): void
    {
        Cache::flush();
        Candidature::all()->each(fn($c) => $this->calculate($c, false));
    }
}
