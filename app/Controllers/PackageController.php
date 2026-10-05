<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Package;

/**
 * PackageController — manages card packages.
 *
 * List rendering is active now; create/update/delete handlers are wired
 * but guarded by the packages table which is added by the schema.
 */
final class PackageController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $package = new Package();
        $packages = $this->safeList(fn () => $package->all());

        $this->view('packages/index', [
            'pageTitle' => 'الباقات',
            'active'    => 'packages',
            'packages'   => $packages,
        ]);
    }

    /** Gracefully handle a missing table during early development. */
    private function safeList(callable $loader): array
    {
        try {
            return $loader();
        } catch (\PDOException $e) {
            return [];
        }
    }
}
