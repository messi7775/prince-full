<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Expense;

final class ExpenseController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $expense = new Expense();
        $expenses = $this->safeList(fn () => $expense->all());

        $this->view('expenses/index', [
            'pageTitle' => 'المصروفات',
            'active'    => 'expenses',
            'expenses'  => $expenses,
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
