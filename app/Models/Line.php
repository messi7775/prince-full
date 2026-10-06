<?php
declare(strict_types=1);

namespace Models;

use Model;

final class Line extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            "SELECT l.*,
                    COALESCE((SELECT SUM(CASE WHEN lp.direction = 'in' THEN lp.amount ELSE -lp.amount END) FROM line_payments lp WHERE lp.line_id = l.id), 0) AS balance
               FROM `lines` l
              ORDER BY l.created_at DESC"
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM `lines` WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('`lines`', $data);
    }

    public function delete(int $id): int
    {
        return $this->deleteRow('`lines`', $id);
    }

    public function count(): int
    {
        return $this->fetchInt('SELECT COUNT(*) FROM `lines`');
    }

    public function payments(int $lineId): array
    {
        return $this->fetchAll(
            'SELECT * FROM line_payments WHERE line_id = ? ORDER BY created_at DESC',
            [$lineId]
        );
    }

    public function allPayments(): array
    {
        return $this->fetchAll(
            "SELECT lp.*, l.name AS line_name
               FROM line_payments lp
               JOIN `lines` l ON l.id = lp.line_id
              ORDER BY lp.created_at DESC"
        );
    }

    public function createPayment(array $data): int
    {
        return $this->insert('line_payments', $data);
    }

    public function deletePayment(int $id): int
    {
        return $this->deleteRow('line_payments', $id);
    }

    public function totalPaymentsOut(): int
    {
        return $this->fetchInt("SELECT COALESCE(SUM(amount), 0) FROM line_payments WHERE direction = 'out'");
    }

    public function totalPaymentsIn(): int
    {
        return $this->fetchInt("SELECT COALESCE(SUM(amount), 0) FROM line_payments WHERE direction = 'in'");
    }
}
