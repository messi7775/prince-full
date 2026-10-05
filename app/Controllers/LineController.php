<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Models\Line;

final class LineController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();

        $line = new Line();
        $lines = $this->safeList(fn () => $line->all());

        $this->view('lines/index', [
            'pageTitle' => 'الخطوط',
            'active'    => 'lines',
            'lines'     => $lines,
        ]);
    }

    public function payments(Request $request): void
    {
        $this->requireAuth();
        $this->view('shared/placeholder', [
            'pageTitle' => 'دفعات الخطوط',
            'active'    => 'line-payments',
            'hint'      => 'سجل دفعات الخطوط يظهر هنا.',
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
