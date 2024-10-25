<?php

namespace App\Providers\Validation;

use Rakit\Validation\Validator;

class ValidateRoleProvider
{
    private $validRoles = ['0001', '0002', '4DM1N']; // Include '4DM1N' as a valid role

    public function isValidRole($role)
    {
        $validator = new Validator();

        // Validate the role field, ensuring it matches one of the valid roles
        $validation = $validator->make(
            ['role' => $role], 
            ['role' => ['required', function ($value) {
                return in_array($value, $this->validRoles);
            }]]
        );

        // Perform the validation
        $validation->validate();

        return !$validation->fails(); // Return true if valid, false otherwise
    }
}
