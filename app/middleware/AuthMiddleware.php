<?php

namespace App\Middleware;

use App\Providers\Validation\ValidateTokenProvider;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    private $validateTokenProvider;

    public function __construct($db)
    {
        $this->validateTokenProvider = new ValidateTokenProvider($db); // Pass DB to Token Provider
    }

    public function handle($request, $next)
    {
    // Fetch the Authorization header
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';

    // Log the Authorization header for debugging
    error_log('Authorization Header in Middleware: ' . $authHeader);

    // Check if the token is present
    if (empty($authHeader) || strpos($authHeader, 'Bearer ') !== 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Authorization token not found'
        ]);
        http_response_code(401);
        return false;
    }

    // Extract the JWT token from the Authorization header
    $jwtToken = str_replace('Bearer ', '', $authHeader);

    // Validate the token
    try {
        $decoded = $this->validateTokenProvider->validateToken($jwtToken);

        // Pass the Authorization token to the next action
        return $next($request, $jwtToken); // Pass the token here
    } catch (\Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);
        http_response_code(401);
        return false;
    }
}

}
