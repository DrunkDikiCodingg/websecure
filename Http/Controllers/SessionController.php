<?php

namespace Http\Controllers;

use Core\Authenticator;
use Core\Session;
use Core\ValidationException;
use Http\Forms\LoginForm;


class SessionController
{

    public function create()
    {
        view('session/create.view.php', [
            'errors' => Session::get('errors')
        ]);
        
    }

    public function store()
    {
        try {

            // Validate the form inputs
            $form = LoginForm::validate($attributes = [
                'usernameOrEmail' => $_POST['usernameOrEmail'] ?? '',
                'password' => $_POST['password'] ?? ''
            ]);

            // Attempt login and initiate 2FA
            $authenticator = new Authenticator();
            $user = $authenticator->attempt($attributes['usernameOrEmail'], $attributes['password']);

            if (!$user) {
                // Add general error under the 'form' key and throw
                $form->error('form', 'Invalid username/email or password.')->throw();
            }

            // Store session data for 2FA verification
            $_SESSION['temp_user'] = $user; // Store the full user object
            $_SESSION['return_location'] = '/login';

            // Redirect to 2FA verification page
            redirect('/verify');

        } catch (ValidationException $e) {
            Session::flash('errors', $e->errors);
            Session::flash('old', $e->old);

            // Redirect back to login
            redirect('/login');
        }
    }

    public function destroy()
    {
        (new Authenticator)->logout();

        header('location: /');
        exit();
    }
}