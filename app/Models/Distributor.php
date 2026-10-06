<?php
declare(strict_types=1);

namespace Models;

use Model;

final class Distributor extends Model
{
    /**
     * All distributors with derived financial columns:
     *   credit_total      = sum of credit sales totals (الآجل)
     *   installment_total = sum of installment sales full totals (المقسط)
     *   installment_paid   = sum of installment sales paid_amount (الدفعات الأولية للتقسيط)
     *   paid_total        = sum of standalone payments (تحصيلات لاحقة)
     *
     * المسدد = installment_paid + paid_total  (لا يدخل فيه المبيعات النقدية)
     * الرصيد المستحق = الآجل + المقسط − المسدد
     */
    public function all(): array
    {
        return $this->fetchAll(
            "SELECT d.*,
                    COALESCE((SELECT SUM(s.total) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'credit'), 0) AS credit_total,
                    COALESCE((SELECT SUM(s.total) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'installment'), 0) AS installment_total,
                    COALESCE((SELECT SUM(s.paid_amount) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'installment'), 0) AS installment_paid,
                    COALESCE((SELECT SUM(p.amount) FROM payments p WHERE p.distributor_id = d.id), 0) AS paid_total
               FROM distributors d
              ORDER BY d.name"
        );
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT * FROM distributors WHERE id = ?', [$id]);
    }

    public function create(array $data): int
    {
        return $this->insert('distributors', $data);
    }

    public function update(int $id, array $data): int
    {
        return $this->updateRow('distributors', $id, $data);
    }

    public function delete(int $id): int
    {
        return $this->deleteRow('distributors', $id);
    }

    public function count(): int
    {
        return $this->fetchInt('SELECT COUNT(*) FROM distributors');
    }

    /**
     * Total outstanding debt across all distributors.
     * الرصيد المستحق = الآجل + المقسط − المسدد
     * المسدد = الدفعات الأولية للتقسيط + التحصيلات اللاحقة
     */
    public function totalDebt(): int
    {
        return $this->fetchInt(
            "SELECT COALESCE(SUM(
                (SELECT COALESCE(SUM(s.total), 0) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'credit')
                +
                (SELECT COALESCE(SUM(s.total), 0) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'installment')
                -
                (SELECT COALESCE(SUM(s.paid_amount), 0) FROM sales s WHERE s.distributor_id = d.id AND s.payment_type = 'installment')
                -
                (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.distributor_id = d.id)
            ), 0)
               FROM distributors d
              HAVING SUM(1) > 0"
        );
    }

    /**
     * Outstanding balance for a single distributor.
     * الرصيد المستحق = الآجل + المقسط − المسدد
     */
    public function balance(int $id): int
    {
        $credit      = $this->fetchInt(
            "SELECT COALESCE(SUM(total), 0) FROM sales WHERE distributor_id = ? AND payment_type = 'credit'",
            [$id]
        );
        $installment = $this->fetchInt(
            "SELECT COALESCE(SUM(total), 0) FROM sales WHERE distributor_id = ? AND payment_type = 'installment'",
            [$id]
        );
        $installmentPaid = $this->fetchInt(
            "SELECT COALESCE(SUM(paid_amount), 0) FROM sales WHERE distributor_id = ? AND payment_type = 'installment'",
            [$id]
        );
        $paid = $this->fetchInt(
            'SELECT COALESCE(SUM(amount), 0) FROM payments WHERE distributor_id = ?',
            [$id]
        );
        return $credit + $installment - $installmentPaid - $paid;
    }
}
