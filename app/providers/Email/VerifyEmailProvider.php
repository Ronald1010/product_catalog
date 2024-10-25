<?php

namespace App\Providers\Email;

use App\Models\User;

class VerifyEmailProvider
{
    public function verify($token, User $userModel)
    {
        // Retrieve user by verification token
        $user = $userModel->getByVerificationToken($token);
        if (!$user) {
            return [
                'status' => 'error',
                'message' => 'Invalid or expired token.'
            ];
        }

        // Update the user's email verification status
        $userModel->uuid = $user->uuid;
        $userModel->email_verified_at = date('Y-m-d H:i:s');
        $userModel->verification_token = null;

        if ($userModel->verifyEmail()) {
            return [
                'status' => 'success',
                'message' => 'Email verified successfully.'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Email verification failed.'
            ];
        }
    }
}
