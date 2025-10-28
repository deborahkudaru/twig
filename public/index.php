<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\Router;
use App\Utils\Session;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\TicketController;

Session::init();

$router = new Router();

// Public routes
$router->add('GET', '/', [HomeController::class, 'index']);
$router->add('GET', '/auth/login', [AuthController::class, 'showLogin']);
$router->add('POST', '/auth/login', [AuthController::class, 'login']);
$router->add('GET', '/auth/signup', [AuthController::class, 'showSignup']);
$router->add('POST', '/auth/signup', [AuthController::class, 'signup']);
$router->add('POST', '/auth/logout', [AuthController::class, 'logout']);

// Protected routes
$router->add('GET', '/dashboard', [DashboardController::class, 'index']);
$router->add('GET', '/tickets', [TicketController::class, 'index']);
$router->add('POST', '/tickets/create', [TicketController::class, 'create']);
$router->add('POST', '/tickets/update', [TicketController::class, 'update']);
$router->add('POST', '/tickets/delete', [TicketController::class, 'delete']);

$router->dispatch();
