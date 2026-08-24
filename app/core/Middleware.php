<?php
declare(strict_types=1);

abstract class Middleware
{
    abstract public function handle(array $params, callable $next): void;
}
