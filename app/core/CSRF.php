<?php
declare(strict_types=1);

class CSRF
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf_token']) || empty($_SESSION['_csrf_time']) || time() > $_SESSION['_csrf_time']) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['_csrf_time'] = time() + 7200;
        }
        return $_SESSION['_csrf_token'];
    }

    public static function verify(?string $token = null): bool
    {
        $token = $token ?? $_POST['_csrf_token'] ?? null;
        if (!$token || empty($_SESSION['_csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['_csrf_token'], $token);
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function rotate(): void
    {
        unset($_SESSION['_csrf_token'], $_SESSION['_csrf_time']);
        self::token();
    }
}
