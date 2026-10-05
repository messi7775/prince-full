<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Payment — a collection from a distributor against their credit sales.
 * Supports full or partial payments (README §9).
 */
final class Payment extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT p.*, d.name AS distributor_name
               FROM payments p
          LEFT JOIN distributors d ON d.id = p.distributor_id
              ORDER BY p.created_at DESC'
        );
    }

    public function create(array $data): int
    {
        return $this->insert('payments', $data);
    }

    public function totalToday(): float
    {
        return (float)$this->fetchScalar(
            'SELECT COALESCE(SUM(amount), 0) FROM payments WHERE DATE(created_at) = CURDATE()'
        );
    }

    public function total(): float
    {
        return (float)$this->fetchScalar('SELECT COALESCE(SUM(amount), 0) FROM payments');
    }
}
