<?php

// app/Http/Controllers/Admin/DashboardController.php
// Phase 4 — Gestion de Stage

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\DashboardService;

/**
 * Tableau de bord administrateur.
 * Délègue toutes les statistiques à DashboardService.
 */
class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        $this->authorize('viewAny', User::class);

        $data = $this->dashboardService->getAdminStats();

        return view('admin.dashboard', $data);
    }
}

