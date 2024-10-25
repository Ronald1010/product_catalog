<?php

namespace App\Controllers;

use App\Models\User;
use App\Providers\Auth\JWTProvider;  // Ensure JWTProvider is included
use App\Providers\Validation\ValidateTokenProvider;

class AuthController
{
    private $userModel;
    private $jwtProvider; // Add this property for JWT handling
    private $validateTokenProvider;

    public function __construct($db)
    {
        $this->userModel = new User($db);       
        $this->validateTokenProvider = new ValidateTokenProvider($db); 
        $this->jwtProvider = new JWTProvider(); // Initialize JWTProvider here
    }

    // Handle user login
    public function login($data)
    {
        // Validate input data
        if (empty($data['email']) || empty($data['password'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email and password are required.'
            ]);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        // Fetch user by email
        $user = $this->userModel->getByEmail($email);
        if (!$user) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid email or password.'
            ]);
            return;
        }

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Please verify your email before logging in.'
            ]);
            return;
        }

        // Verify password (assumes password is hashed)
        if (!password_verify($password, $user->password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid email or password.'
            ]);
            return;
        }

        // Generate JWT token
        $authToken = $this->jwtProvider->generateToken([
            'uuid' => $user->uuid,
            'email' => $user->email,
            'role' => $user->role,
            'iat' => time()  // 'iat'
        ]);
        

        // Successful login response
        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful.',
            'auth_token' => $authToken, // Return the JWT auth token
            'user' => [
                'id' => $user->uuid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    }

    // Handle logout
    public function logout($jwtToken)
{
    // Log the received token
    error_log("Authorization Header in logout: " . $jwtToken);

    if (!empty($jwtToken)) {
        // Blacklist the token
        $this->validateTokenProvider->blacklistToken($jwtToken);

        echo json_encode([
            'status' => 'success',
            'message' => 'Logout successful. Token blacklisted.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No token provided.'
        ]);
    }
}
    
}
