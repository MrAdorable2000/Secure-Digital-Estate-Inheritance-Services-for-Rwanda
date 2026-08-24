<?php
declare(strict_types=1);

class AuthController extends Controller
{
    // -------------------------------------------------------
    // Login
    // -------------------------------------------------------

    public function showLogin(array $params = []): void
    {
        $this->layout('auth', 'auth/login', [
            'pageTitle' => 'Sign In',
        ]);
    }

    public function handleLogin(array $params = []): void
    {
        $input = $_POST;

        $validator = new Validator($input, [
            'email'    => 'required|email',
            'password' => 'required|min:1',
        ]);

        if (!$validator->validate()) {
            Flash::setInputs($input);
            foreach ($validator->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    Flash::set('error', $error);
                }
            }
            $this->back();
        }

        $email = trim($input['email']);
        $password = $input['password'];
        $remember = !empty($input['remember']);

        $result = auth()->attempt($email, $password, $remember);

        if ($result === true) {
            CSRF::rotate();
            Flash::set('success', 'Welcome back, ' . e(user()['first_name']) . '!');
            $this->redirect('dashboard');
        }

        // Parse error result
        if (str_contains((string) $result, '|')) {
            [$code, $extra] = explode('|', (string) $result, 2);
        } else {
            $code = (string) $result;
            $extra = null;
        }

        Flash::setInputs(['email' => $email]);

        switch ($code) {
            case 'account_locked':
                Flash::set('error', "Your account has been locked due to too many failed login attempts. Please try again in {$extra} minute(s).");
                break;
            case 'too_many_attempts':
                Flash::set('error', "Invalid credentials. You have used {$extra} of 5 allowed attempts. Your account will be locked after that.");
                break;
            case 'account_suspended':
                Flash::set('error', 'Your account has been suspended. Please contact an administrator for assistance.');
                break;
            case 'invalid_credentials':
                if ($extra !== null) {
                    Flash::set('error', "Invalid email or password. Attempt {$extra} of 5.");
                } else {
                    Flash::set('error', 'Invalid email or password.');
                }
                break;
            default:
                Flash::set('error', 'Login failed. Please try again.');
        }

        $this->back();
    }

    // -------------------------------------------------------
    // Registration
    // -------------------------------------------------------

    public function showRegister(array $params = []): void
    {
        $this->layout('auth', 'auth/register', [
            'pageTitle' => 'Create Account',
        ]);
    }

    public function handleRegister(array $params = []): void
    {
        $input = $_POST;

        $validator = new Validator($input, [
            'first_name'            => 'required|max:100',
            'last_name'             => 'required|max:100',
            'email'                 => 'required|email|unique:users',
            'phone'                 => 'phone',
            'password'              => 'required|strong_password|confirmed',
        ]);

        if (!$validator->validate()) {
            Flash::setInputs($input);
            foreach ($validator->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    Flash::set('error', $error);
                }
            }
            $this->back();
        }

        try {
            Database::transaction(function () use ($input) {
                auth()->register([
                    'first_name' => $input['first_name'],
                    'last_name'  => $input['last_name'],
                    'email'      => $input['email'],
                    'phone'      => $input['phone'] ?? '',
                    'password'   => $input['password'],
                ]);
            });

            CSRF::rotate();
            Flash::set('success', 'Your account has been created successfully. Please sign in.');
            $this->redirect('login');
        } catch (Throwable $e) {
            error_log('[R-DEIP Auth] Registration failed: ' . $e->getMessage());
            Flash::setInputs($input);
            Flash::set('error', 'An error occurred during registration. Please try again.');
            $this->back();
        }
    }

    // -------------------------------------------------------
    // Forgot Password
    // -------------------------------------------------------

    public function showForgotPassword(array $params = []): void
    {
        $this->layout('auth', 'auth/forgot-password', [
            'pageTitle' => 'Forgot Password',
        ]);
    }

    public function handleForgotPassword(array $params = []): void
    {
        $input = $_POST;

        $validator = new Validator($input, [
            'email' => 'required|email',
        ]);

        if (!$validator->validate()) {
            Flash::setInputs($input);
            foreach ($validator->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    Flash::set('error', $error);
                }
            }
            $this->back();
        }

        $user = Database::selectOne(
            "SELECT `id`, `email`, `first_name` FROM `users` WHERE `email` = ? AND `deleted_at` IS NULL LIMIT 1",
            [strtolower(trim($input['email']))]
        );

        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

            // Invalidate any existing tokens for this user
            Database::query(
                "UPDATE `password_resets` SET `used_at` = NOW() WHERE `user_id` = ? AND `used_at` IS NULL",
                [$user['id']]
            );

            Database::insert('password_resets', [
                'user_id'    => (int) $user['id'],
                'token'      => $token,
                'expires_at' => $expiresAt,
            ]);

            // In a real application, an email would be sent here with the reset link.
            // For now we store the token and inform the user.
            error_log(
                "[R-DEIP Password Reset] Token for {$user['email']}: "
                . url("reset-password?token={$token}")
            );
        }

        // Always show the same message to avoid leaking whether the email exists
        CSRF::rotate();
        Flash::set('success', 'If an account with that email exists, a password reset link has been generated. Please check the application logs for the reset link (in production, this would be emailed).');
        $this->redirect('login');
    }

    // -------------------------------------------------------
    // Reset Password
    // -------------------------------------------------------

    public function showResetPassword(array $params = []): void
    {
        $token = $params['token'] ?? $_GET['token'] ?? '';

        if (empty($token)) {
            Flash::set('error', 'Invalid or missing reset token.');
            $this->redirect('forgot-password');
        }

        $reset = Database::selectOne(
            "SELECT * FROM `password_resets`
             WHERE `token` = ? AND `used_at` IS NULL AND `expires_at` > NOW()
             LIMIT 1",
            [$token]
        );

        if (!$reset) {
            Flash::set('error', 'This reset link is invalid or has expired. Please request a new one.');
            $this->redirect('forgot-password');
        }

        $this->layout('auth', 'auth/reset-password', [
            'pageTitle' => 'Reset Password',
            'token'     => e($token),
        ]);
    }

    public function handleResetPassword(array $params = []): void
    {
        $input = $_POST;

        $validator = new Validator($input, [
            'token'    => 'required',
            'password' => 'required|strong_password|confirmed',
        ]);

        if (!$validator->validate()) {
            Flash::setInputs(['token' => $input['token'] ?? '']);
            foreach ($validator->errors() as $field => $errors) {
                foreach ($errors as $error) {
                    Flash::set('error', $error);
                }
            }
            $this->back();
        }

        $reset = Database::selectOne(
            "SELECT * FROM `password_resets`
             WHERE `token` = ? AND `used_at` IS NULL AND `expires_at` > NOW()
             LIMIT 1",
            [$input['token']]
        );

        if (!$reset) {
            Flash::set('error', 'This reset link is invalid or has expired. Please request a new one.');
            $this->redirect('forgot-password');
        }

        try {
            Database::transaction(function () use ($input, $reset) {
                // Update user password
                Database::update(
                    'users',
                    [
                        'password' => password_hash($input['password'], PASSWORD_BCRYPT, ['cost' => 12]),
                    ],
                    '`id` = :where_id',
                    ['where_id' => (int) $reset['user_id']]
                );

                // Mark token as used
                Database::update(
                    'password_resets',
                    ['used_at' => date('Y-m-d H:i:s')],
                    '`id` = :where_id',
                    ['where_id' => (int) $reset['id']]
                );

                // Reset failed attempts
                Database::update(
                    'users',
                    [
                        'failed_login_attempts' => 0,
                        'locked_until' => null,
                    ],
                    '`id` = :where_id',
                    ['where_id' => (int) $reset['user_id']]
                );
            });

            CSRF::rotate();
            Flash::set('success', 'Your password has been reset successfully. Please sign in with your new password.');
            $this->redirect('login');
        } catch (Throwable $e) {
            error_log('[R-DEIP Auth] Password reset failed: ' . $e->getMessage());
            Flash::set('error', 'An error occurred while resetting your password. Please try again.');
            $this->back();
        }
    }

    // -------------------------------------------------------
    // Logout
    // -------------------------------------------------------

    public function logout(array $params = []): void
    {
        auth()->logout();
        Flash::set('success', 'You have been logged out successfully.');
        $this->redirect('login');
    }
}
