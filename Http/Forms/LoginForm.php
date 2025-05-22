<?php

namespace Http\Forms;

use Core\ValidationException;
use Core\Validator;

class LoginForm
{
    protected $errors = [];

    protected bool $isEmail;

    public function __construct(public array $attributes)
    {
        // Check if usernameOrEmail is provided
        if (!Validator::required($attributes['usernameOrEmail'] ?? '')) {
            $this->errors['usernameOrEmail'] = 'The username/email field is required.';
        } else {
            // Distinguish between email and username
            $this->isEmail = Validator::email($attributes['usernameOrEmail']);

            if (!$this->isEmail && !Validator::string($attributes['usernameOrEmail'], min: 3)) {
                $this->errors['usernameOrEmail'] = 'Please provide a valid username or email.';
            }
        }

        // Check if password is provided
        if (!Validator::required($attributes['password'] ?? '')) {
            $this->errors['password'] = 'The password field is required.';
        } else if (!Validator::string($attributes['password'], min: 8)) {
            $this->errors['password'] = 'Please provide a valid password.';
        }
    }

    public static function validate($attributes)
    {
        $instance = new static($attributes);

        return $instance->failed() ? $instance->throw() : $instance;
    }

    public function throw()
    {
        ValidationException::throw($this->errors(), $this->attributes);
    }

    public function failed(): bool
    {
        return count($this->errors) > 0;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function error(string $field, string $message): self
    {
        // General error handling under the 'form' key
        if ($field === 'form') {
            $this->errors['form'] = $message;
        } else {
            $this->errors[$field] = $message;
        }

        return $this;
    }
}