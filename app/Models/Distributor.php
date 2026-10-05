<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Distributor — a reseller. The balance is derived from movements:
 *   balance = total credit sales - total payments
 * never stored (README §14).
 */
final class Distributor extends Model
{
    public function all(): array
    {
        return $this->fetchAll('SELECT * FROM distributors ORDER BY name');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM distributors WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('distributors', $data);
    }

    public function count(): int
    {
        return (int)$this->fetchScalar('SELECT COUNT(*) FROM distributors');
    }

    /** Total outstanding debt across all distributors. */
    public function totalDebt(): float
    {
        return (float)$this->fetchScalar(
            "SELECT COALESCE(SUM(
                (SELECT COALESCE(SUM(total), 0) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'credit')
                -
                (SELECT COALESCE(SUM(amount), 0) FROM payments p WHERE p.distributor_id = d.id)
            ), 0)
               FROM distributors d
              HAVING SUM(...) > 0"
        );
    }

    /** One distributor's current balance (credit sales − payments). */
    public function balance(int $id): float
    {
        $sales = (float)$this->fetchScalar(
            "SELECT COALESCE(SUM(total), 0) FROM sales WHERE distributor_id = ? AND payment_type = 'credit'",
            [$id]
        );
        $paid = (float)$this->fetchScalar(
            'SELECT COALESCE(SUM(amount), 0) FROM payments WHERE distributor_id = ?',
            [$id]
        );
        return $sales - $paid;
    }
}
