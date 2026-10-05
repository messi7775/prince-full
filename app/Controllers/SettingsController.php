<?php
declare(strict_types=1);

namespace Controllers;

use Controller;
use Request;
use Session;
use Models\AuditLog;

final class SettingsController extends Controller
{
    public function index(Request $request): void
    {
        $this->requireAuth();
        $this->view('settings/index', [
            'pageTitle' => 'الإعدادات',
            'active'    => 'settings',
            'adminEmail'=> Session::adminEmail(),
        ]);
    }

    public function audit(Request $request): void
    {
        $this->requireAuth();
        $log = new AuditLog();
        $entries = $this->safeList(fn () => $log->all());

        $this->view('settings/audit', [
            'pageTitle' => 'سجل التدقيق',
            'active'    => 'audit',
            'entries'   => $entries,
        ]);
    }

    public function backup(Request $request): void
    {
        $this->requireAuth();
        $this->view('shared/placeholder', [
            'pageTitle' => 'النسخ الاحتياطي',
            'active'    => 'backup',
            'hint'      => 'إنشاء واستعادة نسخ قاعدة البيانات.',
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
