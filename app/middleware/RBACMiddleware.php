<?php
declare(strict_types=1);

class RBACMiddleware extends Middleware
{
    private array $requirements;

    public function __construct(array $requirements = [])
    {
        $this->requirements = $requirements;
    }

    public function handle(array $params, callable $next): void
    {
        // Must be authenticated first
        if (auth()->guest()) {
            Flash::set('warning', 'Please log in to access this page.');
            Response::redirect(url('login'));
        }

        if (empty($this->requirements)) {
            $next($params);
            return;
        }

        foreach ($this->requirements as $requirement) {
            // Role requirement: prefixed with 'role:'
            if (str_starts_with($requirement, 'role:')) {
                $role = substr($requirement, 5);
                if (!auth()->hasRole($role)) {
                    Response::error(403, 'You do not have the required role to perform this action.');
                }
            } else {
                // Permission requirement
                if (!auth()->can($requirement)) {
                    Response::error(403, 'You do not have permission to perform this action.');
                }
            }
        }

        $next($params);
    }
}
