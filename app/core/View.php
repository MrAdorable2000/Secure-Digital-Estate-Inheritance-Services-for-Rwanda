<?php
declare(strict_types=1);

class View
{
    public static function render(string $path, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $file = dirname(__DIR__) . '/views/' . $path . '.php';

        if (file_exists($file)) {
            include $file;
        } else {
            throw new RuntimeException('View file not found: ' . $path);
        }
    }

    public static function renderLayout(string $layout, string $view, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }

        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';
        $layoutFile = dirname(__DIR__) . '/views/layouts/' . $layout . '.php';

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        }
        $content = ob_get_clean();

        if (file_exists($layoutFile)) {
            include $layoutFile;
        }
    }
}
