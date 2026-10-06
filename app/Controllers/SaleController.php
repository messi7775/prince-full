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

        $data = $this->validateSaleInput($request);
        if ($data === null) {
            $this->redirect('/sales');
        }

        $db = \Database::connection();

        try {
            $db->beginTransaction();

            $this->deductInventory($data['package_id'], $data['bundles_count']);

            $saleId = (new Sale())->create($data);

            // Cash sale: record cash movement
            if ($data['payment_type'] === 'cash') {
                (new CashMovement())->create([
                    'direction'      => CashMovement::IN,
                    'amount'         => $data['total'],
                    'reason'         => 'بيع كروت (نقدي)',
                    'reference_type' => 'sale',
                    'reference_id'   => $saleId,
                ]);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        $this->logAudit('sale_create', "بيع {$data['bundles_count']} شدة × {$data['bundle_price']} = {$data['total']}", $data);

        $this->redirect('/sales');
    }

    public function update(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        $old = (new Sale())->find($id);
        if (!$old) {
            $this->redirect('/sales');
        }

        $new = $this->validateSaleInput($request);
        if ($new === null) {
            $this->redirect('/sales');
        }

        $db = \Database::connection();

        try {
            $db->beginTransaction();

            // 1) Reverse old sale effect on inventory (restore bundles)
            $this->restoreInventory((int)$old['package_id'], (int)$old['bundles_count']);

            // 2) Delete old cash movement for this sale
            (new CashMovement())->deleteByReference('sale', $id);

            // 3) Apply new sale effect on inventory
            $this->deductInventory($new['package_id'], $new['bundles_count']);

            // 4) Update the sale record
            (new Sale())->update($id, $new);

            // 5) Record new cash movement if cash sale
            if ($new['payment_type'] === 'cash') {
                (new CashMovement())->create([
                    'direction'      => CashMovement::IN,
                    'amount'         => $new['total'],
                    'reason'         => 'بيع كروت (نقدي)',
                    'reference_type' => 'sale',
                    'reference_id'   => $id,
                ]);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }

        // Audit log with old → new description
        $oldType = $old['payment_type'] === 'cash' ? 'نقدي' : 'آجل';
        $newType = $new['payment_type'] === 'cash' ? 'نقدي' : 'آجل';
        $this->logAudit(
            'sale_update',
            "تعديل بيع: من {$old['bundles_count']} شدة / {$old['total']} {$oldType} إلى {$new['bundles_count']} شدة / {$new['total']} {$newType}",
            ['old' => $old, 'new' => $new]
        );

        $this->redirect('/sales');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            $sale = (new Sale())->find($id);
            if (!$sale) {
                $this->redirect('/sales');
            }

            $db = \Database::connection();

            try {
                $db->beginTransaction();

                // Restore inventory (reverse the sale)
                $this->restoreInventory((int)$sale['package_id'], (int)$sale['bundles_count']);

                // Delete sale and its cash movement
                (new Sale())->delete($id);
                (new CashMovement())->deleteByReference('sale', $id);

                $db->commit();
            } catch (\Throwable $e) {
                $db->rollBack();
                throw $e;
            }

            $type = $sale['payment_type'] === 'cash' ? 'نقدي' : 'آجل';
            $this->logAudit('sale_delete', "حذف عملية بيع #{$id}: {$sale['bundles_count']} شدة / {$sale['total']} {$type}");
        }

        $this->redirect('/sales');
    }

    // ── Helper methods ─────────────────────────────────────────────────────

    /**
     * Validate and normalize sale input. Returns null on invalid input.
     * Only 'cash' and 'credit' payment types are allowed.
     */
    private function validateSaleInput(Request $request): ?array
    {
        $packageId     = (int)$request->input('package_id', 0);
        $distributorId = (int)$request->input('distributor_id', 0);
        $bundlesCount  = (int)$request->input('bundles_count', 0);
        $bundlePrice   = (int)$request->input('bundle_price', 0);
        $paymentType   = (string)$request->input('payment_type', 'cash');
        $note          = (string)$request->input('note', '');

        if ($distributorId <= 0 || $packageId <= 0 || $bundlesCount <= 0) {
            return null;
        }

        $total = $bundlesCount * $bundlePrice;
        if ($total <= 0) {
            return null;
        }

        // Only cash or credit
        if (!in_array($paymentType, ['cash', 'credit'], true)) {
            $paymentType = 'cash';
        }

        return [
            'distributor_id' => $distributorId,
            'package_id'     => $packageId,
            'bundles_count'  => $bundlesCount,
            'bundle_price'   => $bundlePrice,
            'total'          => $total,
            'paid_amount'    => 0,
            'payment_type'   => $paymentType,
            'note'           => $note ?: null,
        ];
    }

    /** Deduct bundles from inventory (FIFO across active batches). */
    private function deductInventory(int $packageId, int $count): void
    {
        $inventory = new Inventory();
        $remaining = $count;
        $batches = $inventory->activeBatches($packageId);
        foreach ($batches as $batch) {
            if ($remaining <= 0) break;
            $deduct = min((int)$batch['quantity'], $remaining);
            $newQty = (int)$batch['quantity'] - $deduct;
            $inventory->deductBatch((int)$batch['id'], $newQty);
            $remaining -= $deduct;
        }
    }

    /** Restore bundles to inventory (add back to the active row for the package). */
    private function restoreInventory(int $packageId, int $count): void
    {
        $inventory = new Inventory();
        $batches = $inventory->activeBatches($packageId);
        if (!empty($batches)) {
            // Add to the first active batch
            $batch = $batches[0];
            $newQty = (int)$batch['quantity'] + $count;
            $inventory->deductBatch((int)$batch['id'], $newQty);
        } else {
            // No active batch — find the package's inventory row and restore
            $row = $inventory->findByPackageId($packageId);
            if ($row) {
                $newQty = (int)$row['quantity'] + $count;
                $inventory->setQuantity((int)$row['id'], $newQty);
            }
        }
    }
}
