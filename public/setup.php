<?php
/**
 * R-DEIP Setup Diagnostic Tool — BULLETPROOF
 * Open in browser: http://localhost/rdeip/setup.php
 * DELETE THIS FILE after setup is complete.
 */
header('Content-Type: text/html; charset=utf-8');

// Ensure no output buffering issues
if (ob_get_level()) ob_end_clean();
ob_start();
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>R-DEIP Setup Diagnostic</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
*{margin:0;padding:0;box-sizing:border-box}
:root{--bg:#0B1120;--surface:rgba(255,255,255,0.04);--border:rgba(255,255,255,0.08);--text:#F1F5F9;--muted:#94A3B8;--dim:#64748B;--green:#00A651;--green-dark:#00693E;--red:#EF4444;--amber:#FBBF24;--font:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;--mono:'SF Mono','Fira Code',monospace}
body{font-family:var(--font);background:var(--bg);color:var(--text);min-height:100vh;overflow-x:hidden;-webkit-font-smoothing:antialiased}
.bg{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
.bg::before{content:'';position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(ellipse 600px 600px at 20% 30%,rgba(0,166,81,0.3),transparent),radial-gradient(ellipse 500px 500px at 80% 70%,rgba(229,161,0,0.2),transparent),radial-gradient(ellipse 400px 400px at 50% 50%,rgba(27,58,107,0.2),transparent);animation:drift 20s ease-in-out infinite alternate}
@keyframes drift{0%{transform:translate(0,0)}33%{transform:translate(-20px,-15px)}66%{transform:translate(15px,10px)}100%{transform:translate(-8px,-8px)}}
.grid{position:fixed;inset:0;z-index:1;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,0.015) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,0.015) 1px,transparent 1px);background-size:60px 60px;mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,black,transparent);-webkit-mask-image:radial-gradient(ellipse 70% 70% at 50% 50%,black,transparent)}
.container{max-width:800px;margin:0 auto;padding:2.5rem 1.5rem;position:relative;z-index:10}
.header{text-align:center;margin-bottom:2.5rem}
.header h1{font-size:2rem;font-weight:800;background:linear-gradient(135deg,var(--green),#34D399,var(--green-dark));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.5rem;animation:fadein .8s cubic-bezier(.16,1,.3,1) both}
.header p{color:var(--muted);font-size:.95rem;animation:fadein .8s .1s cubic-bezier(.16,1,.3,1) both}
@keyframes fadein{0%{opacity:0;transform:translateY(16px)}100%{opacity:1;transform:translateY(0)}}
h2{font-size:.85rem;font-weight:600;color:var(--dim);text-transform:uppercase;letter-spacing:.08em;margin:2.5rem 0 1rem;padding-bottom:.5rem;border-bottom:1px solid var(--border);animation:fadein .8s .15s cubic-bezier(.16,1,.3,1) both}
.check{display:flex;align-items:flex-start;gap:1rem;padding:1rem 1.25rem;border-radius:12px;margin-bottom:.625rem;background:var(--surface);border:1px solid var(--border);transition:all .2s;animation:fadein .6s cubic-bezier(.16,1,.3,1) both}
.check:hover{background:rgba(255,255,255,0.06);border-color:rgba(255,255,255,0.12)}
.icon{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;margin-top:1px}
.pass .icon{background:rgba(0,166,81,0.15);color:#34D399}
.fail .icon{background:rgba(239,68,68,0.15);color:#F87171}
.warn .icon{background:rgba(245,158,11,0.15);color:#FBBF24}
.label{font-weight:600;font-size:.95rem;color:var(--text)}
.detail{font-size:.85rem;color:var(--muted);margin-top:.25rem;line-height:1.5}
.detail code{background:rgba(255,255,255,0.08);padding:.15rem .5rem;border-radius:5px;font-size:.8rem;font-family:var(--mono);color:var(--text)}
.actions-box{margin-top:2rem;padding:1.75rem;background:var(--surface);border:1px solid var(--border);border-radius:14px;animation:fadein .8s .3s cubic-bezier(.16,1,.3,1) both}
.actions-box h3{margin-bottom:1rem;font-size:1rem;color:var(--text)}
.actions-box ol{padding-left:1.5rem;line-height:2.2;color:var(--muted);font-size:.925rem}
.actions-box code{background:rgba(255,255,255,0.08);padding:.1rem .5rem;border-radius:5px;font-size:.825rem;font-family:var(--mono);color:var(--text)}
.btn{display:inline-block;padding:.7rem 1.75rem;border-radius:10px;text-decoration:none;font-weight:600;font-size:.9rem;margin-top:1.25rem;transition:all .25s cubic-bezier(.16,1,.3,1);cursor:pointer;border:none;font-family:var(--font);position:relative;overflow:hidden}
.btn-primary{background:linear-gradient(135deg,var(--green-dark),var(--green));color:#fff;box-shadow:0 4px 20px rgba(0,105,62,.35),inset 0 1px 0 rgba(255,255,255,.12)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,105,62,.4)}
.btn-primary::after{content:'';position:absolute;top:0;left:-100%;width:60%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.12),transparent);transition:left .5s}
.btn-primary:hover::after{left:120%}
.result{margin-top:1.5rem;padding:1.25rem 1.5rem;border-radius:12px;animation:fadein .6s cubic-bezier(.16,1,.3,1) both}
.result-success{background:rgba(0,166,81,0.1);border:1px solid rgba(0,166,81,0.25);color:#34D399}
.result-error{background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);color:#F87171}
.result h3{margin-bottom:.5rem;font-size:.95rem}
.sql-box{background:rgba(0,0,0,0.3);border:1px solid var(--border);border-radius:8px;padding:1rem;margin-top:1rem;font-family:var(--mono);font-size:.8rem;color:var(--muted);white-space:pre-wrap;word-break:break-all}
.footer{text-align:center;margin-top:3rem;padding-top:1.5rem;border-top:1px solid var(--border);color:var(--dim);font-size:.8rem;animation:fadein .8s .5s cubic-bezier(.16,1,.3,1) both}
@media(max-width:640px){.container{padding:1.5rem 1rem}.header h1{font-size:1.5rem}.check{padding:.875rem 1rem}.actions-box{padding:1.25rem}}
</style>
</head>
<body>
<div class="bg"></div>
<div class="grid"></div>
<div class="container">
<div class="header">
<h1>R-DEIP Setup Diagnostic</h1>
<p>Rwanda Digital Estate &amp; Inheritance Platform</p>
</div>

<?php
$checks = [];

// ---- PHP Version ----
$phpVer = PHP_VERSION;
$phpOk = version_compare($phpVer, '8.0', '>=');
$checks[] = ['pass' => $phpOk, 'label' => 'PHP Version', 'detail' => "Running PHP $phpVer" . ($phpOk ? '' : ' — PHP 8.0+ required')];

// ---- Extensions ----
$exts = ['pdo', 'pdo_mysql', 'session', 'json', 'mbstring', 'openssl', 'ctype'];
foreach ($exts as $ext) {
    $loaded = extension_loaded($ext);
    $checks[] = ['pass' => $loaded, 'warn' => !$loaded, 'label' => "Extension: $ext", 'detail' => $loaded ? 'Loaded' : 'MISSING — install it'];
}

// ---- Session ----
@session_start();
$sessionOk = session_status() === PHP_SESSION_ACTIVE;
$checks[] = ['pass' => $sessionOk, 'label' => 'Sessions', 'detail' => $sessionOk ? 'Session started' : 'Session failed to start'];

// ---- Directories ----
$baseDir = dirname(__DIR__);

// ---- .env file ----
$envPath = $baseDir . '/.env';
$envExists = @file_exists($envPath);
$checks[] = ['pass' => $envExists, 'label' => '.env File', 'detail' => $envExists ? "Found" : 'NOT FOUND — copy <code>.env.example</code> to <code>.env</code>'];

// ---- Config ----
$configPath = $baseDir . '/config/config.php';
$configOk = @file_exists($configPath);
$checks[] = ['pass' => $configOk, 'label' => 'Config File', 'detail' => $configOk ? 'Loaded' : 'NOT FOUND'];

// ---- Database Connection ----
$dbHost = '127.0.0.1'; $dbPort = 3306; $dbName = 'rdeip'; $dbUser = 'root'; $dbPass = '';
$dbOk = false;
$dbExists = false;
$tablesOk = false;
$pdo = null;

if ($envExists) {
    $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $line = trim($line);
            if (str_starts_with($line, '#') || strpos($line, '=') === false) continue;
            $parts = explode('=', $line, 2);
            if (count($parts) !== 2) continue;
            $k = trim($parts[0]);
            $v = trim($parts[1], " \t\"'");
            if ($k === 'DB_HOST') $dbHost = $v;
            if ($k === 'DB_PORT') $dbPort = (int)$v;
            if ($k === 'DB_NAME') $dbName = $v;
            if ($k === 'DB_USER') $dbUser = $v;
            if ($k === 'DB_PASS') $dbPass = $v;
        }
    }
}

try {
    $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3,
    ]);
    $dbOk = true;
    $checks[] = ['pass' => true, 'label' => 'MySQL Connection', 'detail' => "Connected to <code>$dbHost:$dbPort</code> as <code>$dbUser</code>"];

    // Check if database exists
    try {
        $stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = ?");
        $stmt->execute([$dbName]);
        $dbExists = (bool)$stmt->fetch();
    } catch (Throwable) {
        $dbExists = false;
    }

    if ($dbExists) {
        $pdo->exec("USE `$dbName`");
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $tableCount = count($tables);
        $requiredTables = ['users','roles','permissions','role_permissions','user_roles','audit_logs','password_resets','login_logs'];
        $missingTables = array_diff($requiredTables, $tables);
        $tablesOk = empty($missingTables);
        $checks[] = ['pass' => $tablesOk, 'warn' => !$tablesOk, 'label' => "Database '$dbName'", 'detail' => $tablesOk
            ? "$tableCount tables found — all required tables present"
            : "$tableCount tables found. Missing: <code>" . implode('</code>, <code>', $missingTables) . "</code>"];
    } else {
        $checks[] = ['pass' => false, 'label' => "Database '$dbName'", 'detail' => 'Database does not exist. Create it below.'];
    }

    // User count
    if ($dbExists && $tablesOk) {
        try {
            $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $checks[] = ['pass' => $count > 0, 'label' => 'Seed Users', 'detail' => "$count user(s) found in database"];
        } catch (Throwable) {}
    }
} catch (PDOException $e) {
    $checks[] = ['pass' => false, 'label' => 'MySQL Connection', 'detail' => 'FAILED: ' . htmlspecialchars($e->getMessage())];
} catch (Throwable) {
    $checks[] = ['pass' => false, 'label' => 'MySQL Connection', 'detail' => 'FAILED: Unable to connect'];
}

// ---- File Permissions ----
$dirs = ['storage/logs', 'storage/cache', 'storage/backups', 'public/uploads', 'public/uploads/profiles'];
foreach ($dirs as $dir) {
    $fullPath = $baseDir . '/' . $dir;
    if (!@is_dir($fullPath)) {
        @mkdir($fullPath, 0755, true);
    }
    $writable = @is_writable($fullPath);
    $checks[] = ['pass' => $writable, 'warn' => !$writable, 'label' => "Writable: $dir", 'detail' => $writable ? 'OK' : 'NOT WRITABLE'];
}

// ---- Rewrite ----
$rewrite = function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules());
$checks[] = ['pass' => $rewrite, 'warn' => !$rewrite, 'label' => 'mod_rewrite', 'detail' => $rewrite ? 'Enabled' : 'Cannot detect — ensure it is enabled'];

// ---- Render Checks ----
foreach ($checks as $i => $c) {
    $cls = !empty($c['pass']) ? 'pass' : (!empty($c['warn']) ? 'warn' : 'fail');
    $icon = $cls === 'pass' ? '&#10003;' : ($cls === 'warn' ? '&#9888;' : '&#10007;');
    echo "<div class=\"check $cls\" style=\"animation-delay:" . ($i * 0.04) . "s\"><div class=\"icon\">$icon</div><div><div class=\"label\">" . htmlspecialchars($c['label']) . "</div><div class=\"detail\">" . $c['detail'] . "</div></div></div>\n";
}

// ---- SQL File ----
$sqlFile = $baseDir . '/database/rdeip.sql';
$sqlExists = @file_exists($sqlFile);
$sqlSize = $sqlExists ? @filesize($sqlFile) : 0;
?>

<div class="actions-box">
<h3>Setup Steps</h3>
<ol>
    <li>Ensure MySQL is running in XAMPP</li>
    <li>Create the database: <code>CREATE DATABASE rdeip CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code></li>
    <li>Import the schema: <code>mysql -u root rdeip &lt; database/rdeip.sql</code></li>
    <li><?php if ($sqlExists): ?>SQL file found (<code><?php echo number_format($sqlSize); ?></code> bytes)<?php else: ?><strong style="color:#F87171">SQL file NOT found</strong><?php endif; ?></li>
    <li>Set <code>APP_ENV=development</code> in <code>.env</code> for debugging</li>
    <li>Delete <code>public/setup.php</code> after setup</li>
</ol>

<?php if ($dbOk && !$dbExists): ?>
<form method="POST" style="margin-top:1rem">
    <button type="submit" name="action" value="create_db" class="btn btn-primary">Create Database &amp; Import Schema</button>
</form>
<?php endif; ?>

<?php if ($dbOk && $dbExists && !$tablesOk): ?>
<form method="POST" style="margin-top:1rem">
    <button type="submit" name="action" value="import_sql" class="btn btn-primary">Import SQL Schema</button>
</form>
<?php endif; ?>
</div>

<?php
// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create_db') {
        try {
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `$dbName`");
            $sql = @file_get_contents($sqlFile);
            if ($sql) {
                $pdo->exec($sql);
                echo '<div class="result result-success"><h3>Database created and schema imported successfully!</h3><a href="' . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\') . '" class="btn btn-primary">Go to R-DEIP Homepage</a></div>';
            } else {
                echo '<div class="result result-error"><h3>Could not read SQL file.</h3></div>';
            }
        } catch (Throwable $e) {
            echo '<div class="result result-error"><h3>Error: ' . htmlspecialchars($e->getMessage()) . '</h3></div>';
        }
    }
    if ($action === 'import_sql') {
        try {
            $pdo->exec("USE `$dbName`");
            $sql = @file_get_contents($sqlFile);
            if ($sql) {
                $pdo->exec($sql);
                echo '<div class="result result-success"><h3>Schema imported successfully!</h3><a href="' . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\') . '" class="btn btn-primary">Go to R-DEIP Homepage</a></div>';
            } else {
                echo '<div class="result result-error"><h3>Could not read SQL file.</h3></div>';
            }
        } catch (Throwable $e) {
            echo '<div class="result result-error"><h3>Error: ' . htmlspecialchars($e->getMessage()) . '</h3></div>';
        }
    }
}
?>

<div class="footer">R-DEIP Setup Diagnostic &mdash; Delete this file after use</div>
</div>
</body>
</html>