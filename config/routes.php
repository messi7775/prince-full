<?php
declare(strict_types=1);

/**
 * Route table — method => [path => [Controller, action]].
 *
 * The Router currently resolves static paths only; this keeps the URL
 * scheme simple and matches the README's flat section links.
 */
return [

    'GET' => [
        '/'                 => ['Controllers\\AuthController', 'root'],
        '/login'            => ['Controllers\\AuthController', 'login'],
        '/logout'           => ['Controllers\\AuthController', 'logout'],
        '/dashboard'        => ['Controllers\\DashboardController', 'index'],
        '/packages'         => ['Controllers\\PackageController', 'index'],
        '/inventory'        => ['Controllers\\InventoryController', 'index'],
        '/distributors'     => ['Controllers\\DistributorController', 'index'],
        '/sales'            => ['Controllers\\SaleController', 'index'],
        '/payments'         => ['Controllers\\PaymentController', 'index'],
        '/lines'            => ['Controllers\\LineController', 'index'],
        '/line-payments'    => ['Controllers\\LineController', 'payments'],
        '/expenses'         => ['Controllers\\ExpenseController', 'index'],
        '/owner-withdrawals'=> ['Controllers\\CashController', 'withdrawals'],
        '/cash'             => ['Controllers\\CashController', 'index'],
        '/reports'          => ['Controllers\\ReportController', 'index'],
        '/search'           => ['Controllers\\ReportController', 'search'],
        '/audit'            => ['Controllers\\SettingsController', 'audit'],
        '/settings'         => ['Controllers\\SettingsController', 'index'],
        '/backup'           => ['Controllers\\SettingsController', 'backup'],
    ],
];
