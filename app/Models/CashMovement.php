<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * CashMovement — every cash in/out event (sales, collections, expenses,
 * owner withdrawals, line payments, ...). The dashboard cash balance is
 * the net of these movements (README §14).
 */
final class CashMovement extends Model
{
    public const IN  = 'in';
    public const OUT = 'out';

    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM cash_movements ORDER BY created_at DESC');
    }

    public function create(array $data): int
    {
        return $this->insert('cash_movements', $data);
    }

    /** Net cash balance = sum(in) - sum(out). */
    public function balance(): float
    {
        $in  = (float)$this->fetchScalar("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'in'");
        $out = (float)$this->fetchScalar("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'out'");
        return $in - $out;
    }

    public function totalIn(): float
    {
        return (float)$this->fetchScalar("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'in'");
    }

    public function totalOut(): float
    {
        return (float)$this->fetchScalar("SELECT COALESCE(SUM(amount), 0) FROM cash_movements WHERE direction = 'out'");
    }
}
