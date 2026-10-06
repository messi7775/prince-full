<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Payment;
use Models\Distributor;
use Models\CashMovement;

final class PaymentController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $payment = new Payment();
        $payments = $payment->all();

        $distributors = (new Distributor())->all();

        $this->view('payments/index', [
            'pageTitle'    => 'التحصيلات',
            'active'       => 'payments',
            'payments'     => $payments,
            'distributors' => $distributors,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $distributorId = (int)$request->input('distributor_id', 0);
        $amount        = (int)$request->input('amount', 0);
        $note          = (string)$request->input('note', '');

        if ($distributorId <= 0 || $amount <= 0) {
            $this->redirect('/payments');
        }

        $data = [
            'distributor_id' => $distributorId,
            'amount'         => $amount,
            'note'           => $note ?: null,
        ];

        $payId = (new Payment())->create($data);

        // Cash in from collection
        (new CashMovement())->create([
            'direction'      => CashMovement::IN,
            'amount'         => $amount,
            'reason'         => 'تحصيل من موزع',
            'reference_type' => 'payment',
            'reference_id'   => $payId,
        ]);

        $this->logAudit('payment_create', 'تحصيل ' . $amount, $data);

        $this->redirect('/payments');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            (new Payment())->delete($id);
            (new CashMovement())->execute(
                "DELETE FROM cash_movements WHERE reference_type = 'payment' AND reference_id = ?",
                [$id]
            );
            $this->logAudit('payment_delete', 'حذف تحصيل #' . $id);
        }

        $this->redirect('/payments');
    }
}
