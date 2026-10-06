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
              ORDER BY i.created_at DESC'
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM inventory WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('inventory', $data);
    }

    public function delete(int $id): int
    {
        return $this->deleteRow('inventory', $id);
    }

    /** Get active batches for a package (for sale deduction). */
    public function activeBatches(int $packageId): array
    {
        return $this->fetchAll(
            "SELECT id, quantity FROM inventory WHERE package_id = ? AND status = 'active' ORDER BY created_at ASC",
            [$packageId]
        );
    }

    /** Deduct bundles from a batch. */
    public function deductBatch(int $batchId, int $newQuantity): void
    {
        if ($newQuantity <= 0) {
            $this->execute("UPDATE inventory SET quantity = 0, status = 'closed' WHERE id = ?", [$batchId]);
        } else {
            $this->execute("UPDATE inventory SET quantity = ? WHERE id = ?", [$newQuantity, $batchId]);
        }
    }

    /** Total number of bundles in active stock. */
    public function totalBundles(): int
    {
        return $this->fetchInt("SELECT COALESCE(SUM(quantity), 0) FROM inventory WHERE status = 'active'");
    }

    /** Stock grouped by package. */
    public function stockByPackage(): array
    {
        return $this->fetchAll(
            "SELECT p.id, p.name, p.bundle_price,
                    COALESCE(SUM(i.quantity), 0) AS bundles,
                    COALESCE(SUM(i.quantity * i.bundle_price), 0) AS value
               FROM packages p
          LEFT JOIN inventory i ON i.package_id = p.id AND i.status = 'active'
           GROUP BY p.id, p.name, p.bundle_price
           ORDER BY p.bundle_price DESC"
        );
    }
}
