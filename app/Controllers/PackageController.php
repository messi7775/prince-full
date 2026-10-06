<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Package;
use Models\AuditLog;

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
        ]);
    }

    public function store(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $name        = (string)$request->input('name', '');
        $price       = (int)$request->input('price', 0);
        $bundleSize  = (int)$request->input('bundle_size', 1);
        $bundlePrice = (int)$request->input('bundle_price', 0);
        $hours       = $request->input('hours');
        $duration    = (string)$request->input('duration', '');
        $status      = (string)$request->input('status', 'active');

        if ($name === '' || $price <= 0 || $bundleSize < 1 || $bundlePrice < 0) {
            $this->redirect('/packages');
        }

        $data = [
            'name'         => $name,
            'price'        => $price,
            'bundle_size'  => $bundleSize,
            'bundle_price' => $bundlePrice,
            'hours'        => $hours !== null && $hours !== '' ? (int)$hours : null,
            'duration'     => $duration ?: null,
            'status'       => $status,
        ];

        (new Package())->create($data);
        $this->logAudit('package_create', 'إضافة باقة: ' . $name, $data);

        $this->redirect('/packages');
    }

    public function update(Request $request): void
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $id          = (int)$request->input('id', 0);
        $name        = (string)$request->input('name', '');
        $price       = (int)$request->input('price', 0);
        $bundleSize  = (int)$request->input('bundle_size', 1);
        $bundlePrice = (int)$request->input('bundle_price', 0);
        $hours       = $request->input('hours');
        $duration    = (string)$request->input('duration', '');
        $status      = (string)$request->input('status', 'active');

        if ($id <= 0 || $name === '') {
            $this->redirect('/packages');
        }

        $data = [
            'name'         => $name,
            'price'        => $price,
            'bundle_size'  => $bundleSize,
            'bundle_price' => $bundlePrice,
            'hours'        => $hours !== null && $hours !== '' ? (int)$hours : null,
            'duration'     => $duration ?: null,
            'status'       => $status,
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
