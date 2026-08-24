<?php
declare(strict_types=1);

// ---- Configuration ----
if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        $segs = explode('.', $key);
        $val = $GLOBALS['config'] ?? [];
        foreach ($segs as $s) {
            if (!is_array($val) || !array_key_exists($s, $val)) return $default;
            $val = $val[$s];
        }
        return $val;
    }
}

// ---- Output Escaping ----
if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

// ---- URLs ----
if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        $base = rtrim(config('app.url', ''), '/');
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        $base = rtrim(config('app.url', ''), '/');
        return $base . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }
}

// ---- CSRF ----
if (!function_exists('csrf_token')) {
    function csrf_token(): string { return CSRF::token(); }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string { return CSRF::field(); }
}

// ---- Auth Shortcuts (bulletproof — never throws) ----
if (!function_exists('auth')) {
    function auth(): ?Auth {
        try { return Auth::getInstance(); }
        catch (Throwable) { return null; }
    }
}

if (!function_exists('user')) {
    function user(): ?array {
        try {
            $a = Auth::getInstance();
            return $a ? $a->user() : null;
        } catch (Throwable) { return null; }
    }
}

if (!function_exists('can')) {
    function can(string $permission): bool {
        try {
            $a = Auth::getInstance();
            return $a ? $a->can($permission) : false;
        } catch (Throwable) { return false; }
    }
}

if (!function_exists('has_role')) {
    function has_role(string ...$roles): bool {
        try {
            $a = Auth::getInstance();
            return $a ? $a->hasRole(...$roles) : false;
        } catch (Throwable) { return false; }
    }
}

// ---- Flash Messages ----
if (!function_exists('flash')) {
    function flash(): string { return Flash::class; }
}

if (!function_exists('old')) {
    function old(string $key, $default = '') {
        return Flash::old($key, $default);
    }
}

// ---- Date/Time Formatting ----
if (!function_exists('format_date')) {
    function format_date($date, string $format = 'd M Y'): string
    {
        if (!$date) return '—';
        try { return (new DateTime($date))->format($format); }
        catch (Throwable $e) { return (string) $date; }
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($date): string
    {
        return format_date($date, 'd M Y, H:i');
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime): string
    {
        if (!$datetime) return '—';
        try {
            $now = new DateTime();
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
            if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
            if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
            if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
            if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
            if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
            return 'just now';
        } catch (Throwable $e) { return '—'; }
    }
}

// ---- String Utilities ----
if (!function_exists('truncate')) {
    function truncate(string $str, int $len = 80): string
    {
        return mb_strlen($str) <= $len ? $str : mb_substr($str, 0, $len) . '...';
    }
}

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        return strtolower(trim($text, '-')) ?: 'n-a';
    }
}

// ---- UUID Generator ----
if (!function_exists('gen_uuid')) {
    function gen_uuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

// ---- Image Helper with Multi-Level Fallback ----
// Usage: image_url('hero/hero-family.webp', 'placeholders/hero-placeholder.svg')
// Chain: requested -> fallback -> category placeholder -> default SVG
if (!function_exists('image_url')) {
    function image_url(string $path, string $fallback = ''): string
    {
        $basePath = defined('BASE_PATH') ? BASE_PATH . '/public' : dirname(__DIR__, 2) . '/public';
        $imgDir = $basePath . '/assets/images';

        // 1. Try the requested image
        $requested = $imgDir . '/' . ltrim($path, '/');
        if (@file_exists($requested) && @is_file($requested)) {
            return asset('images/' . ltrim($path, '/'));
        }

        // 2. Try the explicit fallback
        if ($fallback) {
            $fb = $imgDir . '/' . ltrim($fallback, '/');
            if (@file_exists($fb) && @is_file($fb)) {
                return asset('images/' . ltrim($fallback, '/'));
            }
        }

        // 3. Try a category-based SVG placeholder
        $category = explode('/', $path)[0] ?? '';
        $catMap = [
            'hero'        => 'placeholders/hero-placeholder.svg',
            'family'      => 'placeholders/family-placeholder.svg',
            'government'  => 'placeholders/government-placeholder.svg',
            'legal'       => 'placeholders/government-placeholder.svg',
            'team'        => 'placeholders/government-placeholder.svg',
            'city'        => 'placeholders/hero-placeholder.svg',
        ];
        if (isset($catMap[$category])) {
            $catFile = $imgDir . '/' . $catMap[$category];
            if (@file_exists($catFile) && @is_file($catFile)) {
                return asset('images/' . $catMap[$category]);
            }
        }

        // 4. Default SVG fallback (always exists)
        return asset('images/placeholders/default.svg');
    }
}

// ---- Responsive Image Tag Helper ----
// Usage: image_tag('hero/hero-family.webp', 'Kigali family discussing estate plans', ['hero'], 1200, 700)
if (!function_exists('image_tag')) {
    function image_tag(
        string $path,
        string $alt = '',
        array  $fallbacks = [],
        int    $width = 0,
        int    $height = 0,
        bool   $lazy = true,
        string $class = ''
    ): string {
        $url = image_url($path, $fallbacks[0] ?? '');
        $escAlt = htmlspecialchars($alt, ENT_QUOTES, 'UTF-8');
        $attrAlt = $alt !== '' ? $escAlt : '';
        $attrClass = $class ? ' class="' . htmlspecialchars($class, ENT_QUOTES) . '"' : '';
        $attrLazy = $lazy ? ' loading="lazy"' : '';
        $attrW = $width > 0 ? ' width="' . $width . '"' : '';
        $attrH = $height > 0 ? ' height="' . $height . '"' : '';

        // Build srcset for responsive images if a -sm variant exists
        $srcset = '';
        $dir = dirname($path);
        $file = basename($path, '.webp') . '.webp';
        $smPath = $dir . '/' . basename($path, '.webp') . '-sm.webp';
        $smFullPath = (defined('BASE_PATH') ? BASE_PATH . '/public' : dirname(__DIR__, 2) . '/public')
                     . '/assets/images/' . $smPath;
        if (@file_exists($smFullPath)) {
            $smUrl = asset('images/' . $smPath);
            $srcset = ' srcset="' . htmlspecialchars($smUrl) . ' 768w, ' . htmlspecialchars($url) . ' 1200w" sizes="(max-width: 768px) 100vw, 1200px"';
        }

        return '<img src="' . htmlspecialchars($url) . '"' . $srcset . ' alt="' . $attrAlt . '"' . $attrClass . $attrW . $attrH . $attrLazy . '>';
    }
}

// ---- Pluralize ----
if (!function_exists('pluralize')) {
    function pluralize(int $n, string $word): string
    {
        return $n . ' ' . $word . ($n === 1 ? '' : 's');
    }
}

// ---- Debug ----
if (!function_exists('dd')) {
    function dd(...$args): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        foreach ($args as $arg) {
            var_dump($arg);
            echo "\n";
        }
        exit;
    }
}

// ---- Language Helper ----
if (!function_exists('__')) {
    function __(string $key, array $replace = []): string
    {
        static $translations = null;
        if ($translations === null) {
            $lang = $_SESSION['lang'] ?? config('app.locale', 'en');
            $langFile = dirname(__DIR__, 2) . '/lang/' . $lang . '.php';
            $translations = file_exists($langFile) ? require $langFile : [];
        }
        $text = $translations[$key] ?? $key;
        foreach ($replace as $k => $v) {
            $text = str_replace(':' . $k, (string) $v, $text);
        }
        return $text;
    }
}
