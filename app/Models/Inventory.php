<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Inventory — stock batches. The unit_price is captured at creation to
 * preserve historical pricing per README §15.
 */
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

    /** Total quantity in stock. */
    public function totalQuantity(): int
    {
        return (int)$this->fetchScalar('SELECT COALESCE(SUM(quantity), 0) FROM inventory');
    }

    /** Stock grouped by package (for the dashboard inventory status panel). */
    public function stockByPackage(): array
    {
        return $this->fetchAll(
            "SELECT p.name, p.price,
                    COALESCE(SUM(i.quantity), 0) AS quantity,
                    COALESCE(SUM(i.quantity * i.unit_price), 0) AS value
               FROM packages p
          LEFT JOIN inventory i ON i.package_id = p.id AND i.status = 'active'
           GROUP BY p.id, p.name, p.price
           ORDER BY p.price DESC"
        );
    }
}
