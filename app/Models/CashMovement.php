<?php
declare(strict_types=1);

namespace Models;

use Model;

final class CashMovement extends Model
{
    public const IN  = 'in';
    public const OUT = 'out';

    public function all(int $limit = 200): array
    {
        return $this->fetchAll(
            'SELECT * FROM cash_movements ORDER BY created_at DESC LIMIT ' . (int)$limit
        );
    }

    public function create(array $data): int
    {
        return $this->insert('cash_movements', $data);
    }

    public function balance(): int
    {
        $in  = $this->fetchInt("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'in'");
        $out = $this->fetchInt("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'out'");
        return $in - $out;
    }

    public function totalIn(): int
    {
        return $this->fetchInt("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'in'");
    }

    public function totalOut(): int
    {
        return $this->fetchInt("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'out'");
    }

    public function delete(int $id): int
    {
        return $this->deleteRow('cash_movements', $id);
    }
}
