<?php
declare(strict_types=1);

/*
|-------------------------------------------------------------
| R-DEIP Routes — Phase 1
|-------------------------------------------------------------
*/

// Public pages
$router->get('/', [HomeController::class, 'index']);
$router->get('/about', [HomeController::class, 'about']);
$router->get('/services', [HomeController::class, 'services']);
$router->get('/how-it-works', [HomeController::class, 'howItWorks']);
$router->get('/contact', [HomeController::class, 'contact']);

// Guest-only (auth) routes
$router->get('/login', [AuthController::class, 'showLogin'], [GuestMiddleware::class]);
$router->post('/login', [AuthController::class, 'handleLogin'], [GuestMiddleware::class]);
$router->get('/register', [AuthController::class, 'showRegister'], [GuestMiddleware::class]);
$router->post('/register', [AuthController::class, 'handleRegister'], [GuestMiddleware::class]);
$router->get('/forgot-password', [AuthController::class, 'showForgotPassword'], [GuestMiddleware::class]);
$router->post('/forgot-password', [AuthController::class, 'handleForgotPassword'], [GuestMiddleware::class]);
$router->get('/reset-password/{token}', [AuthController::class, 'showResetPassword'], [GuestMiddleware::class]);
$router->post('/reset-password', [AuthController::class, 'handleResetPassword'], [GuestMiddleware::class]);

// Authenticated routes
$router->get('/dashboard', [DashboardController::class, 'index'], [AuthMiddleware::class]);
$router->post('/logout', [AuthController::class, 'logout'], [AuthMiddleware::class]);

// User management (admin only)
$router->get('/users', [UserController::class, 'index'], [AuthMiddleware::class, new RBACMiddleware(['users.view'])]);
$router->get('/users/create', [UserController::class, 'create'], [AuthMiddleware::class, new RBACMiddleware(['users.create'])]);
$router->post('/users', [UserController::class, 'store'], [AuthMiddleware::class, new RBACMiddleware(['users.create'])]);
$router->get('/users/{id}/edit', [UserController::class, 'edit'], [AuthMiddleware::class, new RBACMiddleware(['users.edit'])]);
$router->post('/users/{id}/update', [UserController::class, 'update'], [AuthMiddleware::class, new RBACMiddleware(['users.edit'])]);
$router->post('/users/{id}/suspend', [UserController::class, 'suspend'], [AuthMiddleware::class, new RBACMiddleware(['users.suspend'])]);
$router->post('/users/{id}/activate', [UserController::class, 'activate'], [AuthMiddleware::class, new RBACMiddleware(['users.activate'])]);

// Audit logs
$router->get('/audit', [AuditController::class, 'index'], [AuthMiddleware::class, new RBACMiddleware(['audit.view'])]);

// Profile
$router->get('/profile', [ProfileController::class, 'index'], [AuthMiddleware::class]);
$router->post('/profile', [ProfileController::class, 'update'], [AuthMiddleware::class]);
$router->post('/profile/password', [ProfileController::class, 'changePassword'], [AuthMiddleware::class]);
