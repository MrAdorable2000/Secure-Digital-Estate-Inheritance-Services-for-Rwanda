<?php
declare(strict_types=1);

class Auth
{
    private static ?Auth $instance = null;

    private ?array $user = null;
    private ?array $primaryRole = null;
    private array $roles = [];
    private array $permissions = [];
    private bool $booted = false;

    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 15;
    private const REMEMBER_EXPIRY_DAYS = 30;

    private bool $bootFailed = false;

    private function __construct()
    {
        try {
            $this->boot();
        } catch (Throwable $e) {
            $this->bootFailed = true;
            error_log('[R-DEIP Auth] Boot failed (non-fatal): ' . $e->getMessage());
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // -------------------------------------------------------
    // Boot: restore session user
    // -------------------------------------------------------

    public function boot(): void
    {
        if ($this->booted) {
            return;
        }
        $this->booted = true;

        // 1) Check remember-me cookie
        if (!$this->checkSession() && !$this->checkRememberCookie()) {
            return;
        }
    }

    private function checkSession(): bool
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            return false;
        }

        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$userId]
        );

        if (!$user) {
            $this->clearSession();
            return false;
        }

        $this->loadUserState($user);
        return true;
    }

    private function checkRememberCookie(): bool
    {
        $cookieName = config('auth.remember_cookie', 'rdeip_remember');
        $token = $_COOKIE[$cookieName] ?? null;
        if (!$token) {
            return false;
        }

        // token format: selector:validator
        $parts = explode(':', $token);
        if (count($parts) !== 2) {
            return false;
        }

        [$selector, $validator] = $parts;

        try {
            $row = Database::selectOne(
                "SELECT * FROM `remember_tokens` WHERE `selector` = ? AND `expires_at` > NOW() LIMIT 1",
                [$selector]
            );
        } catch (Throwable) {
            return false;
        }

        if (!$row || !hash_equals($row['hashed_validator'], hash('sha256', $validator))) {
            return false;
        }

        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$row['user_id']]
        );

        if (!$user) {
            return false;
        }

        // Rotate token
        $this->rotateRememberToken($row['id'], $row['user_id']);

        $this->loadUserState($user);
        $this->setSessionUser($user);
        return true;
    }

    private function loadUserState(array $user): void
    {
        $this->user = $user;
        $this->roles = [];
        $this->primaryRole = null;
        $this->permissions = [];

        // Load roles via user_roles
        $userRoles = Database::select(
            "SELECT r.* FROM `roles` r
             INNER JOIN `user_roles` ur ON ur.`role_id` = r.`id`
             WHERE ur.`user_id` = ?
             ORDER BY ur.`assigned_at` ASC",
            [$user['id']]
        );

        foreach ($userRoles as $role) {
            $this->roles[$role['slug']] = $role;
        }

        // Primary role = first assigned
        $this->primaryRole = $userRoles[0] ?? null;

        // Load permissions via role_permissions
        $roleIds = array_column($userRoles, 'id');
        if (empty($roleIds)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($roleIds), '?'));
        $perms = Database::select(
            "SELECT DISTINCT p.* FROM `permissions` p
             INNER JOIN `role_permissions` rp ON rp.`permission_id` = p.`id`
             WHERE rp.`role_id` IN ($placeholders)
             ORDER BY p.`module`, p.`slug`",
            $roleIds
        );

        foreach ($perms as $perm) {
            $this->permissions[$perm['slug']] = $perm;
        }
    }

    // -------------------------------------------------------
    // Public accessors
    // -------------------------------------------------------

    public function user(): ?array
    {
        return $this->user;
    }

    public function id(): ?int
    {
        return $this->user['id'] ?? null;
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    public function guest(): bool
    {
        return !$this->check();
    }

    public function primaryRole(): ?array
    {
        return $this->primaryRole;
    }

    public function allRoles(): array
    {
        return $this->roles;
    }

    public function allPermissions(): array
    {
        return $this->permissions;
    }

    // -------------------------------------------------------
    // Role checks
    // -------------------------------------------------------

    public function hasRole(string ...$roles): bool
    {
        foreach ($roles as $role) {
            if (isset($this->roles[$role])) {
                return true;
            }
        }
        return false;
    }

    // -------------------------------------------------------
    // Permission checks
    // -------------------------------------------------------

    public function can(string $permission): bool
    {
        // Super admin wildcard
        if ($this->hasRole('super_admin')) {
            return true;
        }

        return isset($this->permissions[$permission]);
    }

    // -------------------------------------------------------
    // Login attempt
    // -------------------------------------------------------

    public function attempt(string $email, string $password, bool $remember = false): bool|string
    {
        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `email` = ? AND `deleted_at` IS NULL LIMIT 1",
            [strtolower(trim($email))]
        );

        if (!$user) {
            return 'invalid_credentials';
        }

        // Check lockout
        if ($this->isLocked($user)) {
            $remaining = $this->lockoutRemaining($user);
            $this->logLogin((int) $user['id'], 'locked');
            return "account_locked|{$remaining}";
        }

        // Check suspended
        if ($user['status'] === 'suspended') {
            return 'account_suspended';
        }

        // Verify password
        if (!password_verify($password, $user['password'])) {
            $this->incrementFailedAttempts((int) $user['id']);
            $this->logLogin((int) $user['id'], 'failed');

            $attempts = ((int) $user['failed_login_attempts']) + 1;
            if ($attempts >= self::MAX_ATTEMPTS) {
                return "too_many_attempts|{$attempts}";
            }

            return "invalid_credentials|{$attempts}";
        }

        // Check if password needs rehash
        if (password_needs_rehash($user['password'], PASSWORD_BCRYPT, ['cost' => 12])) {
            Database::update(
                'users',
                ['password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])],
                '`id` = :where_id',
                ['where_id' => $user['id']]
            );
        }

        // Reset failed attempts and lock
        Database::update(
            'users',
            [
                'failed_login_attempts' => 0,
                'locked_until' => null,
                'last_login_at' => date('Y-m-d H:i:s'),
                'status' => $user['status'] === 'pending' ? 'active' : $user['status'],
            ],
            '`id` = :where_id',
            ['where_id' => $user['id']]
        );

        // Reload user with fresh data
        $freshUser = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$user['id']]
        );

        $this->login($freshUser, $remember);
        $this->logLogin((int) $freshUser['id'], 'success');

        return true;
    }

    private function isLocked(array $user): bool
    {
        if ($user['locked_until'] === null) {
            return false;
        }
        return strtotime((string) $user['locked_until']) > time();
    }

    private function lockoutRemaining(array $user): int
    {
        if ($user['locked_until'] === null) {
            return 0;
        }
        $remaining = strtotime((string) $user['locked_until']) - time();
        return max(0, (int) ceil($remaining / 60));
    }

    private function incrementFailedAttempts(int $userId): void
    {
        $user = Database::selectOne(
            "SELECT `failed_login_attempts` FROM `users` WHERE `id` = ? LIMIT 1",
            [$userId]
        );

        if (!$user) {
            return;
        }

        $newCount = ((int) $user['failed_login_attempts']) + 1;
        $data = ['failed_login_attempts' => $newCount];

        if ($newCount >= self::MAX_ATTEMPTS) {
            $data['locked_until'] = date('Y-m-d H:i:s', time() + (self::LOCKOUT_MINUTES * 60));
        }

        Database::update('users', $data, '`id` = :where_id', ['where_id' => $userId]);
    }

    private function logLogin(int $userId, string $status): void
    {
        Database::insert('login_logs', [
            'user_id'    => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
            'status'     => $status,
        ]);
    }

    // -------------------------------------------------------
    // Set user as logged in
    // -------------------------------------------------------

    public function login(array $user, bool $remember = false): void
    {
        $this->loadUserState($user);
        $this->setSessionUser($user);

        if ($remember) {
            $this->setRememberCookie((int) $user['id']);
        }
    }

    private function setSessionUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['login_at'] = time();
        $_SESSION['last_activity'] = time();
    }

    private function setRememberCookie(int $userId): void
    {
        try {
            $selector = bin2hex(random_bytes(16));
            $validator = bin2hex(random_bytes(32));
            $hashedValidator = hash('sha256', $validator);
            $expiresAt = date('Y-m-d H:i:s', time() + (self::REMEMBER_EXPIRY_DAYS * 86400));

            // Create the remember_tokens table if not exists (graceful degradation)
            $this->ensureRememberTokensTable();

            Database::insert('remember_tokens', [
                'user_id'          => $userId,
                'selector'         => $selector,
                'hashed_validator' => $hashedValidator,
                'expires_at'       => $expiresAt,
            ]);

            $cookieValue = $selector . ':' . $validator;
            $cookieExpiry = time() + (self::REMEMBER_EXPIRY_DAYS * 86400);
            $cookieName = config('auth.remember_cookie', 'rdeip_remember');

            setcookie($cookieName, $cookieValue, [
                'expires'  => $cookieExpiry,
                'path'     => '/',
                'secure'   => config('app.force_https', false),
                'httponly'  => true,
                'samesite' => 'Lax',
            ]);
        } catch (Throwable $e) {
            error_log('[R-DEIP Auth] Remember-me cookie failed: ' . $e->getMessage());
        }
    }

    private function rotateRememberToken(int $tokenId, int $userId): void
    {
        try {
            $selector = bin2hex(random_bytes(16));
            $validator = bin2hex(random_bytes(32));
            $hashedValidator = hash('sha256', $validator);
            $expiresAt = date('Y-m-d H:i:s', time() + (self::REMEMBER_EXPIRY_DAYS * 86400));

            Database::update(
                'remember_tokens',
                [
                    'selector'         => $selector,
                    'hashed_validator' => $hashedValidator,
                    'expires_at'       => $expiresAt,
                ],
                '`id` = :where_id',
                ['where_id' => $tokenId]
            );

            $cookieName = config('auth.remember_cookie', 'rdeip_remember');
            $cookieValue = $selector . ':' . $validator;

            setcookie($cookieName, $cookieValue, [
                'expires'  => time() + (self::REMEMBER_EXPIRY_DAYS * 86400),
                'path'     => '/',
                'secure'   => config('app.force_https', false),
                'httponly'  => true,
                'samesite' => 'Lax',
            ]);
        } catch (Throwable $e) {
            error_log('[R-DEIP Auth] Token rotation failed: ' . $e->getMessage());
        }
    }

    private function clearRememberCookie(): void
    {
        $cookieName = config('auth.remember_cookie', 'rdeip_remember');
        unset($_COOKIE[$cookieName]);
        setcookie($cookieName, '', [
            'expires'  => time() - 3600,
            'path'     => '/',
            'httponly'  => true,
            'samesite' => 'Lax',
        ]);
    }

    private function deleteRememberTokens(int $userId): void
    {
        try {
            Database::delete('remember_tokens', '`user_id` = ?', [$userId]);
        } catch (Throwable) {
            // Table may not exist
        }
    }

    private function ensureRememberTokensTable(): void
    {
        if (!Database::tableExists('remember_tokens')) {
            Database::query("
                CREATE TABLE `remember_tokens` (
                  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                  `user_id` BIGINT UNSIGNED NOT NULL,
                  `selector` CHAR(32) NOT NULL,
                  `hashed_validator` CHAR(64) NOT NULL,
                  `expires_at` TIMESTAMP NOT NULL,
                  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uk_rt_selector` (`selector`),
                  INDEX `idx_rt_user_id` (`user_id`),
                  INDEX `idx_rt_expires_at` (`expires_at`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            ");
        }
    }

    // -------------------------------------------------------
    // Logout
    // -------------------------------------------------------

    public function logout(): void
    {
        if ($this->user !== null) {
            $this->deleteRememberTokens((int) $this->user['id']);
        }

        $this->clearRememberCookie();
        $this->clearSession();

        $this->user = null;
        $this->primaryRole = null;
        $this->roles = [];
        $this->permissions = [];
        $this->booted = false;
    }

    private function clearSession(): void
    {
        unset(
            $_SESSION['user_id'],
            $_SESSION['login_at'],
            $_SESSION['last_activity']
        );
        session_regenerate_id(true);
    }

    // -------------------------------------------------------
    // Registration
    // -------------------------------------------------------

    public function register(array $data): array
    {
        $uuid = gen_uuid();
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $userId = Database::insert('users', [
            'uuid'      => $uuid,
            'first_name' => trim($data['first_name']),
            'last_name'  => trim($data['last_name']),
            'email'      => strtolower(trim($data['email'])),
            'phone'      => $data['phone'] ? trim($data['phone']) : null,
            'password'   => $hashedPassword,
            'status'     => 'active',
        ]);

        // Assign citizen role
        $citizenRole = Database::selectOne(
            "SELECT `id` FROM `roles` WHERE `slug` = 'citizen' LIMIT 1"
        );

        if ($citizenRole) {
            Database::insert('user_roles', [
                'user_id'     => $userId,
                'role_id'     => (int) $citizenRole['id'],
                'assigned_by' => $userId,
            ]);
        }

        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? LIMIT 1",
            [$userId]
        );

        return $user;
    }

    // -------------------------------------------------------
    // Audit logging
    // -------------------------------------------------------

    public function logAudit(string $action, string $module, string $description): void
    {
        Database::insert('audit_logs', [
            'user_id'    => $this->id(),
            'action'     => $action,
            'module'     => $module,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
        ]);
    }
}
