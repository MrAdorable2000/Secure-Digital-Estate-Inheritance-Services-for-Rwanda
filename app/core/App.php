<?php
declare(strict_types=1);

class App
{
    private static ?App $instance = null;

    public Request $request;
    public Router $router;
    public array $config;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->request = new Request();
        // Router is set externally after routes are loaded
        $this->router = new Router();
    }

    public static function getInstance(array $config = []): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function run(): void
    {
        // CSRF check for non-GET requests
        $method = $this->request->method;
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            if (!CSRF::verify()) {
                if ($this->request->isAjax()) {
                    Response::json(['success' => false, 'error' => 'CSRF token mismatch. Please refresh and try again.'], 419);
                }
                Response::error(419, 'Your session has expired. Please refresh the page and try again.');
            }
        }

        // Session timeout check for authenticated users
        if (isset($_SESSION['user_id'])) {
            $timeout = $this->config['session']['timeout'] ?? 3600;
            $lastActivity = $_SESSION['last_activity'] ?? $_SESSION['login_at'] ?? time();
            if (time() - $lastActivity > $timeout) {
                session_unset();
                session_regenerate_id(true);
                Flash::set('warning', 'Your session has expired due to inactivity. Please log in again.');
                Response::redirect(url('login'));
            }
            $_SESSION['last_activity'] = time();
        }

        $this->router->dispatch($this->request->method, $this->request->path);
    }

    public function isDev(): bool
    {
        return ($this->config['app']['env'] ?? 'production') === 'development';
    }
}
