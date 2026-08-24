<?php
/**
 * R-DEIP — Entry Point
 * Rwanda Digital Estate & Inheritance Platform
 * 
 * BULLETPROOF: No PHP warnings will ever bleed into output.
 * All errors are captured cleanly.
 */

// ---- 0. Start output buffering IMMEDIATELY to catch any stray output ----
ob_start();

// ---- 1. Suppress display errors — ALWAYS log to file, never to screen ----
@ini_set('display_errors', '0');
@ini_set('display_startup_errors', '0');
@error_reporting(E_ALL);

// Custom error handler that logs but never displays
set_error_handler(function (int $severity, string $message, string $file, int $line) {
    $types = [E_ERROR=>'ERROR',E_WARNING=>'WARNING',E_PARSE=>'PARSE',E_NOTICE=>'NOTICE',E_DEPRECATED=>'DEPRECATED'];
    $type = $types[$severity] ?? 'ERROR-' . $severity;
    error_log("[R-DEIP $type] $message in $file:$line");
    // Never display — return true to suppress the default PHP handler
    return true;
});

// ---- 2. Load .env ----
$baseDir = dirname(__DIR__);
$envFile = $baseDir . '/.env';
if (@file_exists($envFile)) {
    try {
        $lines = @file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($lines)) {
            foreach ($lines as $line) {
                $line = trim($line);
                if (!is_string($line) || $line === '' || str_starts_with($line, '#')) continue;
                if (strpos($line, '=') === false) continue;
                $parts = explode('=', $line, 2);
                if (count($parts) !== 2) continue;
                $k = trim($parts[0]);
                $v = trim($parts[1], " \t\"'");
                @putenv("$k=$v");
                $_ENV[$k] = $v;
            }
        }
    } catch (Throwable) {}
}

// ---- 3. Determine environment ----
$env = getenv('APP_ENV') ?: 'production';
$debugMode = ($env === 'development');

@date_default_timezone_set('Africa/Kigali');

// ---- 4. Session ----
@ini_set('session.cookie_httponly', '1');
@ini_set('session.use_strict_mode', '1');
@ini_set('session.cookie_samesite', 'Lax');
@session_name('rdeip_session');
@session_start();

// ---- 5. Define base path ----
define('BASE_PATH', $baseDir);

// ---- 6. Exception handler ----
set_exception_handler(function (Throwable $e) use ($debugMode, $baseDir) {
    // Discard any stray output that was buffered
    if (ob_get_level()) ob_end_clean();

    error_log('[R-DEIP FATAL] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

    if ($debugMode) {
        http_response_code(500);
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Debug | R-DEIP</title>';
        echo '<style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:Consolas,Monaco,monospace;background:#1a1a2e;color:#e0e0e0;padding:2rem;line-height:1.6}';
        echo '.box{max-width:900px;margin:2rem auto;background:#16213e;border:1px solid #0f3460;border-radius:8px;padding:1.5rem}';
        echo 'h1{color:#e94560;margin-bottom:1rem;font-size:1.2rem}h2{color:#53d8fb;font-size:1rem;margin:1rem 0 .5rem}';
        echo 'p{margin:.5rem 0}code{background:rgba(255,255,255,.1);padding:.1rem .4rem;border-radius:3px;font-size:.9rem}';
        echo 'pre{background:#0a0a1a;padding:1rem;border-radius:6px;overflow-x:auto;font-size:.85rem;margin:1rem 0}';
        echo '.type{color:#e94560;font-weight:700}.file{color:#53d8fb}.msg{color:#f8c291}</style></head><body>';
        echo '<div class="box">';
        echo '<h1>Debug Error</h1>';
        echo '<p><span class="type">' . htmlspecialchars(get_class($e)) . '</span></p>';
        echo '<p class="msg">' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p>in <span class="file">' . htmlspecialchars($e->getFile()) . '</span> line <code>' . $e->getLine() . '</code></p>';
        echo '<h2>Stack Trace</h2><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div></body></html>';
    } else {
        http_response_code(500);
        $errorFile = $baseDir . '/app/views/errors/500.php';
        $message = 'An unexpected error occurred. Please try again later.';
        if (@file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>500</title></head>';
            echo '<body style="font-family:sans-serif;text-align:center;padding:50px;background:#0B1120;color:#F1F5F9">';
            echo '<h1 style="font-size:4rem">500</h1><p>Something went wrong.</p>';
            echo '<a href="/" style="color:#00A651">Go Home</a></body></html>';
        }
    }
    exit;
});

// ---- 7. Config ----
try {
    $configFile = BASE_PATH . '/config/config.php';
    $GLOBALS['config'] = @file_exists($configFile) ? require $configFile : [];
} catch (Throwable) {
    $GLOBALS['config'] = [];
}

// ---- 8. Core classes (order matters) ----
$coreFiles = [
    '/app/helpers/helpers.php',
    '/app/core/Database.php',
    '/app/core/Request.php',
    '/app/core/Response.php',
    '/app/core/CSRF.php',
    '/app/core/Flash.php',
    '/app/core/Validator.php',
    '/app/core/Middleware.php',
    '/app/core/Router.php',
    '/app/core/Controller.php',
    '/app/core/Auth.php',
    '/app/core/App.php',
];
foreach ($coreFiles as $file) {
    $path = BASE_PATH . $file;
    if (@file_exists($path)) {
        try { require $path; } catch (Throwable $e) {
            error_log('[R-DEIP] Failed to load ' . $file . ': ' . $e->getMessage());
        }
    }
}

// ---- 9. Middleware ----
$middlewareFiles = [
    '/app/middleware/AuthMiddleware.php',
    '/app/middleware/GuestMiddleware.php',
    '/app/middleware/RBACMiddleware.php',
];
foreach ($middlewareFiles as $file) {
    $path = BASE_PATH . $file;
    if (@file_exists($path)) {
        try { require $path; } catch (Throwable $e) {
            error_log('[R-DEIP] Failed to load ' . $file . ': ' . $e->getMessage());
        }
    }
}

// ---- 10. Controllers ----
$controllerFiles = [
    '/app/controllers/HomeController.php',
    '/app/controllers/AuthController.php',
    '/app/controllers/DashboardController.php',
    '/app/controllers/UserController.php',
    '/app/controllers/ProfileController.php',
    '/app/controllers/AuditController.php',
];
foreach ($controllerFiles as $file) {
    $path = BASE_PATH . $file;
    if (@file_exists($path)) {
        try { require $path; } catch (Throwable $e) {
            error_log('[R-DEIP] Failed to load ' . $file . ': ' . $e->getMessage());
        }
    }
}

// ---- 11. Boot Auth (graceful) ----
try { Auth::getInstance(); } catch (Throwable) {}

// ---- 12. Normalize REQUEST_URI ----
try {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $subdir = dirname(dirname($scriptName));
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    if ($subdir !== '/' && $subdir !== '\\' && str_starts_with($requestUri, $subdir)) {
        $_SERVER['REQUEST_URI'] = '/' . ltrim(substr($requestUri, strlen($subdir)), '/');
    }
} catch (Throwable) {}

// ---- 13. Routes ----
try {
    $router = new Router();
    $routesFile = BASE_PATH . '/routes/web.php';
    if (@file_exists($routesFile)) {
        require $routesFile;
    }
} catch (Throwable $e) {
    error_log('[R-DEIP] Route loading failed: ' . $e->getMessage());
    if (ob_get_level()) ob_end_clean();
    http_response_code(500);
    $errorFile = BASE_PATH . '/app/views/errors/500.php';
    if (@file_exists($errorFile)) {
        $message = 'Failed to load application routes. Check server logs.';
        include $errorFile;
    }
    exit;
}

// ---- 14. Dispatch ----
try {
    $app = App::getInstance($GLOBALS['config'] ?? []);
    $app->router = $router;
    $app->run();
} catch (Throwable $e) {
    error_log('[R-DEIP] ' . get_class($e) . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    if (ob_get_level()) ob_end_clean();
    if ($debugMode) {
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Debug | R-DEIP</title>';
        echo '<style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:Consolas,Monaco,monospace;background:#1a1a2e;color:#e0e0e0;padding:2rem;line-height:1.6}';
        echo '.box{max-width:900px;margin:2rem auto;background:#16213e;border:1px solid #0f3460;border-radius:8px;padding:1.5rem}';
        echo 'h1{color:#e94560;margin-bottom:1rem;font-size:1.2rem}h2{color:#53d8fb;font-size:1rem;margin:1rem 0 .5rem}';
        echo 'p{margin:.5rem 0}code{background:rgba(255,255,255,.1);padding:.1rem .4rem;border-radius:3px;font-size:.9rem}';
        echo 'pre{background:#0a0a1a;padding:1rem;border-radius:6px;overflow-x:auto;font-size:.85rem;margin:1rem 0}';
        echo '.type{color:#e94560;font-weight:700}.file{color:#53d8fb}.msg{color:#f8c291}</style></head><body>';
        echo '<div class="box">';
        echo '<h1>Debug Error</h1>';
        echo '<p><span class="type">' . htmlspecialchars(get_class($e)) . '</span></p>';
        echo '<p class="msg">' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<p>in <span class="file">' . htmlspecialchars($e->getFile()) . '</span> line <code>' . $e->getLine() . '</code></p>';
        echo '<h2>Stack Trace</h2><pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
        echo '</div></body></html>';
    } else {
        http_response_code(500);
        $errorFile = BASE_PATH . '/app/views/errors/500.php';
        $message = 'An unexpected error occurred. Please try again later.';
        if (@file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>500</title></head>';
            echo '<body style="font-family:sans-serif;text-align:center;padding:50px;background:#0B1120;color:#F1F5F9">';
            echo '<h1 style="font-size:4rem">500</h1><p>Something went wrong.</p>';
            echo '<a href="/" style="color:#00A651">Go Home</a></body></html>';
        }
    }
    exit;
}
