<?php
declare(strict_types=1);

class Database
{
    private static ?PDO $instance = null;

    private static function getDsn(array $cfg): string
    {
        return sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $cfg['host'] ?? '127.0.0.1',
            $cfg['port'] ?? 3306,
            $cfg['name'] ?? 'rdeip',
            $cfg['charset'] ?? 'utf8mb4'
        );
    }

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $cfg = $GLOBALS['config']['database'] ?? [
                'host' => '127.0.0.1', 'port' => 3306,
                'name' => 'rdeip', 'user' => 'root', 'pass' => '',
                'charset' => 'utf8mb4',
            ];
            $dsn = self::getDsn($cfg);
            try {
                self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
                self::$instance->exec("SET time_zone = '+02:00', sql_mode = 'STRICT_ALL_TABLES,NO_ENGINE_SUBSTITUTION'");
            } catch (PDOException $e) {
                error_log('[R-DEIP DB] Connection failed: ' . $e->getMessage());
                throw new RuntimeException('Database connection failed. Please check your configuration and ensure MySQL is running.');
            }
        }
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function select(string $sql, array $params = []): array
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function selectScalar(string $sql, array $params = [])
    {
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($params);
        $val = $stmt->fetchColumn();
        return $val === false ? null : $val;
    }

    public static function insert(string $table, array $data): int
    {
        $cols = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $cols);
        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table, implode('`, `', $cols), implode(', ', $placeholders)
        );
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute($data);
        return (int) self::getInstance()->lastInsertId();
    }

    public static function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $set = array_map(fn($c) => "`$c` = :$c", array_keys($data));
        $sql = sprintf('UPDATE `%s` SET %s WHERE %s', $table, implode(', ', $set), $where);
        $stmt = self::getInstance()->prepare($sql);
        $stmt->execute(array_merge($data, $whereParams));
        return $stmt->rowCount();
    }

    public static function delete(string $table, string $where, array $params = []): int
    {
        $stmt = self::getInstance()->prepare("DELETE FROM `$table` WHERE $where");
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public static function transaction(callable $callback)
    {
        $pdo = self::getInstance();
        $pdo->beginTransaction();
        try {
            $result = $callback($pdo);
            $pdo->commit();
            return $result;
        } catch (Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public static function tableExists(string $table): bool
    {
        return (bool) self::selectScalar(
            "SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1",
            [$table]
        );
    }
}
