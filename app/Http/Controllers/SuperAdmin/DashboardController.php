<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SuperAdmin\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard Service
     */
    protected DashboardService $dashboardService;

    /**
     * Constructor
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display Super Admin Dashboard
     */
    public function index(): View
    {
        return view('superadmin.dashboard', [
            'hero'             => $this->dashboardService->getHeroData(),
            'statistics'       => $this->dashboardService->getStatistics(),
            'charts'           => $this->dashboardService->getChartData(),
            'activities'       => $this->dashboardService->getRecentActivities(),
            'systemStatus'     => $this->dashboardService->getSystemStatus(),
            'quickActions'     => $this->dashboardService->getQuickActions(),
            'notifications'    => $this->dashboardService->getNotifications(),
            'evaluationSummary' => $this->dashboardService->getEvaluationSummary(),
            'evaluationMatrix' => $this->dashboardService->getEvaluationMatrix(),
        ]);
    }
   
}