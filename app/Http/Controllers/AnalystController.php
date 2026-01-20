<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnalystController extends Controller
{
    public function index()
    {
        return view('analyst.dashboard');
    }

    public function riskAnalysis()
    {
        // Logic for risk analysis
        return view('analyst.risk-analysis');
    }

    public function monitoring()
    {
        // Logic for monitoring
        return view('analyst.monitoring');
    }
}
