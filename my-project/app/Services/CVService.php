<?php

namespace App\Services;

use App\Models\User;
use App\Models\Offre;
use App\Models\Skill;
use Illuminate\Support\Facades\Log;

class CVService
{
    private AIService $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Parses the raw CV text to extract skills and levels,
     * and saves them to the student_skills pivot table.
     *
     * @param User $student
     * @return array The list of extracted skills
     * @throws \Exception
     */
    public function parseCV(User $student): array
    {
        if (empty($student->cv_text)) {
            return [];
        }

        $systemPrompt = "You are an expert technical recruiter and resume parser.
Your task is to extract technical skills and their estimated proficiency levels from the provided CV text.
Return ONLY a valid JSON array of objects, with no markdown formatting or extra text.
Format: [{\"skill\": \"PHP\", \"level\": \"intermediate\"}]
Valid levels: beginner, intermediate, advanced, expert.";

        $userMessage = "Extract skills from this CV:\n" . $student->cv_text;

        try {
            $response = $this->aiService->ask($systemPrompt, $userMessage);
            
            // Clean up possible markdown code blocks from AI response
            $response = str_replace(['```json', '```'], '', $response);
            $extractedSkills = json_decode(trim($response), true);

            if (!is_array($extractedSkills)) {
                Log::warning('CV parsing failed to return valid JSON', ['response' => $response]);
                return [];
            }

            // Sync skills to database
            $skillIdsToSync = [];
            foreach ($extractedSkills as $s) {
                if (!isset($s['skill']) || !isset($s['level'])) continue;

                // Normalize skill name to Title Case
                $skillName = ucwords(strtolower(trim($s['skill'])));
                $level = in_array(strtolower($s['level']), ['beginner', 'intermediate', 'advanced', 'expert']) 
                            ? strtolower($s['level']) 
                            : 'beginner';

                // Find or create the skill in global catalog
                $skill = Skill::firstOrCreate(['name' => $skillName]);
                
                // Keep track of pivot data
                $skillIdsToSync[$skill->id] = ['level' => $level];
            }

            // Syncing will completely replace old skills with new parsed list, which is the expected NLP behavior
            if (!empty($skillIdsToSync)) {
                $student->skills()->sync($skillIdsToSync);
            }

            return $extractedSkills;

        } catch (\Exception $e) {
            Log::error('Error parsing CV', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Generates an optimized CV summary and suggestions based on a specific internship offer.
     *
     * @param User $student
     * @param Offre $offre
     * @return array JSON decoded structure of optimizations
     * @throws \Exception
     */
    public function optimizeCV(User $student, Offre $offre): array
    {
         $systemPrompt = "You are an expert IT technical recruiter.
I will give you a student's CV text and an internship offer description.
Your task is to provide feedback on how the student can improve their CV for this specific offer.
Return ONLY a valid JSON object with the following structure, no markdown formatting:
{
  \"improved_summary\": \"A short professional summary in French to add to the top of the CV highlighting alignment with the offer.\",
  \"missing_skills\": [\"Skill1\", \"Skill2\"],
  \"suggestions\": [\"Actionable suggestion 1\", \"Actionable suggestion 2\"]
}";

        $studentCVText = !empty($student->cv_text) ? $student->cv_text : "No CV explicitly provided, consider the student's base profile.";

        $userMessage = "--- STUDENT CV ---\n" . $studentCVText . "\n\n" .
                       "--- INTERNSHIP OFFER ---\n" .
                       "Title: " . $offre->titre . "\n" .
                       "Type: " . ($offre->type ?? 'Non spécifié') . "\n" .
                       "Level Required: " . ($offre->level_required ?? 'Non spécifié') . "\n" .
                       "Description: " . $offre->description;

        try {
            $response = $this->aiService->ask($systemPrompt, $userMessage);
            $response = str_replace(['```json', '```'], '', $response);
            $result = json_decode(trim($response), true);

            return is_array($result) ? $result : [
                'improved_summary' => 'Impossible de générer le résumé. Veuillez réessayer.',
                'missing_skills' => [],
                'suggestions' => ['Le retour de l\'IA n\'était pas utilisable. Veuillez vérifier votre clé API ou réessayer.']
            ];

        } catch (\Exception $e) {
            Log::error('Error optimizing CV', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
