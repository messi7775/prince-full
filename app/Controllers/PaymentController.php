<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Payment;

final class PaymentController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $payment = new Payment();
        $payments = $this->safeList(fn () => $payment->all());

        $this->view('payments/index', [
            'pageTitle' => 'التحصيلات',
            'active'    => 'payments',
            'payments'  => $payments,
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
