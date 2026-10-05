<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Sale;

final class SaleController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $sale = new Sale();
        $sales = $this->safeList(fn () => $sale->all());

        $this->view('sales/index', [
            'pageTitle' => 'المبيعات',
            'active'    => 'sales',
            'sales'     => $sales,
        ]);
    }

    private function safeList(callable $loader): array
    {
        try {
            return $loader();
        } catch (\PDOException $e) {
            return [];
        }
    }
}
