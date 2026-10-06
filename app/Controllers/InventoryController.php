<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Inventory;
use Models\Package;
use Models\CashMovement;

final class InventoryController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $inventory = new Inventory();
        $package   = new Package();

        $items    = $inventory->all();
        $packages = $package->active();

        $this->view('inventory/index', [
            'pageTitle' => 'المخزون',
            'active'    => 'inventory',
            'items'     => $items,
            'packages'  => $packages,
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $packageId   = (int)$request->input('package_id', 0);
        $quantity    = (int)$request->input('quantity', 0);
        $bundlePrice = (int)$request->input('bundle_price', 0);
        $note        = (string)$request->input('note', '');

        if ($packageId <= 0 || $quantity <= 0) {
            $this->redirect('/inventory');
        }

        $data = [
            'package_id'   => $packageId,
            'quantity'      => $quantity,
            'bundle_price'  => $bundlePrice,
            'status'        => 'active',
            'note'          => $note ?: null,
        ];

        (new Inventory())->create($data);

        // Cash out for purchasing stock
        if ($bundlePrice > 0) {
            $totalCost = $quantity * $bundlePrice;
            (new CashMovement())->create([
                'direction'      => CashMovement::OUT,
                'amount'         => $totalCost,
                'reason'         => 'شراء مخزون',
                'reference_type' => 'inventory',
            ]);
        }

        $this->logAudit('inventory_add', 'إضافة مخزون: ' . $quantity . ' شدة', $data);

        $this->redirect('/inventory');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            (new Inventory())->delete($id);
            $this->logAudit('inventory_delete', 'حذف دفعة مخزون #' . $id);
        }

        $this->redirect('/inventory');
    }
}
