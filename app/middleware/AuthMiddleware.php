<?php
declare(strict_types=1);

class AuthMiddleware extends Middleware
{
    public function handle(array $params, callable $next): void
    {
        if (auth()->guest()) {
            Flash::set('warning', 'Please log in to access this page.');
            Response::redirect(url('login'));
        }

        $next($params);
    }
}
