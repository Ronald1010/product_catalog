<?php

namespace Routes\UserRoute;

use App\Controllers\UserController;
use App\Providers\Auth\JWTProvider;
use Config\DatabaseConnection;

require_once __DIR__ . '/../config/DatabaseConnection.php';

class UserRoute {

    private $jwtProvider;

    public function __construct() {
        $this->jwtProvider = new JWTProvider(); // Initialize JWTProvider
    }

    public function handleUserRoute($uri, $method) {
        // Initialize the database connection
        $dbConnection = new DatabaseConnection();
        $db = $dbConnection->getConnection();

        // Initialize the UserController
        $userController = new UserController($db);

        // Normalize the URI for accurate matching
        $uri = str_replace('/user', '', $uri);

        // Debugging - Output the current URI after normalization
        error_log("User Route Handling for URI: $uri");

        // Define user-related routes
        switch ($uri) {
            case '/register': // POST - Register a new user
                if ($method === 'POST') {
                    error_log("Handling User Registration");
                    $data = json_decode(file_get_contents('php://input'), true);
                    $userController->createUser($data);
                }
                break;

            case '/verify': // GET - Verify user email
                if ($method === 'GET') {
                    error_log("Handling Email Verification");
                    $token = $_GET['token'] ?? '';
                    $userController->verifyEmail($token);
                }
                break;

            case '/request-password-reset': // POST - Request OTP for password reset
                if ($method === 'POST') {
                    error_log("Handling Password Reset Request");
                    $data = json_decode(file_get_contents('php://input'), true);
                    $userController->requestPasswordReset($data['email']);
                }
                break;

            case '/reset-password': // PUT - Verify OTP and reset password
                if ($method === 'PUT') {
                    error_log("Handling Password Reset");
                    $data = json_decode(file_get_contents('php://input'), true);
                    $userController->resetPassword($data);
                }
                break;

            default:
                // Routes that require authentication (get, update, delete user)
                $this->handleAuthenticatedRoutes($uri, $method, $userController);
                break;
        }
    }

    private function handleAuthenticatedRoutes($uri, $method, $userController) {
        // Check if the Authorization header exists
        $token = $this->getBearerToken();
        if (!$token) {
            $this->sendErrorResponse('Authorization token is missing.', 401);
            return;
        }

        // Verify the token and decode it
        $decoded = $this->jwtProvider->verifyToken($token);
        if (!$decoded) {
            $this->sendErrorResponse('Invalid token.', 401);
            return;
        }

        // For authenticated routes: GET (view user), PUT (update user), DELETE (delete user)
        switch ($uri) {
            case '': // Empty string after '/user'
                if ($method === 'GET') {
                    $userController->getUserDetails($token);  // View user details
                } elseif ($method === 'PUT') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $userController->updateUserDetails($data, $token);  // Update user details
                } elseif ($method === 'DELETE') {
                    $data = json_decode(file_get_contents('php://input'), true);
                    $userController->deleteUser($data, $token);  // Delete user with confirmation
                }
                break;

            default:
                $this->sendErrorResponse('Route not found', 404);
                break;
        }
    }

    // Extract Bearer token from Authorization header
    private function getBearerToken() {
        // Ensure Authorization header is captured in cases where it might be set differently
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $_SERVER['HTTP_AUTHORIZATION'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        // Get the Authorization header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!empty($authHeader) && strpos($authHeader, 'Bearer ') !== false) {
            return str_replace('Bearer ', '', $authHeader); // Return the token part
        }

        return null; // No valid token found
    }

    // Send a JSON error response
    private function sendErrorResponse($message, $statusCode = 400) {
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        http_response_code($statusCode);
    }
}
