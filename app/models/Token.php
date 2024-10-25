<?php

namespace App\Models;

use PDO;

class Token
{
    private $conn;
    private $table = 'token'; // Tokens table

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Create a new token
    public function create($uuid, $token)
    {
        $expiresAt = (new \DateTime())->add(new \DateInterval('PT1H')); // Set expiration to 1 hour from now
        $expiresAtFormatted = $expiresAt->format('Y-m-d H:i:s'); // Format the expiration date as a string

        $query = "INSERT INTO " . $this->table . " (uuid, token, expires_at) 
                  VALUES (:uuid, :token, :expires_at)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uuid', $uuid);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expires_at', $expiresAtFormatted); // Pass the formatted expiration date

        return $stmt->execute();
    }

    // Validate token
    public function validate($token)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE token = :token AND expires_at > NOW()"; // Check if token is valid and not expired

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ); // Return the token or false if invalid
    }

    // Delete token
    public function deleteToken($token)
    {
        $query = "DELETE FROM " . $this->table . " WHERE token = :token";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        
        return $stmt->execute(); // Return true if successful, false otherwise
    }
}
