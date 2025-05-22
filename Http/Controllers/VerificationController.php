<?php

namespace Http\Controllers;

use Core\Authenticator;

class VerificationController
{
    public function show()
    {
        view('verification/show.view.php');
    }

    public function verify()
    {
        try {
            $code = $_POST['code'] ?? null;
        
            if (!$code) {
                throw new \Exception('Verification code is required.');
            }
        
            // Authenticate the 2FA code
            $authenticator = new Authenticator();
            $authenticator->verify2FA($code);
        
            // Redirect to the intended location
            $redirectLocation = $_SESSION['return_location'] ?? '/';
            unset($_SESSION['return_location'], $_SESSION['temp_user']);
            redirect($redirectLocation);
        } catch (\Exception $e) {
            // Handle verification errors
            $_SESSION['error_message'] = $e->getMessage();
            redirect('/verify');
        }
    }
}
