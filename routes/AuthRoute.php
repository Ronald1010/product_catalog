<?php

namespace Routes\AuthRoute;

use App\Controllers\AuthController;
use Config\DatabaseConnection;
use App\Middleware\AuthMiddleware;

require_once __DIR__ . '/../config/DatabaseConnection.php';

class AuthRoute
{
    public function handleAuthRoute($uri, $method)
    {
        // Initialize the database connection
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();

        // Initialize the AuthController
        $authController = new AuthController($db);

        // Initialize Auth Middleware for JWT validation (pass the database connection)
        $authMiddleware = new AuthMiddleware($db);

        // Normalize the URI for accurate matching
        $uri = str_replace('/auth', '', $uri);

        // Debugging - Output the current URI after normalization
        error_log("Auth Route Handling for URI: $uri");

        // Define auth-related routes
        switch ($uri) {
            case '/login': // POST - User login
                if ($method === 'POST') {
                    error_log("Handling User Login");
                    $data = json_decode(file_get_contents('php://input'), true);
                    $authController->login($data);
                }
                break;

                case '/logout': // POST - User logout
                    if ($method === 'POST') {
                        error_log("Handling User Logout");
                        $authMiddleware->handle($_SERVER, function ($request, $jwtToken) use ($authController) {
                            // Pass the token to the logout function
                            $authController->logout($jwtToken);
                        });
                    }
                break;

            default:
                error_log("No matching auth route found for URI: $uri");
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Route not found'
                ]);
                http_response_code(404);
                break;
        }
    }
}
