<?php
declare(strict_types=1);

namespace Models;

use Model;

final class Inventory extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT i.*, p.name AS package_name
               FROM inventory i
               JOIN packages p ON p.id = i.package_id
              ORDER BY p.bundle_price DESC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM inventory WHERE id = ?', [$id]);
    }

    public function findByPackageId(int $packageId): ?array
    {
        return $this->fetchOne('SELECT * FROM inventory WHERE package_id = ?', [$packageId]);
    }

    public function create(array $data): int
    {
        return $this->insert('inventory', $data);
    }

    /**
     * Find an inventory row by package_id, or create one with the given
     * default quantity and bundle price (one row per package).
     */
    public function findOrCreateByPackage(int $packageId, int $quantity, int $bundlePrice): array
    {
        $row = $this->findByPackageId($packageId);
        if ($row !== null) {
            return $row;
        }

        $this->insert('inventory', [
            'package_id'   => $packageId,
            'quantity'     => $quantity,
            'bundle_price' => $bundlePrice,
            'status'       => 'active',
            'note'         => null,
        ]);

        return $this->findByPackageId($packageId);
    }

    /** إضافة — add quantity to an inventory row. */
    public function addQuantity(int $inventoryId, int $amount): bool
    {
        return $this->execute(
            "UPDATE inventory SET quantity = quantity + ?, status = 'active' WHERE id = ?",
            [$amount, $inventoryId]
        ) > 0;
    }

    /** تعديل — replace the quantity on an inventory row. */
    public function setQuantity(int $inventoryId, int $quantity): bool
    {
        $status = $quantity > 0 ? 'active' : 'closed';
        return $this->execute(
            'UPDATE inventory SET quantity = ?, status = ? WHERE id = ?',
            [$quantity, $status, $inventoryId]
        ) > 0;
    }

    public function delete(int $id): int
    {
        return $this->deleteRow('inventory', $id);
    }

    // ── Inventory movement log ─────────────────────────────────────────────

    public function logMovement(array $data): int
    {
        return $this->insert('inventory_movements', $data);
    }

    public function movements(int $limit = 100): array
    {
        return $this->fetchAll(
            'SELECT m.*, p.name AS package_name
               FROM inventory_movements m
               JOIN packages p ON p.id = m.package_id
              ORDER BY m.created_at DESC
              LIMIT ' . (int)$limit
        );
    }

    // ── Sale deduction (compatible with single-row-per-package model) ──────

    /** Get active inventory rows for a package (for sale deduction). */
    public function activeBatches(int $packageId): array
    {
        return $this->fetchAll(
            "SELECT id, quantity FROM inventory WHERE package_id = ? AND status = 'active' AND quantity > 0 ORDER BY created_at ASC",
            [$packageId]
        );
    }

    /** Deduct bundles from a row. */
    public function deductBatch(int $batchId, int $newQuantity): void
    {
        if ($newQuantity <= 0) {
            $this->execute("UPDATE inventory SET quantity = 0, status = 'closed' WHERE id = ?", [$batchId]);
        } else {
            $this->execute("UPDATE inventory SET quantity = ? WHERE id = ?", [$newQuantity, $batchId]);
        }
    }

    // ── Dashboard / reports helpers ─────────────────────────────────────────

    /** Total number of bundles in active stock. */
    public function totalBundles(): int
    {
        return $this->fetchInt("SELECT COALESCE(SUM(quantity), 0) FROM inventory WHERE status = 'active'");
    }

    /** Stock grouped by package (with low-stock threshold). */
    public function stockByPackage(): array
    {
        return $this->fetchAll(
            "SELECT p.id, p.name, p.bundle_price, p.low_stock_threshold,
                    COALESCE(i.quantity, 0) AS bundles,
                    COALESCE(i.quantity * i.bundle_price, 0) AS value
               FROM packages p
          LEFT JOIN inventory i ON i.package_id = p.id AND i.status = 'active'
           GROUP BY p.id, p.name, p.bundle_price, p.low_stock_threshold
           ORDER BY p.bundle_price DESC"
        );
    }

    /** Packages whose active stock is at or below their low-stock threshold. */
    public function lowStock(): array
    {
        return $this->fetchAll(
            "SELECT p.id, p.name, p.bundle_price, p.low_stock_threshold,
                    COALESCE(i.quantity, 0) AS bundles
               FROM packages p
          LEFT JOIN inventory i ON i.package_id = p.id AND i.status = 'active'
              WHERE p.status = 'active'
           GROUP BY p.id, p.name, p.bundle_price, p.low_stock_threshold
             HAVING bundles <= p.low_stock_threshold
           ORDER BY bundles ASC"
        );
    }
}
