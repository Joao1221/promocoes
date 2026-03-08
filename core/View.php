<?php
class View
{
    public static function render(string $view, array $data = [], string $layout = 'layouts/public'): void
    {
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../app/views/' . $layout . '.php';

        if (!file_exists($viewFile) || !file_exists($layoutFile)) {
            http_response_code(500);
            echo 'View not found.';
            return;
        }

        extract($data, EXTR_SKIP);
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        require $layoutFile;
    }
}
