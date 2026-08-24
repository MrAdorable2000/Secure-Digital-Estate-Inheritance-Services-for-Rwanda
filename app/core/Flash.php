<?php
declare(strict_types=1);

class Flash
{
    private const KEY = '_flash_messages';

    public static function set(string $type, string $message): void
    {
        $_SESSION[self::KEY][$type][] = $message;
    }

    public static function get(string $type): array
    {
        $messages = $_SESSION[self::KEY][$type] ?? [];
        unset($_SESSION[self::KEY][$type]);
        return $messages;
    }

    public static function has(string $type): bool
    {
        return !empty($_SESSION[self::KEY][$type]);
    }

    public static function all(): array
    {
        $messages = $_SESSION[self::KEY] ?? [];
        unset($_SESSION[self::KEY]);
        return $messages;
    }

    public static function setInputs(array $inputs): void
    {
        $_SESSION['_old_input'] = $inputs;
    }

    public static function old(string $key, $default = '')
    {
        $val = $_SESSION['_old_input'][$key] ?? $default;
        return is_string($val) ? $val : $default;
    }

    public static function clearOld(): void
    {
        unset($_SESSION['_old_input']);
    }
}
