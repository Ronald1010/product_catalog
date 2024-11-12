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
        $dbConnection = new DatabaseConnection();
        $this->db = $dbConnection->getConnection();
        $this->tokenValidator = new ValidateTokenProvider($this->db);
    }

    public function handleSellerRoute($uri, $method)
    {
        $sellerController = new SellerController($this->db, $this->tokenValidator);

        // Normalize URI for accurate matching
        $uri = str_replace('/seller', '', $uri);

        // Extract and validate token from the Authorization header
        $token = $this->getBearerToken();
        if (!$this->isTokenValid($token)) {
            $this->sendErrorResponse('Unauthorized', 401);
            return;
        }

        switch ($uri) {
            case '/products':
                if ($method === 'POST') {
                    $data = $_POST;
                    $files = $_FILES;
                    echo $sellerController->createProduct($data, $files, $token);
                } elseif ($method === 'GET') {
                    echo $sellerController->viewOwnProducts($token);
                }
                break;

            case preg_match('/\/products\/(\d+)\/details/', $uri, $matches) ? true : false:
                $productId = $matches[1];
                if ($method === 'PUT') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    echo $sellerController->updateProductDetails($productId, $data, $token);
                }
                break;

            case preg_match('/\/products\/(\d+)\/images/', $uri, $matches) ? true : false:
                $productId = $matches[1];
                if ($method === 'PUT') {
                    $files = $_FILES;
                    echo $sellerController->updateProductImages($productId, $files, $token);
                }
                break;

            case preg_match('/\/products\/(\d+)/', $uri, $matches) ? true : false:
                $productId = $matches[1];
                if ($method === 'DELETE') {
                    echo $sellerController->deleteProduct($productId, $token);
                }
                break;

            default:
                $this->sendErrorResponse('Route not found', 404);
                break;
        }
    }

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

    private function getBearerToken()
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
        if (!empty($authHeader) && strpos($authHeader, 'Bearer ') === 0) {
            return str_replace('Bearer ', '', $authHeader);
        }
        return null;
    }

    private function sendErrorResponse($message, $statusCode = 400)
    {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        http_response_code($statusCode);
    }
}
