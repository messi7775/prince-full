<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Line — a telecom line/account with its own balance and payment history.
 */
final class Line extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM lines ORDER BY created_at DESC');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM lines WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('lines', $data);
    }

    public function count(): int
    {
        return (int)$this->fetchScalar('SELECT COUNT(*) FROM lines');
    }
}
