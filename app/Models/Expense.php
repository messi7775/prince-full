<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Expense — operating costs (electricity, internet, maintenance, ...).
 */
final class Expense extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM expenses ORDER BY created_at DESC');
    }

    public function create(array $data): int
    {
        return $this->insert('expenses', $data);
    }

    public function total(): float
    {
        return (float)$this->fetchScalar('SELECT COALESCE(SUM(amount), 0) FROM expenses');
    }
}
