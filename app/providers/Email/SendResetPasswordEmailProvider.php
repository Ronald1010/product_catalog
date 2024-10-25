<?php

namespace App\Providers\Email;

use App\Providers\Email\SendEmailProvider;

class SendResetPasswordEmailProvider extends SendEmailProvider
{
    public function sendResetPasswordEmail($toEmail, $toName, $resetToken)
    {
        $baseUrl = $_ENV['BASE_URL'];
        $resetUrl = $baseUrl . '/views/reset_password.php?token=' . $resetToken;

        $subject = "Reset Your Password";
        $body = "Hi $toName, <br><br>";
        $body .= "We received a request to reset your password. Please click the link below to set a new password:<br>";
        $body .= "<a href=\"$resetUrl\">Reset Password</a><br><br>";
        $body .= "If you didn't request a password reset, please ignore this email.<br><br>";
        $body .= "Thanks,<br>ProjectZero Team";

        return $this->sendEmail($toEmail, $toName, $subject, $body);
    }
}
