<?php
declare(strict_types=1);

namespace Models;

use Model;

/**
 * Admin — the single authenticated user account.
 */
final class Admin extends Model
{
    /** Find an admin by email, returning id/email/password_hash or null. */
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT id, email, password_hash FROM admins WHERE email = ? LIMIT 1',
            [$email]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT id, email FROM admins WHERE id = ? LIMIT 1',
            [$id]
        );
    }
}
