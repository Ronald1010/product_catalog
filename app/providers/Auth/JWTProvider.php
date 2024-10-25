<?php

namespace App\Providers\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTProvider
{
    private $secretKey;

    public function __construct()
    {
        // Load the secret key from the .env file
        $this->secretKey = $_ENV['JWT_SECRET'] ?? ''; // Make sure to handle if it's missing
        if (empty($this->secretKey)) {
            throw new \Exception("JWT Secret key is not set in the environment.");
        }
    }

    // Generate a JWT token
    public function generateToken($payload)
    {
        // Sign and return the token using HS256
        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    // Decode and verify a JWT token
    public function verifyToken($token)
    {
        try {
            return JWT::decode($token, new Key($this->secretKey, 'HS256'));
        } catch (\Exception $e) {
            return null; // Handle invalid token
        }
    }
}
