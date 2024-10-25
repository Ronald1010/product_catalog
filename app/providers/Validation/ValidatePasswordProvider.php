<?php

namespace App\Providers\Validation;

class ValidatePasswordProvider
{
    public static function validate($password, $firstName = '', $lastName = '')
    {
        // Ensure the password meets length requirements
        if (strlen($password) < 8) {
            return false;
        }

        // Ensure the password contains at least one uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // Ensure the password contains at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // Ensure the password contains at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Check if the password contains the user's first or last name (case-insensitive)
        if (!empty($firstName) && stripos($password, $firstName) !== false) {
            return false;
        }
        if (!empty($lastName) && stripos($password, $lastName) !== false) {
            return false;
        }

        return true; // Password is valid
    }
}
