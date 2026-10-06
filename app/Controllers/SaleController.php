<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Sale;
use Models\Package;
use Models\Distributor;
use Models\Inventory;
use Models\CashMovement;
use Models\AuditLog;

final class SaleController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $sale = new Sale();
        $sales = $sale->all();

        $packages     = (new Package())->active();
        $distributors = (new Distributor())->all();

        $this->view('sales/index', [
            'pageTitle'    => 'المبيعات',
            'active'       => 'sales',
            'sales'        => $sales,
            'packages'     => $packages,
            'distributors' => $distributors,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $packageId     = (int)$request->input('package_id', 0);
        $distributorId = (int)$request->input('distributor_id', 0);
        $bundlesCount  = (int)$request->input('bundles_count', 0);
        $bundlePrice   = (int)$request->input('bundle_price', 0);
        $paymentType   = (string)$request->input('payment_type', 'cash');
        $paidAmount    = (int)$request->input('paid_amount', 0);
        $note          = (string)$request->input('note', '');

        // Distributor is mandatory
        if ($distributorId <= 0) {
            $this->redirect('/sales');
        }

        if ($packageId <= 0 || $bundlesCount <= 0) {
            $this->redirect('/sales');
        }

        $total = $bundlesCount * $bundlePrice;

        // Normalize paid_amount based on payment type
        if ($paymentType === 'cash') {
            $paidAmount = $total;
        } elseif ($paymentType === 'credit') {
            $paidAmount = 0;
        } else {
            // installment — clamp paid_amount to [0, total]
            $paidAmount = max(0, min($paidAmount, $total));
        }

        // Deduct from inventory
        $inventory = new Inventory();
        $remaining = $bundlesCount;
        $batches = $inventory->activeBatches($packageId);
        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            $deduct = min((int)$batch['quantity'], $remaining);
            $newQty = (int)$batch['quantity'] - $deduct;
            $inventory->deductBatch((int)$batch['id'], $newQty);
            $remaining -= $deduct;
        }

        $data = [
            'distributor_id' => $distributorId,
            'package_id'     => $packageId,
            'bundles_count'  => $bundlesCount,
            'bundle_price'   => $bundlePrice,
            'total'          => $total,
            'paid_amount'    => $paidAmount,
            'payment_type'   => $paymentType,
            'note'           => $note ?: null,
        ];

        $saleId = (new Sale())->create($data);

        // Record cash movement for the paid portion
        if ($paidAmount > 0) {
            (new CashMovement())->create([
                'direction'      => CashMovement::IN,
                'amount'         => $paidAmount,
                'reason'         => $paymentType === 'cash'
                    ? 'بيع كروت (نقدي)'
                    : 'بيع كروت (تقسيط - دفعة أولى)',
                'reference_type' => 'sale',
                'reference_id'   => $saleId,
            ]);
        }

        $this->logAudit('sale_create', "بيع $bundlesCount شدة × $bundlePrice = $total", $data);

        $this->redirect('/sales');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            (new Sale())->delete($id);
            (new CashMovement())->deleteByReference('sale', $id);
            $this->logAudit('sale_delete', 'حذف عملية بيع #' . $id);
        }

        $this->redirect('/sales');
    }
}
