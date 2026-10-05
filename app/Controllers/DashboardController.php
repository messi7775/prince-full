<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Services\ReportService;

/**
 * DashboardController — main dashboard. Pulls real KPI data from the
 * ReportService; shows 0 when there is no data (README §9, §13).
 */
final class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $report = new ReportService();
        $kpis   = $report->dashboardKpis();
        $operations = $report->recentOperations();
        $inventory  = $report->inventoryStatus();

        $this->view('dashboard/index', [
            'pageTitle'  => 'لوحة التحكم',
            'active'     => 'dashboard',
            'kpis'       => $kpis,
            'operations' => $operations,
            'inventory'  => $inventory,
        ]);
    }
}
