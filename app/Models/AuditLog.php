<?php
declare(strict_types=1);

namespace Models;

use Model;
use Session;

/**
 * AuditLog — records important operations (login, sales, payments, ...).
 */
final class AuditLog extends Model
{
    public function all(int $limit = 50): array
    {
        return $this->fetchAll(
            'SELECT * FROM audit_logs ORDER BY created_at DESC LIMIT ' . (int)$limit
        );
    }

    public function create(array $data): int
    {
        return $this->insert('audit_logs', $data);
    }

    public function log(string $action, string $description = '', array $context = []): void
    {
        $this->create([
            'admin_id'    => Session::adminId(),
            'action'      => $action,
            'description' => $description,
            'context'     => json_encode($context, JSON_UNESCAPED_UNICODE),
        ]);
    }
}
