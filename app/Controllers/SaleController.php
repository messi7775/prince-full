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

        $packageId    = (int)$request->input('package_id', 0);
        $distributorId = (int)$request->input('distributor_id', 0);
        $bundlesCount = (int)$request->input('bundles_count', 0);
        $bundlePrice  = (int)$request->input('bundle_price', 0);
        $paymentType  = (string)$request->input('payment_type', 'cash');
        $note         = (string)$request->input('note', '');

        if ($packageId <= 0 || $bundlesCount <= 0) {
            $this->redirect('/sales');
        }

        $total = $bundlesCount * $bundlePrice;

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
            'distributor_id' => $distributorId > 0 ? $distributorId : null,
            'package_id'     => $packageId,
            'bundles_count'  => $bundlesCount,
            'bundle_price'   => $bundlePrice,
            'total'          => $total,
            'payment_type'   => $paymentType,
            'note'           => $note ?: null,
        ];

        $saleId = (new Sale())->create($data);

        // Record cash movement for cash sales
        if ($paymentType === 'cash' && $total > 0) {
            (new CashMovement())->create([
                'direction'      => CashMovement::IN,
                'amount'         => $total,
                'reason'         => 'بيع كروت (نقدي)',
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
            // Remove associated cash movement
            (new CashMovement())->execute(
                "DELETE FROM cash_movements WHERE reference_type = 'sale' AND reference_id = ?",
                [$id]
            );
            $this->logAudit('sale_delete', 'حذف عملية بيع #' . $id);
        }

        $this->redirect('/sales');
    }
}
