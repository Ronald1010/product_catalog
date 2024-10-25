<?php

namespace Routes\AdminRoute;

use App\Controllers\AdminController;
use Config\DatabaseConnection;
use App\Providers\Auth\JWTProvider;

require_once __DIR__ . '/../config/DatabaseConnection.php';

class AdminRoute {

    private $jwtProvider;

    public function __construct()
    {
        $this->jwtProvider = new JWTProvider(); // Initialize the JWTProvider
    }

    public function handleAdminRoute($uri, $method) {
        // Initialize the database connection
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();

        // Initialize the AdminController
        $adminController = new AdminController($db);

        // Ensure Authorization header is captured
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        // Get the Authorization header (Bearer token)
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        error_log("Authorization Header: " . $authHeader); // Log the header for debugging

        if (empty($authHeader)) {
            $this->sendErrorResponse('Authorization token is missing.');
            return;
        }

        // Extract the token from the header
        $token = str_replace('Bearer ', '', $authHeader);

        // Normalize the URI for accurate matching
        $uri = str_replace('/admin', '', $uri);

        // Define admin-related routes
        switch (true) {
            case ($uri === '/users' && $method === 'GET'): // GET - List all users
                error_log("Handling Admin: List Users");
                $adminController->listUsers($token);  // Pass the token
                break;

            case (preg_match('/^\/users\/([a-z0-9\-]+)$/', $uri, $matches) && $method === 'GET'): // GET - View user details by UUID
                $uuid = $matches[1];
                error_log("Handling Admin: View User");
                $adminController->readUser($uuid, $token);  // Pass the token
                break;

            case (preg_match('/^\/users\/([a-z0-9\-]+)$/', $uri, $matches) && $method === 'PUT'): // PUT - Update user by UUID
                $uuid = $matches[1];
                error_log("Handling Admin: Update User");
                $data = json_decode(file_get_contents('php://input'), true);
                $adminController->updateUser($data, $uuid, $token);  // Pass the token
                break;

            case (preg_match('/^\/users\/([a-z0-9\-]+)$/', $uri, $matches) && $method === 'DELETE'): // DELETE - Delete user by UUID
                $uuid = $matches[1];
                error_log("Handling Admin: Delete User");
                $adminController->deleteUser($uuid, $token);  // Pass the token
                break;

            default:
                error_log("No matching admin route found for URI: $uri");
                $this->sendErrorResponse('Route not found.', 404);
                break;
        }
    }

    // Utility function to send an error response
    private function sendErrorResponse($message, $statusCode = 401) {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        http_response_code($statusCode);
    }
}
