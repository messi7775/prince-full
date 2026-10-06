<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Expense;
use Models\CashMovement;

final class ExpenseController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $expense = new Expense();
        $expenses = $expense->all();
        $total = $expense->total();

        $this->view('expenses/index', [
            'pageTitle' => 'المصروفات',
            'active'    => 'expenses',
            'expenses'  => $expenses,
            'total'     => $total,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $category = (string)$request->input('category', '');
        $amount   = (int)$request->input('amount', 0);
        $note     = (string)$request->input('note', '');

        if ($category === '' || $amount <= 0) {
            $this->redirect('/expenses');
        }

        $data = [
            'category' => $category,
            'amount'   => $amount,
            'note'     => $note ?: null,
        ];

        $expId = (new Expense())->create($data);

        // Cash out for expense
        (new CashMovement())->create([
            'direction'      => CashMovement::OUT,
            'amount'         => $amount,
            'reason'         => 'مصروف: ' . $category,
            'reference_type' => 'expense',
            'reference_id'   => $expId,
        ]);

        $this->logAudit('expense_create', 'مصروف ' . $category . ': ' . $amount, $data);

        $this->redirect('/expenses');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            (new Expense())->delete($id);
            (new CashMovement())->execute(
                "DELETE FROM cash_movements WHERE reference_type = 'expense' AND reference_id = ?",
                [$id]
            );
            $this->logAudit('expense_delete', 'حذف مصروف #' . $id);
        }

        $this->redirect('/expenses');
    }
}
