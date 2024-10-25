<?php

// Include necessary route files
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/routes/UserRoute.php';
require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/AdminRoute.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// Capture the request URI and method
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Debugging - Output the request URI and method for troubleshooting
error_log("Request URI: $uri");
error_log("Request Method: $method");

// Remove the base path '/admin/api.php' from the URI
$uri = str_replace('/admin/api.php', '', $uri);

// Debugging - Log after removing the base path
error_log("Normalized URI: $uri");

// Route for admin-related actions
if (strpos($uri, '/admin') === 0) {
    // Delegate to the admin route handler
    (new \Routes\AdminRoute\AdminRoute())->handleAdminRoute($uri, $method);
}

// Route for user-related actions
else if (strpos($uri, '/user') === 0) {
    // Delegate to the user route handler
    (new \Routes\UserRoute\UserRoute())->handleUserRoute($uri, $method);
} 
// Route for authentication-related actions
else if (strpos($uri, '/auth') === 0) {
    // Delegate to the auth route handler
    (new \Routes\AuthRoute\AuthRoute())->handleAuthRoute($uri, $method);
} 
else if (strpos($uri, '/verify') === 0) {
    (new \Routes\UserRoute\UserRoute())->handleUserRoute($uri, $method);
}
// Route not found
else {
    error_log("No matching route found for URI: $uri");
    echo json_encode([
        'status' => 'error',
        'message' => 'Route not found'
    ]);
    http_response_code(404);
}
