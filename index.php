<?php
// Segurança de Sessão (LGPD/Hardening)
session_set_cookie_params([
    'lifetime' => 86400, // 24 hours
    'path' => '/',
    'domain' => '', // Current domain
    'secure' => isset($_SERVER['HTTPS']), // Apenas HTTPS se disponível
    'httponly' => true, // Previne XSS (JavaScript não acessa o cookie)
    'samesite' => 'Strict' // Previne CSRF
]);
session_start();

// Simple Autoloader for our MVC mini-framework
spl_autoload_register(function ($class_name) {
    // Convert namespace separators to directory separators
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $class_name) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use Core\Router;

// Initial routing setup
$route = isset($_GET['route']) ? $_GET['route'] : '';
$route = trim($route, '/');

// Security check for non-login routes
if (!isset($_SESSION['user_id']) && $route !== 'login') {
    header('Location: index.php?route=login');
    exit;
}

$router = new Router();

// Auth Routes
$router->add('GET', 'login', 'AuthController', 'login');
$router->add('POST', 'login', 'AuthController', 'login');
$router->add('GET', 'logout', 'AuthController', 'logout');

// Patient/Dashboard Routes
$router->add('GET', '', 'PatientController', 'index');
$router->add('GET', 'patients', 'PatientController', 'index');
$router->add('GET', 'patient/add', 'PatientController', 'add');
$router->add('POST', 'patient/add', 'PatientController', 'add');
$router->add('GET', 'patient/edit', 'PatientController', 'edit');
$router->add('POST', 'patient/edit', 'PatientController', 'edit');
$router->add('GET', 'patient/delete', 'PatientController', 'delete');

// Calculator Routes
$router->add('GET', 'patient/{id}', 'CalculatorController', 'view');
$router->add('POST', 'api/calculate', 'CalculatorController', 'apiCalculate');

// Stats Routes
$router->add('GET', 'stats', 'StatsController', 'index');

// Audit Route
$router->add('GET', 'audit', 'AuditController', 'index');

// User Management Routes
$router->add('GET', 'users', 'UserController', 'index');
$router->add('GET', 'user/add', 'UserController', 'add');
$router->add('POST', 'user/add', 'UserController', 'add');
$router->add('GET', 'user/edit', 'UserController', 'edit');
$router->add('POST', 'user/edit', 'UserController', 'edit');
$router->add('GET', 'user/delete', 'UserController', 'delete');

// Profile Routes
$router->add('GET', 'profile', 'UserController', 'profile');
$router->add('POST', 'profile', 'UserController', 'profile');

// Dispatch
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($route, $method);
