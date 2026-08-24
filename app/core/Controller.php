<?php
declare(strict_types=1);

class Controller
{
    protected function view(string $path, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $viewFile = dirname(__DIR__) . '/views/' . $path . '.php';

        if (!file_exists($viewFile)) {
            Response::error(500, 'View not found: ' . $path);
        }

        include $viewFile;
    }

    protected function layout(string $layoutName, string $viewPath, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $contentFile = dirname(__DIR__) . '/views/' . $viewPath . '.php';
        $layoutFile = dirname(__DIR__) . '/views/layouts/' . $layoutName . '.php';

        if (!file_exists($contentFile)) {
            Response::error(500, 'View not found: ' . $viewPath);
        }

        ob_start();
        include $contentFile;
        $content = ob_get_clean();

        if (!file_exists($layoutFile)) {
            Response::error(500, 'Layout not found: ' . $layoutName);
        }

        include $layoutFile;
    }

    protected function json(array $data, int $status = 200): void
    {
        Response::json($data, $status);
    }

    protected function redirect(string $path): void
    {
        Response::redirect(url($path));
    }

    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        Response::redirect($referer);
    }
}
