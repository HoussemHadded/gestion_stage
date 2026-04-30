<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offre;
use App\Models\OffreMatch;
use Illuminate\Support\Str;

class MatchingService
{
    /**
     * Dictionnaire de technologies sémantiques.
     * Permet de regrouper les technologies équivalentes ou parentes.
     */
    private const TECH_MAP = [
        'PHP' => ['php', 'php8', 'php7', 'laravel', 'symfony', 'codeigniter', 'cakephp', 'wordpress'],
        'Laravel' => ['laravel', 'php framework', 'lumen', 'eloquent'],
        'React' => ['react', 'reactjs', 'react.js', 'hooks', 'redux', 'next.js', 'nextjs'],
        'Vue.js' => ['vue', 'vuejs', 'vue.js', 'vuex', 'pinia', 'nuxt', 'nuxtjs'],
        'JavaScript' => ['js', 'javascript', 'es6', 'typescript', 'ts'],
        'Node.js' => ['node', 'nodejs', 'express', 'nest', 'backend js'],
        'Python' => ['python', 'django', 'flask', 'fastapi', 'data science'],
        'SQL' => ['sql', 'mysql', 'postgresql', 'mariadb', 'oracle', 'sql server'],
        'NoSQL' => ['mongodb', 'redis', 'firebase', 'cassandra'],
        'Docker' => ['docker', 'container', 'docker-compose'],
        'Kubernetes' => ['kubernetes', 'k8s', 'helm'],
        'DevOps' => ['devops', 'ci/cd', 'jenkins', 'github actions', 'pipelines', 'terraform', 'ansible'],
        'Cloud' => ['aws', 'azure', 'gcp', 'google cloud', 'cloud computing'],
        'Mobile' => ['flutter', 'react native', 'kotlin', 'swift', 'ios', 'android', 'dart'],
        'UI/UX' => ['figma', 'adobe xd', 'design', 'canva', 'prototyping'],
    ];

    private const ROLE_CATEGORIES = [
        'fullstack' => ['fullstack', 'full-stack', 'développeur complet', 'web developer'],
        'frontend' => ['frontend', 'front-end', 'développeur front', 'intégrateur'],
        'backend' => ['backend', 'back-end', 'développeur back', 'api developer'],
        'devops' => ['devops', 'infrastructure', 'cloud engineer', 'sre'],
        'data' => ['data scientist', 'data analyst', 'data engineer', 'machine learning'],
        'product' => ['product owner', 'product manager', 'po', 'pm', 'chef de produit'],
    ];

    public function calculate(User $student, Offre $offre): ?array
    {
        $cvText = $student->cv_text ?? '';
        if (empty($cvText) && $student->skills->isEmpty()) {
            OffreMatch::where('student_id', $student->id)->where('offre_id', $offre->id)->delete();
            return null;
        }

        $studentProfile = $this->parseProfile($student);
        $offerRequirements = $this->parseOffer($offre);

        // 1. Raw Component Scores
        $details = [];
        $scores = [
            'skills' => $this->scoreSkills($studentProfile['skills'], $offerRequirements['skills'], 40, $details),
            'experience' => $this->scoreExperience($studentProfile, $offerRequirements, 25, $details),
            'education' => $this->scoreEducation($studentProfile['level'], $offerRequirements['level'], 10, $details),
            'keywords' => $this->scoreKeywords($studentProfile['intent'], $offerRequirements['role'], 15, $details),
            'tools' => $this->scoreTools($studentProfile['tech'], $offerRequirements['tech'], 10, $details),
        ];

        // 2. Applying Advanced Boosting (Non-linear)
        $baseScore = array_sum($scores);
        $finalScore = $this->applyBoosts($baseScore, $studentProfile, $offerRequirements, $details);

        // 3. Minimum Score Guarantee
        $finalScore = $this->applyGuarantees($finalScore, $studentProfile, $offerRequirements, $details);

        $finalScore = min(100, round($finalScore, 2));
        $details['ai_summary'] = $this->generateSummary($finalScore);

        $match = OffreMatch::updateOrCreate(
            ['student_id' => $student->id, 'offre_id' => $offre->id],
            ['score' => $finalScore, 'details' => $details]
        );

        return ['score' => $match->score, 'details' => $match->details, 'match_id' => $match->id];
    }

    private function parseProfile(User $student): array
    {
        $text = strtolower($student->cv_text . ' ' . $student->name);
        $skills = $student->skills->pluck('name')->map(fn($s) => strtolower($s))->toArray();
        
        return [
            'skills' => array_unique(array_merge($skills, $this->extractEntity($text, self::TECH_MAP))),
            'tech' => $this->extractEntity($text, self::TECH_MAP),
            'intent' => $this->extractEntity($text, self::ROLE_CATEGORIES),
            'level' => $this->detectLevel($text),
            'projects' => $this->analyzeProjects($text),
        ];
    }

    private function parseOffer(Offre $offre): array
    {
        $text = strtolower($offre->titre . ' ' . $offre->description);
        $skills = $offre->skills->pluck('name')->map(fn($s) => strtolower($s))->toArray();

        return [
            'skills' => array_unique(array_merge($skills, $this->extractEntity($text, self::TECH_MAP))),
            'tech' => $this->extractEntity($text, self::TECH_MAP),
            'role' => $this->extractEntity($text, self::ROLE_CATEGORIES),
            'level' => $offre->level_required ? strtolower($offre->level_required) : $this->detectLevel($text),
        ];
    }

    private function extractEntity(string $text, array $map): array
    {
        $found = [];
        foreach ($map as $canonical => $synonyms) {
            foreach ($synonyms as $synonym) {
                if (preg_match('/(?<![a-z0-9])' . preg_quote($synonym, '/') . '(?![a-z0-9])/i', $text)) {
                    $found[] = $canonical;
                }
            }
        }
        return array_unique($found);
    }

    private function scoreSkills(array $studentSkills, array $offerSkills, int $weight, array &$details): float
    {
        if (empty($offerSkills)) return $weight;

        $matched = array_intersect($offerSkills, $studentSkills);
        $missing = array_diff($offerSkills, $studentSkills);

        $ratio = count($matched) / count($offerSkills);
        
        // Non-linear scoring: matching key skills gives more points
        $score = (pow($ratio, 0.7)) * $weight;

        $details['skills'] = [
            'score' => round($score, 2),
            'matched' => array_values($matched),
            'missing' => array_values($missing),
            'reason' => count($matched) . "/" . count($offerSkills) . " compétences sémantiques identifiées."
        ];

        return $score;
    }

    private function applyBoosts(float $score, array $student, array $offer, array &$details): float
    {
        $boosts = 0;
        
        // 1. Stack Matching Boost (+25%)
        $matchedTech = array_intersect($offer['tech'], $student['tech']);
        $techRatio = count($offer['tech']) > 0 ? count($matchedTech) / count($offer['tech']) : 1;
        
        if ($techRatio >= 0.8) {
            $boosts += 25;
            $details['boost_applied'][] = "Full-Stack Match Boost (+25%)";
        } elseif ($techRatio >= 0.5) {
            $boosts += 10;
            $details['boost_applied'][] = "Partial Stack Boost (+10%)";
        }

        // 2. Role Alignment Boost (+10%)
        if (!empty(array_intersect($student['intent'], $offer['role']))) {
            $boosts += 10;
            $details['boost_applied'][] = "Role Alignment Boost (+10%)";
        }

        // 3. Project Experience Boost (+5%)
        if (!empty($student['projects']['relevant_tech'])) {
            $boosts += 5;
            $details['boost_applied'][] = "Real-world Project Boost (+5%)";
        }

        return $score + $boosts;
    }

    private function applyGuarantees(float $score, array $student, array $offer, array &$details): float
    {
        $matchedTech = array_intersect($offer['tech'], $student['tech']);
        $techRatio = count($offer['tech']) > 0 ? count($matchedTech) / count($offer['tech']) : 1;
        $roleMatch = !empty(array_intersect($student['intent'], $offer['role']));

        // Minimum Score Guarantee: 70% stack + role match => at least 80%
        if ($techRatio >= 0.7 && $roleMatch && $score < 80) {
            $score = 80 + ($techRatio * 10); // Scale between 80 and 90
            $details['boost_applied'][] = "Guaranteed Relevance Rule Applied (Min 80%)";
        }

        return $score;
    }

    private function scoreExperience(array $student, array $offer, int $weight, array &$details): float
    {
        $score = 0;
        if ($student['projects']['count'] >= 2) $score += $weight * 0.6;
        if (!empty(array_intersect($student['projects']['relevant_tech'], $offer['tech']))) $score += $weight * 0.4;

        $details['experience'] = [
            'score' => round($score, 2),
            'reason' => "Analyse des projets : " . $student['projects']['count'] . " réalisations trouvées."
        ];
        return $score;
    }

    private function scoreEducation(string $studentLevel, ?string $offerLevel, int $weight, array &$details): float
    {
        $levelMap = ['bac' => 1, 'bac+2' => 2, 'bac+3' => 3, 'licence' => 3, 'master' => 5, 'bac+5' => 5, 'ingénieur' => 5];
        $sVal = $levelMap[$studentLevel] ?? 1;
        $oVal = $offerLevel ? ($levelMap[strtolower($offerLevel)] ?? 1) : 0;

        $score = $sVal >= $oVal ? $weight : $weight * 0.5;
        $details['level'] = ['score' => $score, 'reason' => "Équivalence de diplôme validée."];
        return $score;
    }

    private function scoreKeywords(array $studentIntent, array $offerRole, int $weight, array &$details): float
    {
        $match = !empty(array_intersect($studentIntent, $offerRole));
        $score = $match ? $weight : $weight * 0.2;
        $details['keywords'] = ['score' => $score, 'reason' => "Orientation métier compatible."];
        return $score;
    }

    private function scoreTools(array $studentTech, array $offerTech, int $weight, array &$details): float
    {
        $matched = array_intersect($offerTech, $studentTech);
        $ratio = count($offerTech) > 0 ? count($matched) / count($offerTech) : 1;
        $score = $ratio * $weight;
        $details['tools'] = ['score' => $score, 'reason' => count($matched) . " outils technologiques maîtrisés."];
        return $score;
    }

    private function analyzeProjects(string $text): array
    {
        $keywords = ['projet', 'réalisation', 'développement', 'github', 'portfolio'];
        $count = 0;
        foreach ($keywords as $k) if (Str::contains($text, $k)) $count++;
        
        return [
            'count' => $count,
            'relevant_tech' => $this->extractEntity($text, self::TECH_MAP)
        ];
    }

    private function detectLevel(string $text): string
    {
        if (Str::contains($text, ['ingénieur', 'master', 'bac+5'])) return 'ingénieur';
        if (Str::contains($text, ['licence', 'bac+3'])) return 'bac+3';
        return 'bac';
    }

    private function generateSummary(float $score): string
    {
        if ($score >= 85) return "Match Exceptionnel. Le candidat possède la stack complète et l'orientation métier idéale.";
        if ($score >= 70) return "Profil très solide. Les compétences clés sont présentes avec une expérience pertinente.";
        if ($score >= 50) return "Match intéressant. Quelques lacunes sur la stack technique mais bon potentiel.";
        return "Faible correspondance. La stack ou le rôle ne semblent pas alignés.";
    }
}
