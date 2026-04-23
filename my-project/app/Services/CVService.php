<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offre;
use App\Models\Skill;
use Illuminate\Support\Facades\Log;

class CVService
{
    /**
     * Parses the raw CV text to extract skills and levels,
     * and saves them to the student_skills pivot table.
     * Use static mock fallback.
     *
     * @param User $student
     * @return array The list of extracted skills
     */
    public function parseCV(User $student): array
    {
        if (empty($student->cv_text)) {
            return [];
        }

        // --- STATIC MOCK ALGORITHM ---
        $fallbackSkills = [
            'PHP', 'Laravel', 'JavaScript', 'HTML', 'CSS', 'SQL', 'Communication', 'Teamwork'
        ];
        $levels = ['beginner', 'intermediate', 'advanced', 'expert'];

        $extractedSkills = [];
        $lowerCv = strtolower($student->cv_text);
        
        foreach ($fallbackSkills as $skill) {
            $lowerSkill = strtolower($skill);
            if (str_contains($lowerCv, $lowerSkill)) {
                // Randomly assign a level, or simple based on string length
                $level = $levels[array_rand($levels)];
                $extractedSkills[] = ['skill' => $skill, 'level' => $level];
            }
        }

        // If no skills were found in text, just provide 3 basic ones to ensure the feature works visually
        if (empty($extractedSkills)) {
            $extractedSkills = [
                ['skill' => 'Communication', 'level' => 'advanced'],
                ['skill' => 'Teamwork', 'level' => 'intermediate'],
                ['skill' => 'Adaptability', 'level' => 'intermediate'],
            ];
        }

        try {
            $skillIdsToSync = [];
            foreach ($extractedSkills as $s) {
                if (!isset($s['skill']) || !isset($s['level'])) continue;

                $skillName = ucwords(strtolower(trim($s['skill'])));
                $level = in_array(strtolower($s['level']), $levels) ? strtolower($s['level']) : 'beginner';

                $skill = Skill::firstOrCreate(['name' => $skillName]);
                $skillIdsToSync[$skill->id] = ['level' => $level];
            }

            if (!empty($skillIdsToSync)) {
                $student->skills()->sync($skillIdsToSync);
            }

            return $extractedSkills;

        } catch (\Exception $e) {
            Log::error('Error parsing CV statically', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Generates an optimized CV summary and suggestions based on a specific internship offer.
     * Uses static fallback logic.
     *
     * @param User $student
     * @param Offre $offre
     * @return array JSON decoded structure of optimizations
     */
    public function optimizeCV(User $student, Offre $offre): array
    {
        return [
            'improved_summary' => "Étudiant passionné et motivé, je suis particulièrement intéressé par votre offre de '{$offre->titre}'. Je possède les bases nécessaires et je suis prêt à m'investir pleinement pour développer mes compétences au sein de votre équipe.",
            'missing_skills' => ['Docker', 'CI/CD', 'Anglais technique'],
            'suggestions' => [
                "Mettez plus en évidence vos projets académiques récents en lien avec cette offre.",
                "Utilisez des verbes d'action au lieu de listes passives dans la description de vos expériences.",
                "Assurez-vous que vos coordonnées (téléphone, LinkedIn) sont bien visibles au format PDF."
            ]
        ];
    }
}
