<?php
declare(strict_types=1);

class GuestMiddleware extends Middleware
{
    public function handle(array $params, callable $next): void
    {
        if (auth()->check()) {
            Response::redirect(url('dashboard'));
        }

        $next($params);
    }
}
