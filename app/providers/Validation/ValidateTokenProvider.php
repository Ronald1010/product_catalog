<?php

namespace App\Providers\Validation;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PDO;

class ValidateTokenProvider
{
    private $secretKey;
    private $db;

    public function __construct(PDO $db)
    {
        // Initialize DB connection and secret key
        $this->db = $db;
        $this->secretKey = $_ENV['JWT_SECRET'] ?? ''; // Fetch JWT secret from the environment
        if (empty($this->secretKey)) {
            throw new \Exception("JWT Secret key is not set in the environment.");
        }
    }

    // Method to validate the JWT token and check if it's blacklisted
    public function validateToken($token)
{
    // Check if the token is blacklisted
    if ($this->isTokenBlacklisted($token)) {
        throw new \Exception('Token is blacklisted');
    }

    // Try decoding the token
    try {
        return JWT::decode($token, new Key($this->secretKey, 'HS256'));
    } catch (\Exception $e) {
        throw new \Exception('Invalid token');
    }
}

    // Method to check if the token is blacklisted
    private function isTokenBlacklisted($token)
    {
        $query = "SELECT id FROM blacklisted_tokens WHERE token = :token LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    // Method to blacklist a token (e.g., on logout)
    public function blacklistToken($token)
    {
        $query = "INSERT INTO blacklisted_tokens (token) VALUES (:token)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token);
        return $stmt->execute();
    }
}
