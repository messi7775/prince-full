<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Line;
use Models\CashMovement;

final class LineController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $line = new Line();
        $lines = $line->all();

        $this->view('lines/index', [
            'pageTitle' => 'الخطوط',
            'active'    => 'lines',
            'lines'     => $lines,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $name     = (string)$request->input('name', '');
        $provider = (string)$request->input('provider', '');
        $note     = (string)$request->input('note', '');

        if ($name === '') {
            $this->redirect('/lines');
        }

        $data = [
            'name'     => $name,
            'provider' => $provider ?: null,
            'note'     => $note ?: null,
        ];

        (new Line())->create($data);
        $this->logAudit('line_create', 'إضافة خط: ' . $name, $data);

        $this->redirect('/lines');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            (new Line())->delete($id);
            $this->logAudit('line_delete', 'حذف خط #' . $id);
        }

        $this->redirect('/lines');
    }

    public function payments(Request $request): void
    {
        $this->requireAuth();

        $line = new Line();
        $lines    = $line->all();
        $payments = $line->allPayments();

        $this->view('lines/payments', [
            'pageTitle' => 'تسديد الخطوط',
            'active'    => 'line-payments',
            'lines'     => $lines,
            'payments'  => $payments,
        ]);
    }

    public function storePayment(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $lineId    = (int)$request->input('line_id', 0);
        $amount    = (int)$request->input('amount', 0);
        $direction = (string)$request->input('direction', 'out');
        $note      = (string)$request->input('note', '');

        if ($lineId <= 0 || $amount <= 0) {
            $this->redirect('/line-payments');
        }

        $data = [
            'line_id'   => $lineId,
            'amount'    => $amount,
            'direction' => $direction,
            'note'      => $note ?: null,
        ];

        $payId = (new Line())->createPayment($data);

        // Cash movement: 'out' for paying a line, 'in' for receiving from a line
        (new CashMovement())->create([
            'direction'      => $direction,
            'amount'         => $amount,
            'reason'         => $direction === 'in' ? 'استلام من خط' : 'دفع خط',
            'reference_type' => 'line_payment',
            'reference_id'   => $payId,
        ]);

        $this->logAudit('line_payment', 'تسديد خط: ' . $amount, $data);

        $this->redirect('/line-payments');
    }

    public function deletePayment(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            (new Line())->deletePayment($id);
            (new CashMovement())->execute(
                "DELETE FROM cash_movements WHERE reference_type = 'line_payment' AND reference_id = ?",
                [$id]
            );
            $this->logAudit('line_payment_delete', 'حذف دفع خط #' . $id);
        }

        $this->redirect('/line-payments');
    }
}
