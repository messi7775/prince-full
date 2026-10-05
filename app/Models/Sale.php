<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Sale — a sales transaction. Stores unit_price per item so the value of
 * past sales never changes when a package price is edited (README §15).
 */
final class Sale extends Model
{
    public function all(): array
    {
        return $this->fetchAll(
            'SELECT s.*, d.name AS distributor_name
               FROM sales s
          LEFT JOIN distributors d ON d.id = s.distributor_id
              ORDER BY s.created_at DESC'
        );
    }

    public function create(array $data): int
    {
        return $this->insert('sales', $data);
    }

    /** Sum of today's cash sales total. */
    public function totalToday(): float
    {
        return (float)$this->fetchScalar(
            "SELECT COALESCE(SUM(total), 0) FROM sales
              WHERE DATE(created_at) = CURDATE() AND payment_type = 'cash'"
        );
    }

    /** Sum of this month's sales total. */
    public function totalThisMonth(): float
    {
        return (float)$this->fetchScalar(
            "SELECT COALESCE(SUM(total), 0) FROM sales
              WHERE YEAR(created_at) = YEAR(CURDATE())
                AND MONTH(created_at) = MONTH(CURDATE())"
        );
    }

    /** Total credit (آجل) sales — the principal of distributor debt. */
    public function totalCredit(): float
    {
        return (float)$this->fetchScalar(
            "SELECT COALESCE(SUM(total), 0) FROM sales WHERE payment_type = 'credit'"
        );
    }

    public function recent(int $limit = 10): array
    {
        return $this->fetchAll(
            'SELECT s.*, d.name AS distributor_name
               FROM sales s
          LEFT JOIN distributors d ON d.id = s.distributor_id
              ORDER BY s.created_at DESC
              LIMIT ' . (int)$limit
        );
    }
}
