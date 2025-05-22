<?php

$router->get('/', 'index.php')->only('refresh_roles');
$router->get('/about', 'about.php');
$router->get('/contact', 'contact.php');

//Profile page
$router->get('/profile', 'ProfileController', 'show')->middleware('auth');

// Authentication Routes
$router->get('/register', 'RegistrationController', 'create')->only('guest');
$router->post('/register', 'RegistrationController', 'store')->only('guest');

$router->get('/login', 'SessionController', 'create')->only('guest');
$router->post('/session', 'SessionController', 'store')->only('guest');
$router->delete('/session', 'SessionController', 'destroy')->only('auth');

// 2FA verification routes
$router->get('/verify', 'VerificationController', 'show')->only('guest');
$router->post('/verify', 'VerificationController', 'verify')->only('guest');

// Users
$router->get('/users', 'UserController', 'index')->middleware('auth', 'role:admin');
$router->get('/user/{id}', 'UserController', 'show')->middleware('auth', 'role:admin');

// Update user password
$router->patch('/users/{id}/password', 'UserController', 'updatePassword')->middleware('auth', 'role:admin');
// Update user username
$router->patch('/users/{id}/username', 'UserController', 'updateUsername')->middleware('auth', 'role:admin');
// Delete user
$router->delete('/users/{id}', 'UserController', 'deleteUser')->middleware('auth', 'role:admin');


// JIT Access
$router->get('/resources', 'ResourceController', 'index')->middleware('auth', 'refresh_roles', 'jit_access');

$router->get('/resources/{folder}/{file}', 'DownloadController', 'download')->middleware('auth', 'refresh_roles', 'jit_access');

// Grant and Revoke JIT Access
$router->post('/users/{id}/jit-access', 'UserController', 'grantJITAccess')->middleware('auth', 'role:admin');
$router->delete('/users/{id}/jit-access', 'UserController', 'revokeJITAccess')->middleware('auth', 'role:admin');

