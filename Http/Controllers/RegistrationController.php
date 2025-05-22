<?php

namespace Http\Controllers;

use Core\App;
use Core\Session;
use Core\Authenticator;
use Core\Database;
use Core\ValidationException;
use Core\Validator;

/**
 * RegistrationController class
 */
class RegistrationController
{
    public function create()
    {
        return view('registration/create.view.php', [
            'errors' => Session::get('errors'),
            'old' => Session::get('old'),
        ]);
    }

    public function store()
    {
        $attributes = [
            'username' => trim($_POST['username'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? '')
        ];

        $errors = [];

        // Validation
        if (!Validator::email($attributes['email'])) {
            $errors['email'] = 'Please provide a valid email address.';
        }

        if (!Validator::string($attributes['username'], min: 3, max: 20)) {
            $errors['username'] = 'Please provide a valid username.';
        }

        if (!Validator::string($attributes['password'], min: 8)) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        } else {
            $requirements = [
                'min_length' => strlen($attributes['password']) >= 8,
                'uppercase' => preg_match('/[A-Z]/', $attributes['password']),
                'lowercase' => preg_match('/[a-z]/', $attributes['password']),
                'special_or_number' => preg_match('/[\d\W]/', $attributes['password']),
            ];

            if (!array_reduce($requirements, fn($carry, $item) => $carry && $item, true)) {
                $errors['password'] = 'Password must include uppercase, lowercase, and a number or special character.';
            }
        }

        // If validation fails, redirect with errors
        if (!empty($errors)) {
            ValidationException::throw($errors, $attributes);
        }

        $db = App::resolve(Database::class);

        // Check if email or username already exists
        $existingUser = $db->query('SELECT * FROM users WHERE email = :email OR username = :username', [
            'email' => $attributes['email'],
            'username' => $attributes['username'],
        ])->find();

        if ($existingUser) {
            $errors['email'] = 'Email or username is already taken.';
            ValidationException::throw($errors, $attributes);
        }

        // Register the user
        $user = $db->query('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)', [
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => password_hash($attributes['password'], PASSWORD_BCRYPT),
        ])->findById('users', $db->getLastInsertId());

        
        // Use $user as needed, e.g., start 2FA
        (new Authenticator)->start2FA($user);

        // Redirect to 2FA verification
        $_SESSION['temp_user_id'] = $user->id;
        $_SESSION['return_location'] = '/register';
        redirect('/verify');
    }
}