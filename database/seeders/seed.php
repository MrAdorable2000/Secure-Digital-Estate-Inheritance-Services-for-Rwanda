<?php
/**
 * R-DEIP Database Seeder
 * Phase 1: Foundation & Authentication
 *
 * This script connects to the R-DEIP database, generates a proper bcrypt hash
 * for the demo user password, and updates all demo users with the real hash.
 *
 * Usage:
 *   php seed.php
 *
 * Or with custom connection:
 *   php seed.php --host=localhost --db=rdeip --user=root --pass=secret
 */

declare(strict_types=1);

// ============================================
// Configuration
// ============================================

$demoPassword = 'Password@123';

// Default DB connection settings (override via CLI args)
$dbHost = 'localhost';
$dbName = 'rdeip';
$dbUser = 'root';
$dbPass = '';

$demoEmails = [
    'superadmin@rdeip.gov.rw',
    'admin@rdeip.gov.rw',
    'officer@rdeip.gov.rw',
    'citizen@rdeip.gov.rw',
];

// Parse CLI arguments
for ($i = 1; $i < $argc; $i++) {
    if (str_starts_with($argv[$i], '--host=')) {
        $dbHost = substr($argv[$i], 7);
    } elseif (str_starts_with($argv[$i], '--db=')) {
        $dbName = substr($argv[$i], 5);
    } elseif (str_starts_with($argv[$i], '--user=')) {
        $dbUser = substr($argv[$i], 7);
    } elseif (str_starts_with($argv[$i], '--pass=')) {
        $dbPass = substr($argv[$i], 7);
    } elseif (in_array($argv[$i], ['-h', '--help'])) {
        echo "Usage: php seed.php [--host=localhost] [--db=rdeip] [--user=root] [--pass=secret]\n";
        exit(0);
    }
}

// ============================================
// Connect to MySQL
// ============================================

echo "=== R-DEIP Database Seeder ===" . PHP_EOL;
echo "Connecting to MySQL... ";

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    echo "FAILED" . PHP_EOL;
    echo "Error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}

echo "OK" . PHP_EOL;
echo "Database: {$dbName}" . PHP_EOL;

// ============================================
// Generate bcrypt hash
// ============================================

echo PHP_EOL . "Generating bcrypt hash for demo password... ";
$hash = password_hash($demoPassword, PASSWORD_BCRYPT, ['cost' => 12]);
echo "OK" . PHP_EOL;
echo "Hash: {$hash}" . PHP_EOL;

// Verify the hash works
echo "Verifying hash... ";
if (password_verify($demoPassword, $hash)) {
    echo "OK (verified)" . PHP_EOL;
} else {
    echo "FAILED (hash verification failed!)" . PHP_EOL;
    exit(1);
}

// ============================================
// Update demo user passwords
// ============================================

echo PHP_EOL . "Updating demo user passwords:" . PHP_EOL;

$stmt = $pdo->prepare("UPDATE `users` SET `password` = :hash WHERE `email` = :email");
$updated = 0;

foreach ($demoEmails as $email) {
    $stmt->execute([':hash' => $hash, ':email' => $email]);
    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) {
        echo "  [OK]   {$email}" . PHP_EOL;
        $updated++;
    } else {
        echo "  [SKIP] {$email} (not found or already updated)" . PHP_EOL;
    }
}

// ============================================
// Summary
// ============================================

echo PHP_EOL;
echo "--- Seeding Complete ---" . PHP_EOL;
echo "Users updated: {$updated}/" . count($demoEmails) . PHP_EOL;
echo "Demo password: {$demoPassword}" . PHP_EOL;
echo PHP_EOL;
echo "You can now log in with any of these accounts:" . PHP_EOL;
echo "  - superadmin@rdeip.gov.rw  (Super Administrator)" . PHP_EOL;
echo "  - admin@rdeip.gov.rw       (Administrator)" . PHP_EOL;
echo "  - officer@rdeip.gov.rw     (Government Officer)" . PHP_EOL;
echo "  - citizen@rdeip.gov.rw      (Citizen)" . PHP_EOL;

exit(0);
