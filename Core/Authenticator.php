<?php

namespace Core;

use Exception;
use PDO;

class Authenticator
{
    public function attempt($usernameOrEmail, $password)
    {
        $user = App::resolve(Database::class)
            ->query('SELECT * FROM users WHERE username = :input OR email = :input', [
                'input' => $usernameOrEmail
            ])->setFetchMode(PDO::FETCH_OBJ)
            ->find();

        if ($user && password_verify($password, $user->password)) {
            // Initiate 2FA process
            $this->start2FA($user);
    
            return $user;
        }
        return false;
    }

    public function start2FA($user)
    {
        $db = App::resolve(Database::class);

        // Invalidate any existing verification codes for the user
        $db->query("UPDATE verification_codes SET is_active = 0 WHERE user_id = :user_id", [
            'user_id' => $user->id
        ]);

        // Generate a new verification code
        $code = $this->generateVerificationCode($user->id);

        // For debugging purposes only (remove in production)
        $_SESSION['code'] = $code;

        $this->sendVerificationCode($user->username, $user->email, $code);

        // Temporarily store user information for 2FA
        $_SESSION['temp_user'] = $user;
    }

    public function verify2FA($code)
    {
        $user = $_SESSION['temp_user'] ?? null;

        if (!$user) {
            throw new Exception('Verification session expired. Please try logging in again.');
        }

        $db = App::resolve(Database::class);

        // Fetch the most recent active and valid code for the user
        $verification = $db->query('SELECT * 
                                    FROM verification_codes 
                                    WHERE user_id = :user_id 
                                    AND code = :code 
                                    AND is_active = 1 
                                    AND expiration > :now
                                    LIMIT 1', [
                                            'user_id' => $user->id,
                                            'code' => $code,
                                            'now' => date('Y-m-d H:i:s')
                                   ])
                                    ->setFetchMode(PDO::FETCH_OBJ)
                                    ->find();

        // Validate the code
        if (!$verification) {
            throw new Exception('Invalid or expired verification code. Please try again.');
        }

        // Mark the code as inactive
        $db->query("UPDATE verification_codes SET is_active = 0 WHERE id = :id", [
            'id' => $verification->id
        ]);

        // Finalize the login process
        $this->finalizeLogin($user);

        // Clear temporary session variables
        unset($_SESSION['temp_user']);
    }

    public function finalizeLogin($user)
    {
        // Store the user and roles in the session
        $_SESSION['user'] = $user;
        $_SESSION['roles'] = App::resolve(Role::class)->getUserRoles($user->id);

        unset($_SESSION['temp_user']);

        // Regenerate the session ID for security
        session_regenerate_id(true);
    }

    public function logout()
    {
        Session::destroy();
    }

    public function generateVerificationCode($userId) {
        // Generate a 6-digit random code
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    
        // Calculate expiration time (current time + 5 minutes)
        $expiration = date('Y-m-d H:i:s', time() + 5 * 60);
    
        // Insert the code into the database
        $db = App::resolve(Database::class);
        $db->query("INSERT INTO verification_codes (user_id, code, expiration) VALUES (:user_id, :code, :expiration)", [
            'user_id' => $userId,
            'code' => $code,
            'expiration' => $expiration
        ]);

        return $code;
    }

    private function sendVerificationCode($username, $email, $code)
    {
        $mail = new Mail();

        $subject = "Your WebSecure 2FA Verification Code";
        $body = getView('mail/verification_template.view.php', [
            'code' => $code,
            'username' => $username,
            'email' => $email
        ]);

        return $mail->send($email, $subject, $body);
    }
}