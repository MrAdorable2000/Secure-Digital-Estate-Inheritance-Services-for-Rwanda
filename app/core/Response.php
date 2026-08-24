<?php
declare(strict_types=1);

class Response
{
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function redirect(string $url, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }

    public static function error(int $status, string $message = ''): void
    {
        http_response_code($status);
        $baseDir = defined('BASE_PATH') ? BASE_PATH : dirname(__DIR__, 2);
        $errorFile = $baseDir . '/app/views/errors/' . $status . '.php';
        if (@file_exists($errorFile)) {
            // Our error pages are completely self-contained with inline CSS
            // They don't need helper functions or external files
            include $errorFile;
        } else {
            // Dark-themed inline fallback
            $titles = [400=>'Bad Request',401=>'Unauthorized',403=>'Forbidden',404=>'Not Found',405=>'Method Not Allowed',419=>'Page Expired',429=>'Too Many Requests',500=>'Server Error',502=>'Bad Gateway',503=>'Service Unavailable'];
            $t = $titles[$status] ?? 'Error';
            $esc = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
            $msg = $message ?: 'An error occurred. Please try again.';
            echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Error ' . $status . ' | R-DEIP</title>';
            echo '<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
            echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">';
            echo '<style>*{margin:0;padding:0;box-sizing:border-box}body{font-family:\'Inter\',-apple-system,sans-serif;background:#0B1120;color:#F1F5F9;display:flex;align-items:center;justify-content:center;min-height:100vh;overflow:hidden;position:relative}';
            echo '.bg{position:fixed;inset:0;z-index:0;background:radial-gradient(ellipse 600px 600px at 20% 30%,rgba(0,105,62,.35),transparent),radial-gradient(ellipse 500px 500px at 80% 70%,rgba(229,161,0,.3),transparent);animation:d 20s ease-in-out infinite alternate}';
            echo '@keyframes d{to{transform:translate(-10px,-10px) rotate(.5deg)}}';
            echo '.c{text-align:center;padding:2rem;position:relative;z-index:10;animation:f .8s cubic-bezier(.16,1,.3,1) both}';
            echo '@keyframes f{0%{opacity:0;transform:translateY(30px) scale(.97);filter:blur(8px)}100%{opacity:1;transform:translateY(0) scale(1);filter:blur(0)}}';
            echo '.code{font-size:7rem;font-weight:800;line-height:1;background:linear-gradient(135deg,#EF4444,#F87171,#FCA5A5,#F87171,#EF4444);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;animation:s 3s ease-in-out infinite;background-size:200% 100%}';
            echo '@keyframes s{0%{background-position:100% 50%}50%{background-position:0% 50%}100%{background-position:100% 50%}}';
            echo 'h1{font-size:1.625rem;font-weight:700;margin:.5rem 0 .75rem}';
            echo 'p{color:#94A3B8;font-size:1rem;line-height:1.7;max-width:440px;margin:0 auto 2rem}';
            echo 'a{display:inline-block;padding:.8rem 1.75rem;border-radius:12px;text-decoration:none;font-weight:600;font-size:.9375rem;color:#fff;background:linear-gradient(135deg,#00693E,#00A651);box-shadow:0 4px 20px rgba(0,105,62,.35);transition:transform .2s,box-shadow .2s}';
            echo 'a:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,105,62,.4)}';
            echo 'footer{position:fixed;bottom:0;left:0;right:0;text-align:center;padding:1.25rem;color:#64748B;font-size:.8rem;z-index:10}';
            echo '</style></head><body><div class="bg"></div><div class="c"><div class="code">' . $status . '</div><h1>' . $esc($t) . '</h1><p>' . $esc($msg) . '</p><a href="/">Go to Homepage</a></div>';
            echo '<footer>&copy; ' . date('Y') . ' R-DEIP &mdash; Rwanda Digital Estate &amp; Inheritance Platform</footer>';
            echo '</body></html>';
        }
        exit;
    }

    public static function notFound(string $message = 'Page not found'): void
    {
        self::error(404, $message);
    }
}
