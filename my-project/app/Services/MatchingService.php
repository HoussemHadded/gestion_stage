<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offre;
use App\Models\OffreMatch;

class MatchingService
{
    private const SKILL_DICT = [
        'php', 'laravel', 'symfony', 'wordpress', 'javascript', 'typescript', 'node.js', 'react', 'vue', 'angular',
        'next.js', 'python', 'django', 'flask', 'fastapi', 'sql', 'mysql', 'postgresql', 'mongodb', 'docker', 'kubernetes',
        'aws', 'azure', 'gcp', 'git', 'linux', 'html', 'css', 'tailwind', 'figma', 'ui/ux', 'tensorflow', 'pytorch',
        'nlp', 'data science', 'marketing', 'finance', 'excel', 'agile', 'scrum', 'testing', 'qa', 'cybersécurité',
        'java', 'spring', 'hibernate', 'c++', 'c#', 'dotnet', 'ruby', 'rails', 'go', 'rust', 'flutter', 'kotlin'
    ];

    /**
     * Calculate and store the compatibility score between a student and an offer.
     * Pure PHP rule-based scoring (0-100) to avoid API dependency for bulk operations.
     *
     * @param User $student
     * @param Offre $offre
     * @return array|null The calculated score and details, or null if no CV data
     */
    public function calculate(User $student, Offre $offre): ?array
    {
        $cvText = trim($student->cv_text ?? '');
        
        // If the student has literally no CV text and no skills, don't generate a fake score
        if (empty($cvText) && $student->skills->isEmpty()) {
            OffreMatch::where('student_id', $student->id)->where('offre_id', $offre->id)->delete();
            return null;
        }

        $details = [];
        $totalScore = 0.0;

        // 1. Skills (50% weight)
        $skillsScore = $this->calculateSkillsScore($student, $offre, 50, $details);
        $totalScore += $skillsScore;

        // 2. Experience Level (20% weight)
        $levelScore = $this->calculateLevelScore($student, $offre, 20, $details);
        $totalScore += $levelScore;

        // 3. Location (10% weight)
        $locationScore = $this->calculateLocationScore($student, $offre, 10, $details);
        $totalScore += $locationScore;

        // 4. Preferences (10% weight)
        $preferencesScore = $this->calculatePreferencesScore($student, $offre, 10, $details);
        $totalScore += $preferencesScore;

        // 5. Projects (10% weight)
        $projectsScore = $this->calculateProjectsScore($student, $offre, 10, $details);
        $totalScore += $projectsScore;

        // Overall simulated AI summary based purely on math
        $details['ai_summary'] = $this->generateSummary($totalScore);

        $finalScore = min(100, max(0, $totalScore));

        // Store or update the match record in database
        $match = OffreMatch::updateOrCreate(
            ['student_id' => $student->id, 'offre_id' => $offre->id],
            [
                'score' => round($finalScore, 2),
                'details' => $details
            ]
        );

        return [
            'score' => $match->score,
            'details' => $match->details,
            'match_id' => $match->id
        ];
    }

    private function extractSkillsFromText(string $text): array
    {
        $text = strtolower($text);
        return array_values(array_filter(self::SKILL_DICT, function($w) use ($text) {
            $quoted = preg_quote($w, '/');
            return preg_match('/(?<![a-z0-9])' . $quoted . '(?![a-z0-9])/i', $text);
        }));
    }

    private function calculateSkillsScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        // Get the required skills set by the company
        $offerSkillsRel = $offre->skills()->pluck('name')->toArray();
        $offerSkillsNLP = $this->extractSkillsFromText($offre->description . ' ' . $offre->titre);
        $offerSkills = array_unique(array_merge($offerSkillsRel, $offerSkillsNLP));
        $offerSkills = array_map('strtolower', $offerSkills);
        
        // Get the skills possessed by the student
        $studentSkillsRel = $student->skills->pluck('name')->toArray();
        $studentSkillsNLP = $this->extractSkillsFromText($student->cv_text ?? '');
        $studentSkills = array_unique(array_merge($studentSkillsRel, $studentSkillsNLP));
        $studentSkills = array_map('strtolower', $studentSkills);

        if (empty($offerSkills)) {
            $score = empty($studentSkills) ? $maxWeight * 0.5 : $maxWeight;
            $details['skills'] = [
                'score' => $score,
                'reason' => "L'offre ne liste aucune compétence technique spécifique.",
                'matched' => [],
                'missing' => []
            ];
            return $score;
        }

        $matchedNames = array_intersect($offerSkills, $studentSkills);
        $missingNames = array_diff($offerSkills, $studentSkills);
        
        $matchRatio = count($matchedNames) / count($offerSkills);
        $scoreEarned = $matchRatio * $maxWeight;

        $details['skills'] = [
            'score' => round($scoreEarned, 2),
            'reason' => count($matchedNames) . " compétence(s) en commun sur " . count($offerSkills) . " requise(s).",
            'matched' => array_values($matchedNames),
            'missing' => array_values($missingNames)
        ];

        return $scoreEarned;
    }

    private function calculateLevelScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        if (empty($offre->level_required)) {
            $score = $maxWeight * 0.5; // Neutral score when not specified
            $details['level'] = ['score' => $score, 'reason' => "Aucun niveau minimal spécifié par l'offre."];
            return $score;
        }

        $cvText = strtolower($student->cv_text ?? '');
        $reqLevel = strtolower($offre->level_required);

        if (str_contains($cvText, $reqLevel)) {
            $score = (float) $maxWeight;
            $reason = "Niveau '$reqLevel' détecté dans le profil/CV.";
        } else {
            $score = $maxWeight * 0.5; // ATS neutral score for unspecified level
            $reason = "Niveau '$reqLevel' non repéré explicitement dans le CV.";
        }

        $details['level'] = ['score' => $score, 'reason' => $reason];
        return $score;
    }

    private function calculateLocationScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        if (empty($offre->lieu)) {
             $score = $maxWeight * 0.5;
             $details['location'] = ['score' => $score, 'reason' => "Lieu non spécifié ou offre possiblement en télétravail."];
             return $score;
        }

        $cvText = strtolower($student->cv_text ?? '');
        $offLieu = strtolower($offre->lieu);
        
        $cleanLieu = trim(str_replace(['tunisie', 'tunis'], '', $offLieu));

        if (str_contains($cvText, $offLieu) || (!empty($cleanLieu) && str_contains($cvText, $cleanLieu))) {
            $score = (float) $maxWeight;
            $reason = "Localisation compatible avec '$offLieu'.";
        } else {
            $score = $maxWeight * 0.5; // ATS neutral score for unconfirmed relocation
            $reason = "La localisation n'est pas explicitement confirmée dans le CV.";
        }

        $details['location'] = ['score' => $score, 'reason' => $reason];
        return $score;
    }

    private function calculatePreferencesScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        if (empty($offre->type)) {
             $score = $maxWeight * 0.5;
             $details['preferences'] = ['score' => $score, 'reason' => "Type de stage ouvert ou non restrictif."];
             return $score;
        }

        $cvText = strtolower($student->cv_text ?? '');
        $type = strtolower($offre->type);

        $isPFE = str_contains($type, 'pfe') || str_contains($type, 'fin d\'étude') || str_contains($type, 'fin d\'etude');
        $isAlternance = str_contains($type, 'alternance');
        
        $match = false;
        if ($isPFE && (str_contains($cvText, 'pfe') || str_contains($cvText, 'fin d\'étude'))) $match = true;
        if ($isAlternance && str_contains($cvText, 'alternance')) $match = true;
        
        if (!$match && str_contains($cvText, $type)) $match = true;

        if ($match) {
            $score = (float) $maxWeight;
            $reason = "Le type de stage '$offre->type' correspond au profil.";
        } else {
            $score = $maxWeight * 0.5; // ATS neutral score
            $reason = "Le format '$offre->type' n'est pas explicitement demandé dans le CV.";
        }
        
        $details['preferences'] = ['score' => $score, 'reason' => $reason];
        return $score;
    }

    private function calculateProjectsScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        $cvText = strtolower($student->cv_text ?? '');
        
        $keywords = ['projet', 'github', 'portfolio', 'réalisation', 'développement de', 'application', 'site'];
        $foundCount = 0;
        foreach($keywords as $kw) {
            if (str_contains($cvText, $kw)) {
                $foundCount++;
            }
        }

        if ($foundCount >= 2) {
            $score = (float) $maxWeight;
            $reason = "Expérience avérée (projets/portfolio détectés).";
        } elseif ($foundCount == 1) {
            $score = $maxWeight * 0.5;
            $reason = "Quelques indices d'expérience pratique détectés.";
        } else {
            $score = 0;
            $reason = "Peu de références à des projets concrets dans le CV.";
        }

        $details['projects'] = ['score' => $score, 'reason' => $reason];
        return $score;
    }

    private function generateSummary(float $score): string
    {
        if ($score >= 80) return "Candidat exceptionnel. Profil très bien aligné avec les exigences techniques et formelles de l'offre.";
        if ($score >= 60) return "Candidat solide. Plusieurs correspondances, bien que certaines compétences ou pré-requis puissent manquer.";
        if ($score >= 40) return "Match partiel. Un potentiel existe mais un accompagnement ou une formation sur certaines lacunes sera nécessaire.";
        return "Faible compatibilité. Le candidat ne possède probablement pas les bases requises pour ce poste spécifique.";
    }
}
