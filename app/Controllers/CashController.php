<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\CashMovement;

final class CashController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $cash = new CashMovement();
        $movements = $this->safeList(fn () => $cash->all());
        $balance = 0;
        try {
            $balance = $cash->balance();
        } catch (\PDOException $e) {
            // table not present yet
        }

        $this->view('cash/index', [
            'pageTitle' => 'الصندوق',
            'active'    => 'cash',
            'movements' => $movements,
            'balance'   => $balance,
        ]);
    }

    public function withdrawals(Request $request): void
    {
        $this->requireAuth();
        $this->view('shared/placeholder', [
            'pageTitle' => 'سحوبات المالك',
            'active'    => 'owner-withdrawals',
            'hint'      => 'سجل سحوبات المالك يظهر هنا.',
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
