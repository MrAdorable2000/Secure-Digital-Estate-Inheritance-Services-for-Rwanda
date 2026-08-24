<?php
declare(strict_types=1);

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    public function get(string $pattern, $handler, array $middleware = [], ?string $name = null): self
    {
        return $this->addRoute('GET', $pattern, $handler, $middleware, $name);
    }

    public function post(string $pattern, $handler, array $middleware = [], ?string $name = null): self
    {
        return $this->addRoute('POST', $pattern, $handler, $middleware, $name);
    }

    public function put(string $pattern, $handler, array $middleware = [], ?string $name = null): self
    {
        return $this->addRoute('PUT', $pattern, $handler, $middleware, $name);
    }

    public function delete(string $pattern, $handler, array $middleware = [], ?string $name = null): self
    {
        return $this->addRoute('DELETE', $pattern, $handler, $middleware, $name);
    }

    public function group(array $attributes, callable $callback): self
    {
        $prevPrefix = $GLOBALS['_route_prefix'] ?? '';
        $prevMw = $GLOBALS['_route_middleware'] ?? [];

        $GLOBALS['_route_prefix'] = $prevPrefix . '/' . ltrim($attributes['prefix'] ?? '', '/');
        $GLOBALS['_route_middleware'] = array_merge($prevMw, $attributes['middleware'] ?? []);

        $callback($this);

        $GLOBALS['_route_prefix'] = $prevPrefix;
        $GLOBALS['_route_middleware'] = $prevMw;

        return $this;
    }

    private function addRoute(string $method, string $pattern, $handler, array $middleware, ?string $name): self
    {
        $prefix = $GLOBALS['_route_prefix'] ?? '';
        $groupMw = $GLOBALS['_route_middleware'] ?? [];

        $full = '/' . trim($prefix . '/' . ltrim($pattern, '/'), '/');
        if ($full !== '/') $full = rtrim($full, '/');

        // Compile the regex pattern once at registration time
        $compiled = $this->compilePattern($full);

        $this->routes[] = [
            'method'     => $method,
            'pattern'    => $full,
            'compiled'   => $compiled['regex'],
            'paramNames' => $compiled['params'],
            'handler'    => $handler,
            'middleware' => array_merge($groupMw, $middleware),
        ];

        if ($name) {
            $this->namedRoutes[$name] = end($this->routes);
        }

        return $this;
    }

    /**
     * Convert a route pattern like "/users/{id}/edit" into a regex.
     * Uses # as delimiter to avoid conflicts with / in URLs.
     * Returns ['regex' => string, 'params' => string[]].
     */
    private function compilePattern(string $pattern): array
    {
        $paramNames = [];

        // Split pattern into segments by /
        $segments = explode('/', $pattern);
        $regexParts = [];

        foreach ($segments as $seg) {
            if ($seg === '') {
                $regexParts[] = '';
                continue;
            }

            // Check if this segment is a parameter: {name} or {name:constraint}
            if (preg_match('#^\{([a-zA-Z_][a-zA-Z0-9_]*)(?::([^}]+))?\}$#', $seg, $m)) {
                $paramNames[] = $m[1];
                $constraint = $m[2] ?? '[^/]+';
                $regexParts[] = '(' . $constraint . ')';
            } else {
                // Static segment — escape any regex special chars
                $regexParts[] = preg_quote($seg, '#');
            }
        }

        $regex = '#^' . implode('/', $regexParts) . '$#u';

        return ['regex' => $regex, 'params' => $paramNames];
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;

            $params = $this->match($route, $uri);
            if ($params !== false) {
                $this->pipeline($route['middleware'], $route['handler'], $params);
                return;
            }
        }

        Response::notFound();
    }

    private function match(array $route, string $uri)
    {
        if (!preg_match($route['compiled'], $uri, $matches)) {
            return false;
        }

        $result = [];
        foreach ($route['paramNames'] as $i => $name) {
            $result[$name] = $matches[$i + 1] ?? null;
        }

        return $result;
    }

    private function pipeline(array $middleware, $handler, array $params): void
    {
        // Core handler — the innermost callable that invokes the route handler
        $core = function (array $p) use ($handler) {
            $this->callHandler($handler, $p);
        };

        // Build the middleware chain from inside-out
        $next = $core;
        foreach (array_reverse($middleware) as $mw) {
            $next = function (array $p) use ($mw, $next) {
                if (is_string($mw)) {
                    (new $mw())->handle($p, $next);
                } elseif (is_callable($mw)) {
                    $mw($p, $next);
                }
                return;
            };
        }

        $next($params);
    }

    private function callHandler($handler, array $params): void
    {
        if (is_callable($handler) && !is_array($handler)) {
            $handler($params);
            return;
        }

        if (is_array($handler)) {
            [$class, $method] = $handler;
            $obj = is_string($class) ? new $class() : $class;

            if (!method_exists($obj, $method)) {
                throw new RuntimeException('Method ' . $method . ' does not exist on ' . get_class($obj));
            }

            $obj->$method($params);
            return;
        }

        throw new RuntimeException('Invalid route handler');
    }
}
