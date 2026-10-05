<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Inventory;

final class InventoryController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $inventory = new Inventory();
        $items = $this->safeList(fn () => $inventory->all());

        $this->view('inventory/index', [
            'pageTitle' => 'المخزون',
            'active'    => 'inventory',
            'items'     => $items,
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
