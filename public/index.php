<?php
session_start();
define('BASE_PATH', dirname(__DIR__));

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

set_exception_handler(function ($e) {
    error_log($e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    http_response_code(500);
    if (ini_get('display_errors')) {
        echo "<h1>Fatal Error</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<p>File: " . $e->getFile() . " on line " . $e->getLine() . "</p>";
    } else {
        echo "<h1>500 Internal Server Error</h1>";
        echo "<p>Something went wrong on our end. Please check the logs.</p>";
    }
    exit;
});

require_once __DIR__ . '/../core/Autoloader.php';
require_once __DIR__ . '/../core/Blade.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/auth.php';

$router = new Router();

$auth = new AuthController($conn, $blade);
$productCtrl = new ProductController($conn, $blade);
$suppliers = new SupplierController($conn, $blade);

$router->get('/login', [$auth, 'showLogin']);
$router->post('/login', [$auth, 'login']);
$router->get('/logout', [$auth, 'logout']);

$router->get('/', [$productCtrl, 'index']);
$router->get('/products', [$productCtrl, 'index']);

$router->post('/products/create', [$productCtrl, 'create']);
$router->get('/products/edit', [$productCtrl, 'edit']);
$router->post('/products/edit', [$productCtrl, 'edit']);
$router->post('/products/delete', [$productCtrl, 'delete']);
$router->get('/products/search', [$productCtrl, 'search']);
$router->post('/products/search', [$productCtrl, 'search']);

$router->get('/suppliers', [$suppliers, 'index']);
$router->post('/suppliers/create', [$suppliers, 'create']);
$router->get('/suppliers/edit', [$suppliers, 'edit']);
$router->post('/suppliers/edit', [$suppliers, 'edit']);
$router->post('/suppliers/delete', [$suppliers, 'delete']);

$router->get('/api/low-stock', [$productCtrl, 'getLowStock']);

$router->resolve();