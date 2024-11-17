<?php

// Include necessary route files
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/routes/UserRoute.php';
require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/AdminRoute.php';
require_once __DIR__ . '/routes/SellerRoute.php';
require_once __DIR__ . '/routes/ProductRoute.php';
require_once __DIR__ . '/routes/SearchRoute.php';  // Include the search route file

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

// Route for email verification
if ($uri === '/verify') {
    error_log("Routing to UserRoute for email verification");
    (new \Routes\UserRoute\UserRoute())->handleUserRoute($uri, $method);
}

// Grouped Routes for Admin-related actions
elseif (strpos($uri, '/admin') === 0) {
    error_log("Routing to AdminRoute");
    (new \Routes\AdminRoute\AdminRoute())->handleAdminRoute($uri, $method);
}

// Grouped Routes for User-related actions
elseif (strpos($uri, '/user') === 0) {
    error_log("Routing to UserRoute for user actions");
    (new \Routes\UserRoute\UserRoute())->handleUserRoute($uri, $method);
}

// Grouped Routes for Authentication-related actions
elseif (strpos($uri, '/auth') === 0) {
    error_log("Routing to AuthRoute for authentication");
    (new \Routes\AuthRoute\AuthRoute())->handleAuthRoute($uri, $method);
}

// Grouped Routes for Product-related actions
elseif (strpos($uri, '/products') === 0) {
    error_log("Routing to ProductRoute for product-related actions");
    (new \Routes\ProductRoute\ProductRoute())->handleProductRoute($uri, $method);
}

// Grouped Routes for Seller-related actions
elseif (strpos($uri, '/seller') === 0) {
    error_log("Routing to SellerRoute for seller-related actions");
    (new \Routes\SellerRoute\SellerRoute())->handleSellerRoute($uri, $method);
}

// Grouped Routes for Search-related actions
elseif (strpos($uri, '/search') === 0) {
    error_log("Routing to SearchRoute for search-related actions");
    (new \Routes\SearchRoute\SearchRoute($db))->handleSearchRoute($uri, $method);
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
