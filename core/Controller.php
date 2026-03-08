<?php
abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'layouts/public'): void
    {
        $data['authUser'] = Auth::user();
        $data['flash'] = Session::getFlash();
        $data['cartCount'] = count(Session::get('cart', []));
        $data['app'] = config('app');
        View::render($view, $data, $layout);
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }
}
