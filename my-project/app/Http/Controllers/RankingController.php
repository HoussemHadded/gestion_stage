<?php

namespace App\Http\Controllers;

use App\Services\StudentRankingService;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    private StudentRankingService $rankingService;

    public function __construct(StudentRankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * Display the global student leaderboard.
     */
    public function index()
    {
        // Get top 20 students globally
        $topStudents = $this->rankingService->getTopCandidates(20);
        
        return view('ranking.index', compact('topStudents'));
    }
}
