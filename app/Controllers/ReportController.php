<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Services\ReportService;

final class ReportController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $this->view('reports/index', [
            'pageTitle' => 'التقارير',
            'active'    => 'reports',
        ]);
    }

    public function search(Request $request): void
    {
        $this->requireAuth();
        $this->view('shared/placeholder', [
            'pageTitle' => 'البحث',
            'active'    => 'search',
            'hint'      => 'ابحث في النظام حسب الموزع، العملية، الباقة، التاريخ، أو الخط.',
        ]);
    }
}
