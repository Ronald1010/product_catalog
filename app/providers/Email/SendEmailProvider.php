<?php

namespace App\Providers\Email;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

class SendEmailProvider
{
    private $mail;

    public function __construct()
    {
        // Load environment variables
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
        $dotenv->load();

        $this->mail = new PHPMailer(true);

        // SMTP settings
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $_ENV['MAIL_PORT'];
        $this->mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
    }

    // Helper function to send email
    protected function sendEmail($toEmail, $toName, $subject, $body)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($toEmail, $toName);

            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Email error: ' . $e->getMessage());
            return false;
        }
    }

    // Method for sending verification email
    public function sendVerificationEmail($toEmail, $toName, $verificationToken)
    {
        // Construct verification URL
        $baseUrl = $_ENV['BASE_URL'];
        $verificationUrl = $baseUrl . '/verify?token=' . $verificationToken;

        // Email content
        $subject = "Verify Your Email Address";
        $body = "Hi $toName, <br><br>";
        $body .= "Thank you for registering. Please click the link below to verify your email address:<br>";
        $body .= "<a href=\"$verificationUrl\">Verify Email</a><br><br>";
        $body .= "If you did not create this account, you can ignore this email.<br><br>";
        $body .= "Thanks,<br>Your Team";

        // Send the email
        return $this->sendEmail($toEmail, $toName, $subject, $body);
    }

    public function sendResetPasswordEmail($toEmail, $toName, $otp)
    {
        $subject = "Your OTP for Password Reset";
        $body = "Hi $toName, <br><br>";
        $body .= "Your OTP for resetting your password is: <strong>$otp</strong><br>";
        $body .= "The OTP is valid for 15 minutes. <br><br>";
        $body .= "Thanks,<br>ProjectZero Team";

        return $this->sendEmail($toEmail, $toName, $subject, $body);
    }
}
