<?php
declare(strict_types=1);

$appConfig = require __DIR__ . '/../config/app.php';
date_default_timezone_set($appConfig['timezone']);

require __DIR__ . '/../core/helpers.php';
require __DIR__ . '/../core/Database.php';
require __DIR__ . '/../core/Model.php';
require __DIR__ . '/../core/View.php';
require __DIR__ . '/../core/Controller.php';
require __DIR__ . '/../core/Router.php';
require __DIR__ . '/../core/Session.php';
require __DIR__ . '/../core/Csrf.php';
require __DIR__ . '/../core/Validator.php';
require __DIR__ . '/../core/Auth.php';
require __DIR__ . '/../core/Upload.php';
require __DIR__ . '/../app/middlewares/AuthMiddleware.php';

foreach (glob(__DIR__ . '/../app/models/*.php') as $file) {
    require $file;
}

foreach (glob(__DIR__ . '/../app/controllers/*.php') as $file) {
    require $file;
}

Session::start();

$router = new Router();
$router->get('/', [HomeController::class, 'index']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/cadastro', [AuthController::class, 'showRegister']);
$router->post('/cadastro', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/buscar', [ProductController::class, 'search']);
$router->get('/buscar/sugestoes', [ProductController::class, 'suggestions']);
$router->post('/chat/perguntar', [ChatbotController::class, 'ask']);
$router->get('/chat/perguntar', [ChatbotController::class, 'ask']);
$router->get('/produto/{slug}', [ProductController::class, 'show']);
$router->get('/loja/{slug}', [StoreController::class, 'show']);

$router->post('/carrinho/adicionar', [CartController::class, 'add']);
$router->get('/carrinho', [CartController::class, 'show']);
$router->post('/carrinho/remover/{id}', [CartController::class, 'remove']);

$router->get('/checkout', [OrderController::class, 'checkout']);
$router->post('/checkout', [OrderController::class, 'store']);

$router->get('/lojista', [VendorController::class, 'dashboard']);
$router->get('/lojista/pedidos/{id}', [VendorController::class, 'orderDetail']);
$router->get('/lojista/loja', [StoreController::class, 'createForm']);
$router->post('/lojista/loja', [StoreController::class, 'save']);
$router->get('/lojista/produtos/novo', [ProductController::class, 'vendorForm']);
$router->get('/lojista/produtos/{id}/editar', [ProductController::class, 'vendorForm']);
$router->post('/lojista/produtos', [ProductController::class, 'save']);

$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/destaques', [AdminController::class, 'highlights']);
$router->get('/admin/categorias', [AdminController::class, 'categories']);
$router->post('/admin/categorias/icones/aplicar', [AdminController::class, 'applyCategoryIcons']);
$router->post('/admin/lojas/{id}/aprovar', [AdminController::class, 'approveStore']);
$router->post('/admin/produtos/{id}/aprovar', [AdminController::class, 'approveProduct']);
$router->post('/admin/lojas/{id}/destaque', [AdminController::class, 'setStoreFeatured']);
$router->post('/admin/produtos/{id}/destaque', [AdminController::class, 'setProductFeatured']);
$router->post('/admin/destaques/home-produtos', [AdminController::class, 'saveHomeFeaturedProducts']);
$router->post('/admin/categorias', [AdminController::class, 'storeCategory']);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$prefixes = array_unique([
    rtrim(config('app')['base_url'], '/'),
    rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/'),
    rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'] ?? '')), '/'),
]);

usort($prefixes, static fn (string $a, string $b) => strlen($b) <=> strlen($a));

foreach ($prefixes as $prefix) {
    if ($prefix !== '' && $prefix !== '.' && str_starts_with($requestUri, $prefix)) {
        $requestUri = substr($requestUri, strlen($prefix)) ?: '/';
        break;
    }
}

$requestUri = preg_replace('#^/(public/)?index\.php#', '', $requestUri);
$requestUri = $requestUri ?: '/';
$requestUri = '/' . ltrim($requestUri, '/');
$requestUri = $requestUri === '//' ? '/' : (rtrim($requestUri, '/') ?: '/');

$router->dispatch($_SERVER['REQUEST_METHOD'], $requestUri);
