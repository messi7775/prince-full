<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Package — a card denomination sold by the network.
 */
final class Package extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM packages ORDER BY price DESC');
    }

    public function active(): array
    {
        return $this->fetchAll("SELECT * FROM packages WHERE status = 'active' ORDER BY price DESC");
    }

    public function count(): int
    {
        return (int)$this->fetchScalar('SELECT COUNT(*) FROM packages');
    }

    public function countActive(): int
    {
        return (int)$this->fetchScalar("SELECT COUNT(*) FROM packages WHERE status = 'active'");
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM packages WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('packages', $data);
    }

    public function update(int $id, array $data): int
    {
        $set = [];
        foreach (array_keys($data) as $col) {
            $set[] = $col . ' = :' . $col;
        }
        $data['id'] = $id;
        return $this->execute(
            'UPDATE packages SET ' . implode(', ', $set) . ' WHERE id = :id',
            $data
        );
    }

    public function delete(int $id): int
    {
        return $this->execute('DELETE FROM packages WHERE id = ?', [$id]);
    }

    /** Total inventory value grouped by package price (used on the dashboard). */
    public function inventoryValue(): float
    {
        return (float)$this->fetchScalar(
            'SELECT COALESCE(SUM(i.quantity * i.unit_price), 0) FROM inventory i'
        );
    }
}
