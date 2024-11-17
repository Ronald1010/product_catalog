<?php

namespace Routes\SellerRoute;

use App\Controllers\SellerController;
use App\Providers\Validation\ValidateTokenProvider;
use Config\DatabaseConnection;

class SellerRoute
{
    private $db;
    private $tokenValidator;

    public function __construct()
    {
        // Initialize database connection and token validator
        $dbConnection = new DatabaseConnection();
        $this->db = $dbConnection->getConnection();
        $this->tokenValidator = new ValidateTokenProvider($this->db);
    }

    // Main route handler for seller actions
    public function handleSellerRoute($uri, $method)
    {
        // Initialize the SellerController
        $sellerController = new SellerController($this->db, $this->tokenValidator);

        // Normalize the URI by removing the '/seller' part for accurate matching
        $uri = str_replace('/seller', '', $uri);

        // Extract and validate the token from the Authorization header
        $token = $this->getBearerToken();
        if (!$this->isTokenValid($token)) {
            // If token is invalid, send an error response
            $this->sendErrorResponse('Unauthorized', 401);
            return;
        }

        // Handle the different routes based on the URI and HTTP method
        switch ($uri) {
                // Case to create a product (POST request)
            case '/products':
                if ($method === 'POST') {
                    $data = $_POST;  // Get data from the request body
                    $files = $_FILES; // Get files from the request body
                    echo $sellerController->createProduct($data, $files, $token); // Create the product
                } elseif ($method === 'GET') {
                    // If GET request, show the products of the seller
                    echo $sellerController->viewOwnProducts($token);
                }
                break;

                // Case to update product details (PUT request) for a specific product ID
            case preg_match('/\/products\/(\d+)\/details/', $uri, $matches) ? true : false:
                $productId = $matches[1];  // Extract the product ID from the URI
                if ($method === 'PUT') {
                    $data = json_decode(file_get_contents('php://input'), true);  // Get data from the request body
                    // Update the product details using the controller
                    echo $sellerController->updateProductDetails($productId, $data, $token);
                }
                break;

                // Case to update product images (PUT request) for a specific product ID
            case preg_match('/\/products\/(\d+)\/images/', $uri, $matches) ? true : false:
                $productId = $matches[1];  // Extract the product ID from the URI
                if ($method === 'PUT') {
                    $files = $_FILES;  // Get files from the request body
                    // Update the product images using the controller
                    echo $sellerController->updateProductImages($productId, $files, $token);
                }
                break;

                // Case to delete a product (DELETE request) for a specific product ID
            case preg_match('/\/products\/(\d+)/', $uri, $matches) ? true : false:
                $productId = $matches[1];  // Extract the product ID from the URI
                if ($method === 'DELETE') {
                    // Delete the product using the controller
                    echo $sellerController->deleteProduct($productId, $token);
                }
                break;
                // Case to view a specific product (GET request) for a specific product ID
            case preg_match('/\/products\/(\d+)/', $uri, $matches) ? true : false:
                $productId = $matches[1];  // Extract the product ID from the URI
                if ($method === 'GET') {
                    // View the specific product using the controller
                    echo $sellerController->viewSpecificProduct($productId, $token);
                }
                break;

                // Default case for unhandled routes
            default:
                $this->sendErrorResponse('Route not found', 404);
                break;
        }
    }

    // Validate the provided token
    private function isTokenValid($token)
    {
        try {
            $this->tokenValidator->validateToken($token);
            return true;
        } catch (\Exception $e) {
            error_log('Token validation failed: ' . $e->getMessage());
            return false;
        }
    }

    // Extract the bearer token from the Authorization header
    private function getBearerToken()
    {
        // Get the token from either 'HTTP_AUTHORIZATION' or 'REDIRECT_HTTP_AUTHORIZATION'
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (!empty($authHeader) && strpos($authHeader, 'Bearer ') === 0) {
            return str_replace('Bearer ', '', $authHeader);  // Return the token
        }
        return null;  // Return null if no token is found
    }

    // Send an error response with a message and status code
    private function sendErrorResponse($message, $statusCode = 400)
    {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        http_response_code($statusCode);  // Set the HTTP response code
    }
}
