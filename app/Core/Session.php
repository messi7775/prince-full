<?php
declare(strict_types=1);

/**
 * Session — thin wrapper around PHP sessions with typed accessors
 * and a one-call login/logout/regenerate flow.
 */
final class Session
{
    public static function start(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function has(string $key): bool
    {
        self::start();
        return array_key_exists($key, $_SESSION);
    }

    public static function forget(string $key): void
    {
        self::start();
        unset($_SESSION[$key]);
    }

    /** Set a one-time flash message (read and cleared via flashGet). */
    public static function flash(string $key, mixed $value): void
    {
        self::start();
        $_SESSION['_flash'][$key] = $value;
    }

    /** Read and immediately clear a flash message. Returns null if not set. */
    public static function flashGet(string $key): mixed
    {
        self::start();
        $value = $_SESSION['_flash'][$key] ?? null;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    /** Mark the current admin as logged in (regenerates the session id). */
    public static function login(int $adminId, string $email): void
    {
        self::start();
        session_regenerate_id(true);
        $_SESSION['admin_id']   = $adminId;
        $_SESSION['admin_email'] = $email;
    }

    /** Destroy the session entirely and clear the login cookie. */
    public static function logout(): void
    {
        self::start();
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
    }

    public static function isAuthenticated(): bool
    {
        return self::has('admin_id');
    }

    public static function adminId(): ?int
    {
        $id = self::get('admin_id');
        return $id === null ? null : (int)$id;
    }

    public static function adminEmail(): ?string
    {
        $email = self::get('admin_email');
        return $email === null ? null : (string)$email;
    }
}
