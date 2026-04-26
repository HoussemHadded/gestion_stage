<?php

namespace App\Contracts;

use App\Models\User;

interface CVScoringStrategy
{
    /**
     * Calculate the CV score for a given student.
     *
     * @param User $student
     * @return int Score between 0 and 100
     */
    public function score(User $student): int;
}
