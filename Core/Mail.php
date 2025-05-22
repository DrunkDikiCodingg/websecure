<?php

namespace Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    private $fromAddress;
    private $fromName;

    public function __construct($fromAddress = 'no-reply@websecure.com', $fromName = 'WebSecure')
    {
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
    }
    
    public function send($to, $subject, $body, $altBody = null)
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Username = config('mail.username');
            $mail->Password = config('mail.password');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom($this->fromAddress, $this->fromName);

            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            if ($altBody) {
                $mail->AltBody = $altBody;
            }

            return $mail->send();
        } catch (Exception $e) {
            error_log("Mail Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
