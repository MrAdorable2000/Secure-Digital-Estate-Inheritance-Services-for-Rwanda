<?php
declare(strict_types=1);

// Load environment variables
$envFile = dirname(__DIR__) . '/.env';
$env = [];
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value, " \t\"'");
        putenv(trim($key) . '=' . trim($value, " \t\"'"));
    }
}

return [
    'app' => [
        'name'        => $env['APP_NAME'] ?? 'R-DEIP',
        'env'         => $env['APP_ENV'] ?? 'production',
        'url'         => $env['APP_URL'] ?? '',
        'locale'      => $env['APP_LOCALE'] ?? 'en',
        'timezone'    => 'Africa/Kigali',
        'debug'       => ($env['APP_ENV'] ?? 'production') === 'development',
    ],
    'database' => [
        'host'    => $env['DB_HOST'] ?? '127.0.0.1',
        'port'    => (int)($env['DB_PORT'] ?? 3306),
        'name'    => $env['DB_NAME'] ?? 'rdeip',
        'user'    => $env['DB_USER'] ?? 'root',
        'pass'    => $env['DB_PASS'] ?? '',
        'charset' => 'utf8mb4',
    ],
    'session' => [
        'name'     => 'rdeip_session',
        'lifetime' => 7200,
        'timeout'  => 3600,
    ],
    'security' => [
        'csrf_ttl'         => 7200,
        'max_login_attempts' => 5,
        'lockout_minutes'  => 15,
    ],
    'upload' => [
        'max_size'     => 2 * 1024 * 1024, // 2MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'path'         => 'uploads/profiles/',
    ],
];
