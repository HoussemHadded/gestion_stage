<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    /**
     * Call OpenAI API to generate a response for the given prompt.
     * Falls back to a simulated response if OPENAI_API_KEY is not set.
     *
     * @param string $systemPrompt Default behavior instruction
     * @param string $userMessage Context and request
     * @return string Raw content from AI
     * @throws \RuntimeException
     */
    public function ask(string $systemPrompt, string $userMessage): string
    {
        $apiKey = config('services.openai.key');

        if (empty($apiKey)) {
            Log::info('AIService: No OpenAI API Key found. Falling back to simulation mode.');
            return $this->simulateResponse($systemPrompt, $userMessage);
        }

        try {
            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userMessage],
                    ],
                    'temperature' => 0.7, // Slightly creative but structured
                ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content');
            }

            Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            throw new \RuntimeException('Failed to communicate with OpenAI API: ' . $response->body());

        } catch (\Exception $e) {
            Log::error('OpenAI API Exception', ['message' => $e->getMessage()]);
            throw clone $e; // Re-throw to be handled safely by the controller
        }
    }

    /**
     * Simulate an AI response for local testing without API costs.
     */
    private function simulateResponse(string $systemPrompt, string $userMessage): string
    {
        // Add a small delay to simulate network request
        sleep(1);

        // Simulation for CV parsing
        if (str_contains($systemPrompt, 'extract technical skills')) {
            return json_encode([
                ["skill" => "PHP", "level" => "intermediate"],
                ["skill" => "Laravel", "level" => "advanced"],
                ["skill" => "HTML", "level" => "expert"],
                ["skill" => "CSS", "level" => "advanced"],
                ["skill" => "JavaScript", "level" => "intermediate"],
                ["skill" => "MySQL", "level" => "intermediate"],
                ["skill" => "Git", "level" => "beginner"]
            ]);
        }

        // Simulation for CV optimization
        if (str_contains($systemPrompt, 'provide feedback on how the student can improve')) {
             return json_encode([
                 'improved_summary' => "Étudiant en informatique passionné par le développement web, avec une solide maîtrise de Laravel et PHP. Proactif et motivé pour apporter une valeur ajoutée au sein d'un environnement innovant, tout en approfondissant mes compétences cloud et DevOps.",
                 'missing_skills' => ['Docker', 'AWS', 'Tests unitaires / PHPUnit'],
                 'suggestions' => [
                     'Mettez en valeur vos projets universitaires en incluant les liens vers vos repositories GitHub.',
                     'Ajoutez une brève section détaillant votre rôle spécifique et les défis techniques surmontés lors de vos projets récents.',
                     'Précisez votre niveau d\'anglais technique, très recherché pour ce type d\'offre.'
                 ]
             ]);
        }
        
        // Generic fallback error-like response if prompt doesn't match known simulations
        return "Simulation mode activated: Unrecognized prompt type.";
    }
}
