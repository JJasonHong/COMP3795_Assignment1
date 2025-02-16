<?php

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<!-- Validate Password: Used in process-register.php -->
<?php

function validate_password($password) 
{
    // Check if password is not empty and contains no whitespace (just or simplicity)
    return strlen($password) > 0 && !preg_match('/\s/', $password);
}

?>