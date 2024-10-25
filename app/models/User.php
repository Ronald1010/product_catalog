<?php

namespace App\Models;

use PDO;
use Ramsey\Uuid\Uuid;

class User
{
    private $conn;
    private $table = 'users';

    public $uuid;
    public $user_id;
    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $birthdate;
    public $email_verified_at;
    public $created_at;
    public $role;
    public $address;
    public $phone_number;
    public $verification_token;

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // Create a new user
    public function create()
    {
        // Check if the email already exists
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Email already exists, return an error
            return 'email_exists';
        }

        // Proceed with creating the user if email is unique
        $this->uuid = Uuid::uuid4()->toString(); // Generate a new UUID
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); // Hash password

        $query = "INSERT INTO " . $this->table . " 
                  SET uuid = :uuid, first_name = :first_name, last_name = :last_name, 
                      email = :email, password = :password, birthdate = :birthdate, 
                      role = :role, address = :address, phone_number = :phone_number,
                      verification_token = :verification_token";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':uuid', $this->uuid);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':birthdate', $this->birthdate);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':verification_token', $this->verification_token);

        return $stmt->execute();
    }

    // Read user by UUID
    public function read($uuid)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE uuid = :uuid LIMIT 1";
        $stmt = $this->conn->prepare($query);  
        $stmt->bindParam(':uuid', $uuid);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Get user by verification token
    public function getByVerificationToken($token)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE verification_token = :token LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ); 
    }

    // Update user information
    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET first_name = :first_name, last_name = :last_name, 
                      email = :email, birthdate = :birthdate, role = :role, 
                      address = :address, phone_number = :phone_number";
        
        if (!empty($this->password)) {
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $query .= ", password = :password";
        }
        
        $query .= " WHERE uuid = :uuid";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(':uuid', $this->uuid);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':birthdate', $this->birthdate);
        $stmt->bindParam(':role', $this->role);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone_number', $this->phone_number);

        if (!empty($this->password)) {
            $stmt->bindParam(':password', $this->password);
        }

        return $stmt->execute();
    }

     // Delete user by UUID
     public function delete($uuid) {
        $query = "DELETE FROM " . $this->table . " WHERE uuid = :uuid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uuid', $uuid);
        return $stmt->execute();
    }

    // Get user by email
    public function getByEmail($email)
    {
        $sql = "SELECT * FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Verify email
    public function verifyEmail()
    {
        $query = "UPDATE " . $this->table . " SET email_verified_at = NOW(), verification_token = NULL WHERE uuid = :uuid";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':uuid', $this->uuid);

        return $stmt->execute();
    }

    // Reset user password (for password reset flow)
    public function resetPassword($email, $new_password)
    {
        $query = "UPDATE " . $this->table . " SET password = :password WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        
        // Store the result of password_hash in a variable
        $hashedPassword = password_hash($new_password, PASSWORD_BCRYPT);
        
        // Use the variable for binding
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
    
        return $stmt->execute();
    }
    
    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update user information
    public function updateUser($data) {
        $query = "UPDATE " . $this->table . " 
                  SET first_name = :first_name, last_name = :last_name, 
                      email = :email, role = :role 
                  WHERE uuid = :uuid";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':uuid', $data['uuid']);

        return $stmt->execute();
    }
}
