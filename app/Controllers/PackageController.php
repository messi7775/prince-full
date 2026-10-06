<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Package;
use Models\Inventory;
use Models\AuditLog;
use Session;

final class PackageController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $package = new Package();
        $packages = $package->all();

        $this->view('packages/index', [
            'pageTitle' => 'الباقات',
            'active'    => 'packages',
            'packages'  => $packages,
            'error'     => Session::get('package_error'),
        ]);

        Session::forget('package_error');
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $name        = (string)$request->input('name', '');
        $bundlePrice = (int)$request->input('bundle_price', 0);
        $status      = (string)$request->input('status', 'active');
        $threshold   = (int)$request->input('low_stock_threshold', 5);

        if ($name === '' || $bundlePrice < 0) {
            Session::set('package_error', 'اسم الباقة وسعر الشدة مطلوبان');
            $this->redirect('/packages');
        }

        if ((new Package())->findByName($name) !== null) {
            Session::set('package_error', 'يوجد باقة بنفس الاسم بالفعل: ' . $name);
            $this->redirect('/packages');
        }

        $data = [
            'name'                => $name,
            'bundle_price'        => $bundlePrice,
            'status'              => $status,
            'low_stock_threshold' => $threshold,
        ];

        $packageId = (new Package())->create($data);
        // Auto-create a single inventory row for the new package:
        //   عدد الشدات = 5 تلقائيًا، سعر الشدة = سعر الباقة، القيمة = 5 × سعر الشدة
        (new Inventory())->findOrCreateByPackage($packageId, 5, $bundlePrice);
        $this->logAudit('package_create', 'إضافة باقة: ' . $name, $data);

        $this->redirect('/packages');
    }

    public function update(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id          = (int)$request->input('id', 0);
        $name        = (string)$request->input('name', '');
        $bundlePrice = (int)$request->input('bundle_price', 0);
        $status      = (string)$request->input('status', 'active');
        $threshold   = (int)$request->input('low_stock_threshold', 5);

        if ($id <= 0 || $name === '') {
            Session::set('package_error', 'اسم الباقة مطلوب');
            $this->redirect('/packages');
        }

        $existing = (new Package())->findByName($name);
        if ($existing !== null && (int)$existing['id'] !== $id) {
            Session::set('package_error', 'يوجد باقة بنفس الاسم بالفعل: ' . $name);
            $this->redirect('/packages');
        }

        $data = [
            'name'                => $name,
            'bundle_price'        => $bundlePrice,
            'status'              => $status,
            'low_stock_threshold' => $threshold,
        ];

        (new Package())->update($id, $data);
        $this->logAudit('package_update', 'تعديل باقة: ' . $name, $data);

        $this->redirect('/packages');
    }

    public function delete(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id = (int)$request->input('id', 0);
        if ($id > 0) {
            $pkg = (new Package())->find($id);
            (new Package())->delete($id);
            if ($pkg) {
                $this->logAudit('package_delete', 'حذف باقة: ' . ($pkg['name'] ?? ''));
            }
        }

        $this->redirect('/packages');
    }
}
