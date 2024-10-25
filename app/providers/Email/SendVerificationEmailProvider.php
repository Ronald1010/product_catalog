<?php

namespace App\Providers\Email;

use App\Providers\Email\SendEmailProvider;

class SendVerificationEmailProvider extends SendEmailProvider
{
    public function sendVerificationEmail($toEmail, $toName, $verificationToken)
    {
        $baseUrl = $_ENV['BASE_URL'];
        $verificationUrl = $baseUrl . '/verify?token=' . $verificationToken;

        $subject = "Verify Your Email Address";
        $body = "Hi $toName, <br><br>";
        $body .= "Thank you for registering. Please click the link below to verify your email address:<br>";
        $body .= "<a href=\"$verificationUrl\">Verify Email</a><br><br>";
        $body .= "If you did not create this account, you can ignore this email.<br><br>";
        $body .= "Thanks,<br>ProjectZero Team";

        return $this->sendEmail($toEmail, $toName, $subject, $body);
    }
}
