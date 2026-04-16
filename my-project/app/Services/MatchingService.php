<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offre;
use App\Models\OffreMatch;

class MatchingService
{
    /**
     * Calculate and store the compatibility score between a student and an offer.
     * Pure PHP rule-based scoring (0-100) to avoid API dependency for bulk operations.
     *
     * @param User $student
     * @param Offre $offre
     * @return array The calculated score and details
     */
    public function calculate(User $student, Offre $offre): array
    {
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

    private function calculateSkillsScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        // Get the required skills set by the company
        $offerSkills = $offre->skills()->pluck('name', 'skills.id')->toArray();
        
        // Get the skills possessed by the student
        $studentSkills = $student->skills->pluck('pivot.level', 'id')->toArray();

        // If offer doesn't strictly require any skills, assume perfect match to not penalize
        if (empty($offerSkills)) {
            $details['skills'] = [
                'score' => $maxWeight,
                'reason' => "L'offre ne liste aucune compétence technique spécifique.",
                'matched' => [],
                'missing' => []
            ];
            return $maxWeight;
        }

        $matchedNames = [];
        $missingNames = [];
        $scoreEarned = 0;
        
        $pointsPerSkill = $maxWeight / count($offerSkills);

        foreach ($offerSkills as $id => $name) {
            if (isset($studentSkills[$id])) {
                $matchedNames[] = $name;
                // Currently just awarding full points per matched skill regardless of level.
                // Could be advanced further by multiplying by $studentSkill->levelWeight()
                $scoreEarned += $pointsPerSkill; 
            } else {
                $missingNames[] = $name;
            }
        }

        $details['skills'] = [
            'score' => round($scoreEarned, 2),
            'reason' => count($matchedNames) . " compétence(s) en commun sur " . count($offerSkills) . " requise(s).",
            'matched' => $matchedNames,
            'missing' => array_values($missingNames)
        ];

        return $scoreEarned;
    }

    private function calculateLevelScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        if (empty($offre->level_required)) {
            $score = $maxWeight;
            $details['level'] = ['score' => $score, 'reason' => "Aucun niveau minimal spécifié par l'offre."];
            return $score;
        }

        $cvText = strtolower($student->cv_text ?? '');
        $reqLevel = strtolower($offre->level_required);

        if (str_contains($cvText, $reqLevel)) {
            $score = (float) $maxWeight;
            $reason = "Niveau '$reqLevel' détecté dans le profil/CV.";
        } else {
            $score = $maxWeight * 0.5; // Partial points since CV formats vary wildly
            $reason = "Niveau '$reqLevel' non repéré explicitement dans le CV.";
        }

        $details['level'] = ['score' => $score, 'reason' => $reason];
        return $score;
    }

    private function calculateLocationScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        if (empty($offre->lieu)) {
             $details['location'] = ['score' => $maxWeight, 'reason' => "Lieu non spécifié ou offre possiblement en télétravail."];
             return $maxWeight;
        }

        $cvText = strtolower($student->cv_text ?? '');
        $offLieu = strtolower($offre->lieu);
        
        $cleanLieu = trim(str_replace(['tunisie', 'tunis'], '', $offLieu));

        if (str_contains($cvText, $offLieu) || (!empty($cleanLieu) && str_contains($cvText, $cleanLieu))) {
            $score = (float) $maxWeight;
            $reason = "Localisation compatible avec '$offLieu'.";
        } else {
            $score = $maxWeight * 0.5;
            $reason = "La localisation pourrait être un obstacle (à confirmer).";
        }

        $details['location'] = ['score' => $score, 'reason' => $reason];
        return $score;
    }

    private function calculatePreferencesScore(User $student, Offre $offre, int $maxWeight, array &$details): float
    {
        if (empty($offre->type)) {
             $details['preferences'] = ['score' => $maxWeight, 'reason' => "Type de stage ouvert ou non restrictif."];
             return $maxWeight;
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
            $score = $maxWeight * 0.5;
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
            $score = $maxWeight * 0.7;
            $reason = "Quelques indices d'expérience pratique détectés.";
        } else {
            $score = $maxWeight * 0.3;
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
