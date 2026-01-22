<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomsCase;
use App\Models\Inspection;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalystController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:analyst,admin']);
    }

    public function index()
    {
        $riskData = $this->getRiskAnalysisData();
        $monitoringData = $this->getMonitoringData();
        return view('analyst.dashboard', compact('riskData', 'monitoringData'));
    }

    public function riskAnalysis()
    {
        $riskData = $this->getRiskAnalysisData();
        return view('analyst.risk-analysis', compact('riskData'));
    }

    public function monitoring()
    {
        $monitoringData = $this->getMonitoringData();
        return view('analyst.monitoring', compact('monitoringData'));
    }

    private function getRiskAnalysisData()
    {
        $cases = CustomsCase::with(['vehicle'])->latest()->take(50)->get();

        $cases->transform(function ($case) {
            // IZLABOTS: Tā kā modelī ir 'array' casts, risk_flags jau ir masīvs.
            // Ja tas ir null, izmantojam tukšu masīvu.
            $flags = $case->risk_flags ?? [];
            $count = count($flags);

            $case->risk_info = (object)[
                'level' => $count >= 3 ? 'high' : ($count == 2 ? 'medium' : 'low'),
                'label' => $count >= 3 ? 'Augsts' : ($count == 2 ? 'Vidējs' : 'Zems'),
                'class' => $count >= 3 ? 'danger' : ($count == 2 ? 'warning' : 'success')
            ];
            return $case;
        });

        $statistics = [
            'high'   => $cases->where('risk_info.level', 'high')->count(),
            'medium' => $cases->where('risk_info.level', 'medium')->count(),
            'low'    => $cases->where('risk_info.level', 'low')->count(),
            'total'  => $cases->count()
        ];

        return [
            'cases'      => $cases,
            'statistics' => $statistics,
            'trend'      => $this->getRiskTrendData()
        ];
    }

    private function getRiskTrendData()
    {
        $trend = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayCases = CustomsCase::whereDate('created_at', $date->format('Y-m-d'))->get();
            
            $trend[] = [
                'date' => $date->format('d.m'),
                // IZLABOTS: Šeit arī noņemts json_decode
                'high' => $dayCases->filter(fn($c) => count($c->risk_flags ?? []) >= 3)->count(),
                'total' => $dayCases->count()
            ];
        }
        return $trend;
    }

    private function getMonitoringData()
    {
        return [
            'active_sessions'     => DB::table('sessions')->count(),
            'processed_cases'     => CustomsCase::where('status', 'released')->count(),
            'pending_inspections' => Inspection::whereNull('decision')->count(),
            'activity_log'        => $this->getActivityLog(),
            'performance'         => $this->getPerformanceData(),
            'user_activity'       => $this->getUserActivityData(),
            'system_alerts'       => $this->getSystemAlerts(),
            'critical_errors'     => 0
        ];
    }

    // --- PALĪGMETODES ---

    private function getActivityLog()
    {
        return Inspection::with('customsCase')->latest()->limit(5)->get()->map(function($ins) {
            return [
                'time'   => $ins->created_at->format('H:i'),
                'user'   => $ins->assigned_to ?? 'Sistēma',
                'action' => 'Pārbaude apstrādāta',
                'object' => $ins->customsCase->external_id ?? 'N/A',
                'status' => $ins->status == 'completed' ? 'success' : 'info'
            ];
        });
    }

    private function getPerformanceData()
    {
        return [
            'cpu_usage'    => [rand(20,40), rand(35,55), rand(30,50), rand(40,60), rand(25,45)],
            'memory_usage' => [60, 62, 65, 63, 64],
            'labels'       => ['10:00', '11:00', '12:00', '13:00', '14:00']
        ];
    }

    private function getUserActivityData()
    {
        return User::select('role', DB::raw('count(*) as count'))->groupBy('role')->get();
    }

    private function getSystemAlerts()
    {
        $pending = Inspection::whereNull('decision')->count();
        return [[
            'level' => $pending > 5 ? 'critical' : 'info',
            'message' => $pending > 5 ? "Rinda: $pending lietas" : "Sistēma stabila",
            'time' => now()->format('H:i')
        ]];
    }
}