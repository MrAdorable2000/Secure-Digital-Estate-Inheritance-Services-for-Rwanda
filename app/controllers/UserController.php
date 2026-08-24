<?php
declare(strict_types=1);

class UserController extends Controller
{
    private const PER_PAGE = 15;

    // -------------------------------------------------------
    // List Users (paginated, searchable, filterable)
    // -------------------------------------------------------

    public function index(array $params = []): void
    {
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');
        $status = $_GET['status'] ?? '';
        $offset = ($page - 1) * self::PER_PAGE;

        $where = 'WHERE u.`deleted_at` IS NULL';
        $params = [];

        if ($search !== '') {
            $where .= " AND (u.`first_name` LIKE ? OR u.`last_name` LIKE ? OR u.`email` LIKE ?)";
            $like = "%{$search}%";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        if (in_array($status, ['active', 'inactive', 'suspended', 'pending'], true)) {
            $where .= ' AND u.`status` = ?';
            $params[] = $status;
        }

        $total = (int) Database::selectScalar(
            "SELECT COUNT(*) FROM `users` u {$where}",
            $params
        );

        $users = Database::select(
            "SELECT u.*,
                    (SELECT r.`slug` FROM `roles` r
                     INNER JOIN `user_roles` ur ON ur.`role_id` = r.`id`
                     WHERE ur.`user_id` = u.`id`
                     ORDER BY ur.`assigned_at` ASC LIMIT 1) AS role_slug,
                    (SELECT r.`name` FROM `roles` r
                     INNER JOIN `user_roles` ur ON ur.`role_id` = r.`id`
                     WHERE ur.`user_id` = u.`id`
                     ORDER BY ur.`assigned_at` ASC LIMIT 1) AS role_name
             FROM `users` u
             {$where}
             ORDER BY u.`created_at` DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [self::PER_PAGE, $offset])
        );

        $totalPages = (int) ceil($total / self::PER_PAGE);

        $this->layout('app', 'users/index', [
            'pageTitle'   => 'User Management',
            'users'       => $users,
            'page'        => $page,
            'totalPages'  => $totalPages,
            'total'       => $total,
            'search'      => e($search),
            'status'      => e($status),
        ]);
    }

    // -------------------------------------------------------
    // Create User Form
    // -------------------------------------------------------

    public function create(array $params = []): void
    {
        $roles = Database::select("SELECT * FROM `roles` ORDER BY `name` ASC");
        $departments = Database::select("SELECT * FROM `departments` WHERE `status` = 'active' ORDER BY `name` ASC");

        $this->layout('app', 'users/create', [
            'pageTitle'   => 'Create User',
            'roles'       => $roles,
            'departments' => $departments,
        ]);
    }

    // -------------------------------------------------------
    // Store User
    // -------------------------------------------------------

    public function store(array $params = []): void
    {
        $input = $_POST;

        $validator = new Validator($input, [
            'first_name'  => 'required|max:100',
            'last_name'   => 'required|max:100',
            'email'       => 'required|email|unique:users',
            'phone'       => 'phone',
            'password'    => 'required|strong_password',
            'role_id'     => 'required|exists:roles,id',
            'status'      => 'required',
        ]);

        if (!$validator->validate()) {
            Flash::setInputs($input);
            foreach ($validator->errors() as $errors) {
                foreach ($errors as $error) {
                    Flash::set('error', $error);
                }
            }
            $this->back();
        }

        try {
            Database::transaction(function () use ($input) {
                $userId = Database::insert('users', [
                    'uuid'       => gen_uuid(),
                    'first_name' => trim($input['first_name']),
                    'last_name'  => trim($input['last_name']),
                    'email'      => strtolower(trim($input['email'])),
                    'phone'      => !empty($input['phone']) ? trim($input['phone']) : null,
                    'password'   => password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                    'status'     => $input['status'],
                ]);

                Database::insert('user_roles', [
                    'user_id'     => $userId,
                    'role_id'     => (int) $input['role_id'],
                    'assigned_by' => auth()->id(),
                ]);

                auth()->logAudit(
                    'user.create',
                    'users',
                    "Created user account for {$input['email']} (ID: {$userId}) with role ID {$input['role_id']}"
                );
            });

            CSRF::rotate();
            Flash::set('success', 'User created successfully.');
            $this->redirect('users');
        } catch (Throwable $e) {
            error_log('[R-DEIP Users] Create failed: ' . $e->getMessage());
            Flash::setInputs($input);
            Flash::set('error', 'Failed to create user. Please try again.');
            $this->back();
        }
    }

    // -------------------------------------------------------
    // Edit User Form
    // -------------------------------------------------------

    public function edit(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);

        $user = Database::selectOne(
            "SELECT u.*,
                    (SELECT r.`id` FROM `roles` r
                     INNER JOIN `user_roles` ur ON ur.`role_id` = r.`id`
                     WHERE ur.`user_id` = u.`id`
                     ORDER BY ur.`assigned_at` ASC LIMIT 1) AS role_id
             FROM `users` u
             WHERE u.`id` = ? AND u.`deleted_at` IS NULL
             LIMIT 1",
            [$id]
        );

        if (!$user) {
            Flash::set('error', 'User not found.');
            $this->redirect('users');
        }

        $roles = Database::select("SELECT * FROM `roles` ORDER BY `name` ASC");

        $this->layout('app', 'users/edit', [
            'pageTitle' => 'Edit User',
            'user'      => $user,
            'roles'     => $roles,
        ]);
    }

    // -------------------------------------------------------
    // Update User
    // -------------------------------------------------------

    public function update(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $input = $_POST;

        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$id]
        );

        if (!$user) {
            Flash::set('error', 'User not found.');
            $this->redirect('users');
        }

        // Email uniqueness: exclude current user
        $emailRule = 'required|email';
        if (strtolower(trim($input['email'])) !== strtolower($user['email'])) {
            $emailRule .= '|unique:users';
        }

        $rules = [
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:100',
            'email'      => $emailRule,
            'phone'      => 'phone',
            'role_id'    => 'required|exists:roles,id',
            'status'     => 'required',
        ];

        // Only require password if provided
        if (!empty($input['password'])) {
            $rules['password'] = 'strong_password';
        }

        $validator = new Validator($input, $rules);

        if (!$validator->validate()) {
            Flash::setInputs($input);
            foreach ($validator->errors() as $errors) {
                foreach ($errors as $error) {
                    Flash::set('error', $error);
                }
            }
            $this->back();
        }

        try {
            Database::transaction(function () use ($id, $input, $user) {
                $data = [
                    'first_name' => trim($input['first_name']),
                    'last_name'  => trim($input['last_name']),
                    'email'      => strtolower(trim($input['email'])),
                    'phone'      => !empty($input['phone']) ? trim($input['phone']) : null,
                    'status'     => $input['status'],
                ];

                // Update password only if provided
                if (!empty($input['password'])) {
                    $data['password'] = password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 12]);
                }

                Database::update('users', $data, '`id` = :where_id', ['where_id' => $id]);

                // Handle role change
                $currentRole = Database::selectOne(
                    "SELECT ur.`role_id` FROM `user_roles` ur
                     WHERE ur.`user_id` = ?
                     ORDER BY ur.`assigned_at` ASC
                     LIMIT 1",
                    [$id]
                );

                if (!$currentRole || (int) $currentRole['role_id'] !== (int) $input['role_id']) {
                    // Remove all existing roles and assign new one
                    Database::delete('user_roles', '`user_id` = ?', [$id]);
                    Database::insert('user_roles', [
                        'user_id'     => $id,
                        'role_id'     => (int) $input['role_id'],
                        'assigned_by' => auth()->id(),
                    ]);
                }

                auth()->logAudit(
                    'user.update',
                    'users',
                    "Updated user account for {$user['email']} (ID: {$id})"
                );
            });

            CSRF::rotate();
            Flash::set('success', 'User updated successfully.');
            $this->redirect('users');
        } catch (Throwable $e) {
            error_log('[R-DEIP Users] Update failed: ' . $e->getMessage());
            Flash::setInputs($input);
            Flash::set('error', 'Failed to update user. Please try again.');
            $this->back();
        }
    }

    // -------------------------------------------------------
    // Suspend User
    // -------------------------------------------------------

    public function suspend(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);

        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$id]
        );

        if (!$user) {
            Flash::set('error', 'User not found.');
            $this->redirect('users');
        }

        // Prevent suspending self
        if ($id === auth()->id()) {
            Flash::set('error', 'You cannot suspend your own account.');
            $this->redirect('users');
        }

        Database::update(
            'users',
            ['status' => 'suspended'],
            '`id` = :where_id',
            ['where_id' => $id]
        );

        auth()->logAudit(
            'user.suspend',
            'users',
            "Suspended user account for {$user['email']} (ID: {$id})"
        );

        Flash::set('success', "User {$user['first_name']} {$user['last_name']} has been suspended.");
        $this->redirect('users');
    }

    // -------------------------------------------------------
    // Activate User
    // -------------------------------------------------------

    public function activate(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);

        $user = Database::selectOne(
            "SELECT * FROM `users` WHERE `id` = ? AND `deleted_at` IS NULL LIMIT 1",
            [$id]
        );

        if (!$user) {
            Flash::set('error', 'User not found.');
            $this->redirect('users');
        }

        Database::update(
            'users',
            [
                'status'            => 'active',
                'failed_login_attempts' => 0,
                'locked_until'      => null,
            ],
            '`id` = :where_id',
            ['where_id' => $id]
        );

        auth()->logAudit(
            'user.activate',
            'users',
            "Activated user account for {$user['email']} (ID: {$id})"
        );

        Flash::set('success', "User {$user['first_name']} {$user['last_name']} has been activated.");
        $this->redirect('users');
    }
}
