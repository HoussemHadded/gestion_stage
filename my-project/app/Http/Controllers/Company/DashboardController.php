<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Simple dashboard for now, can be extended later.
        // Companies usually land on their offers index.
        return redirect()->route('candidatures.index');
    }
}
