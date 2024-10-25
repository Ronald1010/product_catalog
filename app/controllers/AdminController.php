<?php

namespace App\Controllers;

use App\Models\User;
use App\Providers\Validation\ValidateTokenProvider; // Include ValidateTokenProvider for blacklist checking
use PDO;

class AdminController
{
    private $userModel;
    private $validateTokenProvider;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
        $this->validateTokenProvider = new ValidateTokenProvider($db); // Initialize ValidateTokenProvider
    }

    // List all users (Admin)
    public function listUsers($token)
    {
        // Validate the JWT token and check if blacklisted
        $decodedToken = $this->validateAndVerifyToken($token);

        // Ensure only admins can list users
        if ($decodedToken->role !== '4DM1N') {
            return $this->sendResponse('error', 'Access denied. Admin privileges required.', [], 403);
        }

        // Retrieve all users
        $users = $this->userModel->getAllUsers();
        $sanitizedUsers = $this->sanitizeUserData($users); // Sanitize user data before returning

        return $this->sendResponse('success', 'Users retrieved successfully.', ['users' => $sanitizedUsers]);
    }

    // Read user details (Admin)
    public function readUser($uuid, $token)
    {
        // Validate the JWT token and check if blacklisted
        $decodedToken = $this->validateAndVerifyToken($token);

        // Ensure only admins can read user details
        if ($decodedToken->role !== '4DM1N') {
            return $this->sendResponse('error', 'Access denied. Admin privileges required.', [], 403);
        }

        // Fetch user details by UUID
        $user = $this->userModel->read($uuid);
        if (!$user) {
            return $this->sendResponse('error', 'User not found.');
        }

        $sanitizedUser = $this->sanitizeUserData([$user])[0]; // Sanitize user data before returning

        return $this->sendResponse('success', 'User details retrieved successfully.', ['user' => $sanitizedUser]);
    }

    // Update user details (Admin)
    public function updateUser($data, $uuid, $token)
    {
        // Validate the JWT token and check if blacklisted
        $decodedToken = $this->validateAndVerifyToken($token);

        // Ensure only admins can update user details
        if ($decodedToken->role !== '4DM1N') {
            return $this->sendResponse('error', 'Access denied. Admin privileges required.', [], 403);
        }

        // Fetch the user to update
        $user = $this->userModel->read($uuid);
        if (!$user) {
            return $this->sendResponse('error', 'User not found.');
        }

        // Update the user fields
        $this->userModel->uuid = $uuid;
        $this->userModel->first_name = $data['first_name'] ?? $user['first_name'];
        $this->userModel->last_name = $data['last_name'] ?? $user['last_name'];
        $this->userModel->email = $data['email'] ?? $user['email'];
        $this->userModel->birthdate = $data['birthdate'] ?? $user['birthdate'];
        $this->userModel->role = $data['role'] ?? $user['role'];
        $this->userModel->address = $data['address'] ?? $user['address'];
        $this->userModel->phone_number = $data['phone_number'] ?? $user['phone_number'];

        // Handle password if provided
        if (isset($data['password'])) {
            $this->userModel->password = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if ($this->userModel->update()) {
            return $this->sendResponse('success', 'User information updated successfully.');
        } else {
            return $this->sendResponse('error', 'Failed to update user information.');
        }
    }

    // Delete user (Admin)
    public function deleteUser($uuid, $token)
    {
        // Validate the JWT token and check if blacklisted
        $decodedToken = $this->validateAndVerifyToken($token);

        // Ensure only admins can delete users
        if ($decodedToken->role !== '4DM1N') {
            return $this->sendResponse('error', 'Access denied. Admin privileges required.', [], 403);
        }

        // Delete the user by UUID
        if ($this->userModel->delete($uuid)) {
            return $this->sendResponse('success', 'User deleted successfully.');
        } else {
            return $this->sendResponse('error', 'Failed to delete user.');
        }
    }

    // Validate and verify the JWT token, including blacklist check
    private function validateAndVerifyToken($token)
    {
        try {
            return $this->validateTokenProvider->validateToken($token);
        } catch (\Exception $e) {
            $this->sendResponse('error', $e->getMessage(), [], 401);
        }
    }

    // Sanitize user data by removing sensitive fields
    private function sanitizeUserData($users)
    {
        return array_map(function ($user) {
            unset($user['uuid'], $user['password'], $user['verification_token'], $user['email_verified_at'], $user['created_at']);
            return $user;
        }, $users);
    }

    // Helper function to send standardized response
    private function sendResponse($status, $message, $data = [], $statusCode = 200)
    {
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
        http_response_code($statusCode);
        exit();
    }
}
