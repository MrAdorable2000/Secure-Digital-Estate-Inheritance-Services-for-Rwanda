<?php
declare(strict_types=1);

class Request
{
    public string $method;
    public string $path;
    public array $query;
    public array $post;
    public array $files;
    public array $server;
    public array $headers;
    public string $ip;
    public string $userAgent;

    public function __construct()
    {
        $this->server = $_SERVER;
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->path = '/' . ltrim($this->path, '/');
        $this->query = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->headers = $this->extractHeaders();
    }

    private function extractHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headers[str_replace(' ', '-', ucwords(strtolower(substr($key, 5)), '_'))] = $value;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
        }
        return $headers;
    }

    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->query[$key]);
    }

    public function only(array $keys): array
    {
        return array_intersect_key($this->post, array_flip($keys));
    }

    public function except(array $keys): array
    {
        return array_diff_key($this->post, array_flip($keys));
    }

    public function bearerToken(): ?string
    {
        $header = $this->headers['Authorization'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }

    public function isAjax(): bool
    {
        return ($this->headers['X-Requested-With'] ?? '') === 'XMLHttpRequest';
    }

    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    public function json(): array
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}
