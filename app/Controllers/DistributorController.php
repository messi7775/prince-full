<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Distributor;

final class DistributorController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $distributor = new Distributor();
        $distributors = $this->safeList(fn () => $distributor->all());

        $this->view('distributors/index', [
            'pageTitle'   => 'الموزعون',
            'active'      => 'distributors',
            'distributors'=> $distributors,
        ]);
    }

    private function safeList(callable $loader): array
    {
        try {
            return $loader();
        } catch (\PDOException $e) {
            return [];
        }
    }
}
