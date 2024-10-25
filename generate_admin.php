<?php

// Required for generating UUID and hashing passwords
require 'vendor/autoload.php';  // Assuming you're using the Ramsey UUID package
use Ramsey\Uuid\Uuid;

// Function to generate the admin account SQL query
function generateAdminAccountSQL($firstName, $lastName, $email, $password, $birthdate = null, $address = null, $phone_number = null) {
    // Generate a new UUID for the user
    $uuid = Uuid::uuid4()->toString();
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    
    // If optional fields are not provided, set to NULL
    $birthdate = $birthdate ? "'$birthdate'" : "NULL";
    $address = $address ? "'$address'" : "NULL";
    $phone_number = $phone_number ? "'$phone_number'" : "NULL";
    
    // Get the current timestamp for the email_verified_at field
    $emailVerifiedAt = date('Y-m-d H:i:s');  // Current timestamp
    
    // Create the SQL INSERT query with all fields including email_verified_at
    $sql = "INSERT INTO users (uuid, first_name, last_name, email, password, birthdate, address, phone_number, role, email_verified_at) 
            VALUES ('$uuid', '$firstName', '$lastName', '$email', '$hashedPassword', $birthdate, $address, $phone_number, '4DM1N', '$emailVerifiedAt');";
    
    // Output the SQL query
    echo "Generated SQL Query to create Admin account:\n";
    echo $sql . "\n\n";
}

// You can modify these details or pass them as arguments when you call this script
$firstName = "Admin";  // Modify this
$lastName = "Admin";    // Modify this
$email = "admin@example.com";   // Modify this
$password = "Admin1234";   // Modify this
$birthdate = "1990-01-01";      // Optional, modify this
$address = "123 Admin St.";     // Optional, modify this
$phone_number = "1234567890";   // Optional, modify this

// Call the function to generate the SQL
generateAdminAccountSQL($firstName, $lastName, $email, $password, $birthdate, $address, $phone_number);
