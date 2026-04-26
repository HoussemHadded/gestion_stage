<?php

namespace App\Services;

use App\Contracts\CVScoringStrategy;
use App\Models\User;
use App\Services\Scoring\NLPScoringStrategy;

class CVScoringService
{
    protected CVScoringStrategy $strategy;

    public function __construct(CVScoringStrategy $strategy = null)
    {
        // Default to NLP strategy if none provided, LLM-ready architecture
        $this->strategy = $strategy ?? new NLPScoringStrategy();
    }

    /**
     * Calculates the overall CV Quality Score (0-100) for a student
     * and persists it to the database quietly.
     */
    public function score(User $student): int
    {
        $score = $this->strategy->score($student);

        // Persist quietly to prevent infinite observer loops
        $student->updateQuietly(['cv_score' => $score]);

        return $score;
    }
    
    /**
     * Set the active strategy dynamically
     */
    public function setStrategy(CVScoringStrategy $strategy): self
    {
        $this->strategy = $strategy;
        return $this;
    }
}
