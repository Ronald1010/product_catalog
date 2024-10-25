<?php

namespace App\Providers\Auth;

use PDO;
use DateTime;
use App\Models\User;
use App\Providers\Email\SendEmailProvider;
use Exception;

class PasswordResetProvider
{
    private $db;
    private $userModel;
    private $sendEmailProvider;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->sendEmailProvider = new SendEmailProvider();
    }

    // Request a password reset by sending OTP
    public function requestReset($email)
    {
        // Check if the email exists in the users table
        $user = $this->userModel->getByEmail($email);
        if (!$user) {
            error_log('Email does not exist: ' . $email);  // Log if email not found
            return ['status' => 'error', 'message' => 'Email does not exist.'];
        }

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        error_log('Generated OTP: ' . $otp);  // Log the generated OTP

        // Attempt to save the OTP in the database
        if ($this->savePasswordResetOTP($email, $otp)) {
            // Send email with the OTP
            $this->sendEmailProvider->sendResetPasswordEmail($email, $user->first_name, $otp);
            error_log('OTP sent to email: ' . $email);  // Log OTP email sent
            return ['status' => 'success', 'message' => 'OTP has been sent to your email.'];
        } else {
            error_log('Failed to save OTP for email: ' . $email);  // Log OTP save failure
            return ['status' => 'error', 'message' => 'Failed to generate OTP.'];
        }
    }

    // Save the OTP in the database
    private function savePasswordResetOTP($email, $otp)
    {
        try {
            $expiresAt = (new DateTime())->modify('+15 minutes')->format('Y-m-d H:i:s');
            $query = "INSERT INTO password_resets (email, otp, expires_at) VALUES (:email, :otp, :expires_at)
                      ON DUPLICATE KEY UPDATE otp = :otp, expires_at = :expires_at";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':otp', $otp);
            $stmt->bindParam(':expires_at', $expiresAt);
            $result = $stmt->execute();
            error_log('OTP save result: ' . json_encode($result));  // Log OTP save result
            return $result;
        } catch (Exception $e) {
            error_log('Error saving OTP: ' . $e->getMessage());  // Log any errors
            return false;
        }
    }

    // Validate the OTP and reset the password
    public function resetPassword($otp, $new_password)
{
    error_log('Attempting to reset password with OTP: ' . $otp);

    $tokenValidation = $this->validateOTP($otp);
    error_log('OTP validation result: ' . json_encode($tokenValidation));

    if (!$tokenValidation['status']) {
        return ['status' => 'error', 'message' => $tokenValidation['message']];
    }

    // Proceed to reset the password
    $result = $this->userModel->resetPassword($tokenValidation['email'], $new_password);
    error_log('Password reset result for email: ' . $tokenValidation['email'] . ' - ' . json_encode($result));

    if ($result) {
        $this->deleteOTP($otp);
        return ['status' => 'success', 'message' => 'Password reset successful.'];
    } else {
        return ['status' => 'error', 'message' => 'Password reset failed.'];
    }
}

    // Validate the OTP
    private function validateOTP($otp)
    {
        try {
            $query = "SELECT * FROM password_resets WHERE otp = :otp";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':otp', $otp);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log('OTP fetch result: ' . json_encode($result));  // Log the fetch result

            if ($result) {
                $expiresAt = new DateTime($result['expires_at']);
                $now = new DateTime();

                if ($now > $expiresAt) {
                    return ['status' => false, 'message' => 'OTP has expired.'];
                }

                return ['status' => true, 'email' => $result['email']];
            } else {
                return ['status' => false, 'message' => 'Invalid OTP.'];
            }
        } catch (Exception $e) {
            error_log('Error validating OTP: ' . $e->getMessage());  // Log any errors during validation
            return ['status' => false, 'message' => 'An error occurred.'];
        }
    }

    // Delete the OTP after successful password reset
    private function deleteOTP($otp)
    {
        try {
            $query = "DELETE FROM password_resets WHERE otp = :otp";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':otp', $otp);
            $stmt->execute();
            error_log('Deleted OTP: ' . $otp);  // Log OTP deletion
        } catch (Exception $e) {
            error_log('Error deleting OTP: ' . $e->getMessage());  // Log any errors during deletion
        }
    }
}
