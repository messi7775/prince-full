<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Inventory;
use Models\Package;
use Models\AuditLog;
use Session;

final class InventoryController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $inventory = new Inventory();
        $items     = $inventory->all();
        $lowStock  = $inventory->lowStock();
        $movements = $inventory->movements();

        $this->view('inventory/index', [
            'pageTitle' => 'المخزون',
            'active'    => 'inventory',
            'items'     => $items,
            'lowStock'  => $lowStock,
            'movements' => $movements,
            'error'     => Session::get('inventory_error'),
        ]);

        Session::forget('inventory_error');
    }

    /** إضافة: add quantity to current stock. */
    public function add(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id       = (int)$request->input('id', 0);
        $quantity = (int)$request->input('quantity', 0);

        if ($id <= 0 || $quantity <= 0) {
            Session::set('inventory_error', 'الكمية المضافة يجب أن تكون أكبر من صفر');
            $this->redirect('/inventory');
        }

        $inventory = new Inventory();
        $row = $inventory->find($id);
        if ($row === null) {
            Session::set('inventory_error', 'سجل المخزون غير موجود');
            $this->redirect('/inventory');
        }

        $oldQty = (int)$row['quantity'];
        $newQty = $oldQty + $quantity;
        $price  = (int)$row['bundle_price'];

        $inventory->addQuantity($id, $quantity);
        $inventory->logMovement([
            'package_id'   => (int)$row['package_id'],
            'action'        => 'add',
            'old_quantity'  => $oldQty,
            'new_quantity'  => $newQty,
            'bundle_price'  => $price,
            'old_value'     => $oldQty * $price,
            'new_value'     => $newQty * $price,
            'note'          => 'إضافة ' . $quantity . ' شدة',
        ]);
        $this->logAudit('inventory_add', 'إضافة ' . $quantity . ' شدة للباقة #' . $row['package_id']);

        $this->redirect('/inventory');
    }

    /** تعديل: replace the current quantity with a new value. */
    public function edit(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id       = (int)$request->input('id', 0);
        $quantity = (int)$request->input('quantity', 0);

        if ($id <= 0 || $quantity < 0) {
            Session::set('inventory_error', 'الكمية غير صحيحة');
            $this->redirect('/inventory');
        }

        $inventory = new Inventory();
        $row = $inventory->find($id);
        if ($row === null) {
            Session::set('inventory_error', 'سجل المخزون غير موجود');
            $this->redirect('/inventory');
        }

        $oldQty = (int)$row['quantity'];
        $newQty = $quantity;
        $price  = (int)$row['bundle_price'];

        $inventory->setQuantity($id, $newQty);
        $inventory->logMovement([
            'package_id'   => (int)$row['package_id'],
            'action'        => 'edit',
            'old_quantity'  => $oldQty,
            'new_quantity'  => $newQty,
            'bundle_price'  => $price,
            'old_value'     => $oldQty * $price,
            'new_value'     => $newQty * $price,
            'note'          => 'تعديل العدد من ' . $oldQty . ' إلى ' . $newQty,
        ]);
        $this->logAudit('inventory_edit', 'تعديل مخزون الباقة #' . $row['package_id'] . ' من ' . $oldQty . ' إلى ' . $newQty);

        $this->redirect('/inventory');
    }

    /** حذف: delete the inventory row after confirmation. */
    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id <= 0) {
            $this->redirect('/inventory');
        }

        $inventory = new Inventory();
        $row = $inventory->find($id);
        if ($row === null) {
            $this->redirect('/inventory');
        }

        $oldQty = (int)$row['quantity'];
        $price  = (int)$row['bundle_price'];

        $inventory->logMovement([
            'package_id'   => (int)$row['package_id'],
            'action'        => 'delete',
            'old_quantity'  => $oldQty,
            'new_quantity'  => 0,
            'bundle_price'  => $price,
            'old_value'     => $oldQty * $price,
            'new_value'     => 0,
            'note'          => 'حذف سجل المخزون',
        ]);
        $inventory->delete($id);
        $this->logAudit('inventory_delete', 'حذف سجل مخزون الباقة #' . $row['package_id']);

        $this->redirect('/inventory');
    }
}
