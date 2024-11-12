<?php

// Include necessary route files
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/routes/UserRoute.php';
require_once __DIR__ . '/routes/AuthRoute.php';
require_once __DIR__ . '/routes/AdminRoute.php';
require_once __DIR__ . '/routes/SellerRoute.php';
require_once __DIR__ . '/routes/BuyerRoute.php';
require_once __DIR__ . '/routes/ProductRoute.php';

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

// Direct route for email verification
if ($uri === '/verify') {
    error_log("Routing to UserRoute for email verification");
    (new \Routes\UserRoute\UserRoute())->handleUserRoute($uri, $method);
}

// Route for admin-related actions
elseif (strpos($uri, '/admin') === 0) {
    (new \Routes\AdminRoute\AdminRoute())->handleAdminRoute($uri, $method);
}

// Route for user-related actions
elseif (strpos($uri, '/user') === 0) {
    (new \Routes\UserRoute\UserRoute())->handleUserRoute($uri, $method);
}

// Route for authentication-related actions
elseif (strpos($uri, '/auth') === 0) {
    (new \Routes\AuthRoute\AuthRoute())->handleAuthRoute($uri, $method);
}

// Route for product-related actions
elseif (strpos($uri, '/products') === 0 || strpos($uri, '/search') === 0) {
    (new \Routes\ProductRoute\ProductRoute())->handleProductRoute($uri, $method);
}

// Route for buyer-related actions
elseif (strpos($uri, '/buyer') === 0) {
    (new \Routes\BuyerRoute\BuyerRoute())->handleBuyerRoute($uri, $method);
}

// Route for seller-related actions
elseif (strpos($uri, '/seller') === 0) {
    (new \Routes\SellerRoute\SellerRoute())->handleSellerRoute($uri, $method);
}

// Route for search-related actions
else if (strpos($uri, '/search') === 0) {
    (new \Routes\SearchRoute\SearchRoute())->handleSearchRoute($uri, $method);
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
