<?php

namespace App\Providers\Auth;

use PDO;
use App\Models\User;
use App\Models\Token;
use App\Providers\Auth\JWTProvider;

class AuthProvider
{
    private $db;
    private $userModel;
    private $tokenModel;
    private $jwtProvider;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->tokenModel = new Token($db);
        $this->jwtProvider = new JWTProvider();
    }

    // Handle login
    public function login($email, $password)
    {
        $user = $this->userModel->getByEmail($email);

        if (!$user || !password_verify($password, $user->password)) {
            return ['status' => 'error', 'message' => 'Invalid credentials'];
        }

        // Check if email is verified
        if (is_null($user->email_verified_at)) {
            return ['status' => 'error', 'message' => 'Please verify your email before logging in.'];
        }

        // Generate JWT token
        $token = $this->jwtProvider->generateToken($user->uuid);

        return [
            'status' => 'success',
            'token' => $token,
            'user' => [
                'id' => $user->uuid,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ];
    }

    // Handle logout by invalidating JWT token (if applicable)
    public function logout($token)
    {
        // JWT tokens are stateless, so logout would be handled by client simply deleting the token
        return ['status' => 'success', 'message' => 'Logged out successfully.'];
    }
}
