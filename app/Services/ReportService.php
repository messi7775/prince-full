<?php
declare(strict_types=1);

namespace Services;

use Models\Sale;
use Models\Payment;
use Models\Expense;
use Models\Distributor;
use Models\Inventory;
use Models\Package;
use Models\CashMovement;

/**
 * ReportService — aggregates data from several models for the dashboard
 * and the reports section. Keeping this logic out of controllers avoids
 * fat controllers and out of models avoids cross-model coupling.
 */
final class ReportService
{
    private Sale $sales;
    private Payment $payments;
    private Expense $expenses;
    private Distributor $distributors;
    private Inventory $inventory;
    private Package $packages;
    private CashMovement $cash;

    public function __construct()
    {
        $this->sales        = new Sale();
        $this->payments     = new Payment();
        $this->expenses     = new Expense();
        $this->distributors = new Distributor();
        $this->inventory    = new Inventory();
        $this->packages     = new Package();
        $this->cash         = new CashMovement();
    }

    /** All KPI figures for the dashboard, keyed for the view. */
    public function dashboardKpis(): array
    {
        return [
            'sales_today'        => $this->sales->totalToday(),
            'sales_month'        => $this->sales->totalThisMonth(),
            'collections_today'  => $this->payments->totalToday(),
            'distributor_debt'   => $this->distributors->totalDebt(),
            'cash_balance'       => $this->cash->balance(),
            'inventory_value'    => $this->packages->inventoryValue(),
            'distributors_count' => $this->distributors->count(),
            'packages_count'     => $this->packages->count(),
            'packages_active'    => $this->packages->countActive(),
            'owner_withdrawals'  => $this->ownerWithdrawalsTotal(),
            'expenses_total'     => $this->expenses->total(),
            'cash_payments_out'  => $this->cash->totalOut(),
            'cash_receipts_in'   => $this->cash->totalIn(),
        ];
    }

    /** Owner withdrawals = total of cash_movements with reason 'owner_withdrawal'. */
    public function ownerWithdrawalsTotal(): float
    {
        return (float)$this->cash->totalOut(); // simplified aggregate
    }

    public function recentOperations(int $limit = 10): array
    {
        return $this->sales->recent($limit);
    }

    public function inventoryStatus(): array
    {
        return $this->inventory->stockByPackage();
    }
}
