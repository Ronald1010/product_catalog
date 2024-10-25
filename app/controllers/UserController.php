<?php

namespace App\Controllers;

use App\Models\User;
use App\Providers\Auth\JWTProvider;  // Ensure JWTProvider is included
use App\Providers\Validation\ValidatePasswordProvider;
use App\Providers\Email\SendEmailProvider;
use App\Providers\Email\VerifyEmailProvider;
use App\Providers\Auth\PasswordResetProvider;
use App\Providers\Validation\ValidateTokenProvider;
use PDO;

class UserController
{
    private $userModel;
    private $validatePasswordProvider;
    private $sendEmailProvider;
    private $verifyEmailProvider;
    private $passwordResetProvider;
    private $jwtProvider;
    private $validateTokenProvider;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
        $this->validatePasswordProvider = new ValidatePasswordProvider();
        $this->sendEmailProvider = new SendEmailProvider();
        $this->verifyEmailProvider = new VerifyEmailProvider();
        $this->passwordResetProvider = new PasswordResetProvider($db);
        $this->jwtProvider = new JWTProvider(); // JWT handling
        $this->validateTokenProvider = new ValidateTokenProvider($db); // Token blacklist validation
    }

    // Register a new user
    public function createUser($data)
    {
        // Ensure all fields are set
        $firstName = $data['first_name'] ?? '';
        $lastName = $data['last_name'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $birthdate = $data['birthdate'] ?? '';
        $role = $data['role'] ?? '';

        // Validate the password
        if (!$this->validatePasswordProvider->validate($password, $firstName, $lastName)) {
            return $this->sendResponse('error', 'Password must meet complexity requirements.');
        }

        // Ensure role is either '0001' (buyer) or '0002' (seller)
        if (!in_array($role, ['0001', '0002'])) {
            return $this->sendResponse('error', 'Invalid role specified. Only buyers and sellers can register.');
        }

        // Assign the user data to the model
        $this->userModel->first_name = $firstName;
        $this->userModel->last_name = $lastName;
        $this->userModel->email = $email;
        $this->userModel->password = $password;
        $this->userModel->birthdate = $birthdate;
        $this->userModel->role = $role;
        $this->userModel->verification_token = bin2hex(random_bytes(32));

        // Check if email already exists
        $result = $this->userModel->create();
        if ($result === 'email_exists') {
            return $this->sendResponse('error', 'Email already in use.');
        }

        // If the user is created successfully
        if ($result === true) {
            $emailSent = $this->sendEmailProvider->sendVerificationEmail($email, $firstName, $this->userModel->verification_token);
            if ($emailSent) {
                return $this->sendResponse('success', 'Account created successfully. Please verify your email.');
            } else {
                return $this->sendResponse('error', 'Account created, but failed to send verification email.');
            }
        } else {
            return $this->sendResponse('error', 'Failed to create account.');
        }
    }

    // Get user details (GET)
    public function getUserDetails($token)
    {
        // Validate the JWT token and check if blacklisted
        $decodedToken = $this->validateAndVerifyToken($token);

        // Fetch the user's details
        $user = $this->userModel->read($decodedToken->uuid);
        if (!$user) {
            return $this->sendResponse('error', 'User not found.');
        }

        // Sanitize user data before returning
        $sanitizedUser = $this->sanitizeUserData($user);

        return $this->sendResponse('success', 'User details retrieved successfully.', ['user' => $sanitizedUser]);
    }

    // Update user information (PUT)
    public function updateUserDetails($data, $token)
    {
        // Validate the JWT token and check if blacklisted
        $decodedToken = $this->validateAndVerifyToken($token);

        // Fetch the user's current information
        $user = $this->userModel->read($decodedToken->uuid);
        if (!$user) {
            return $this->sendResponse('error', 'User not found.');
        }

        // Allowed fields for update
        $allowedFields = ['first_name', 'last_name', 'email', 'birthdate', 'address', 'phone_number'];

        // Only update fields that are allowed and present in the request data
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $this->userModel->{$field} = $data[$field];
            }
        }

        // Handle password separately if provided
        if (!empty($data['password'])) {
            // Validate the new password
            if (!$this->validatePasswordProvider->validate($data['password'])) {
                return $this->sendResponse('error', 'Password must meet complexity requirements.');
            }

            // Hash the new password before updating it
            $this->userModel->password = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Perform the update
        if ($this->userModel->update()) {
            return $this->sendResponse('success', 'User information updated successfully.');
        } else {
            return $this->sendResponse('error', 'Failed to update user information.');
        }
    }

    // Delete user account (DELETE)
    public function deleteUser($data, $token)
    {
        // Ensure "confirm" is provided and is set to "true"
        if (!isset($data['confirm']) || $data['confirm'] !== 'true') {
            return $this->sendResponse('error', "Please confirm the account deletion by providing 'confirm': 'true'.");
        }
    
        // Validate the JWT token and get the decoded token data
        $decodedToken = $this->jwtProvider->verifyToken($token);
        if (!$decodedToken) {
            return $this->sendResponse('error', 'Invalid or expired token.');
        }
    
        // Get the user's UUID and role from the decoded token
        $uuid = $decodedToken->uuid;
        $role = $decodedToken->role;
    
        // Prevent admin from deleting their own account
        if ($role === '4DM1N') {
            return $this->sendResponse('error', 'Admin accounts cannot be deleted.');
        }
    
        // Proceed with normal user deletion
        $user = $this->userModel->read($uuid);
        if (!$user) {
            return $this->sendResponse('error', 'User not found.');
        }
    
        // Delete the user account
        if ($this->userModel->delete($uuid)) {
            return $this->sendResponse('success', 'User account deleted successfully.');
        } else {
            return $this->sendResponse('error', 'Failed to delete user account.');
        }
    }
    

    // Verify Email
    public function verifyEmail($token)
    {
        $result = $this->verifyEmailProvider->verify($token, $this->userModel);
        $this->sendResponse($result['status'], $result['message']);
    }

    // Request Password Reset
    public function requestPasswordReset($email)
    {
        $result = $this->passwordResetProvider->requestReset($email);
        $this->sendResponse($result['status'], $result['message']);
    }

    // Reset Password (via OTP)
    public function resetPassword($data)
    {
        if (empty($data['otp']) || empty($data['new_password'])) {
            return $this->sendResponse('error', 'OTP and new password are required.');
        }

        if (!$this->validatePasswordProvider->validate($data['new_password'])) {
            return $this->sendResponse('error', 'Invalid password. Ensure it meets the required criteria.');
        }

        $result = $this->passwordResetProvider->resetPassword($data['otp'], $data['new_password']);
        return $this->sendResponse($result['status'], $result['message']);
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
    private function sanitizeUserData($user)
    {
        unset($user['uuid'], $user['password'], $user['email_verified_at'], $user['created_at'], $user['verification_token'], $user['role']);
        return $user;
    }

    // Helper method to send standardized responses
    private function sendResponse($status, $message, $data = [], $statusCode = 200)
    {
        echo json_encode(array_merge(['status' => $status, 'message' => $message], $data));
        http_response_code($statusCode);
        exit();
    }
}
