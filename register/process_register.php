<?php
session_start();

// Debug: if 'debug' not initialized, do it now
if (!isset($_SESSION['debug'])) {
    $_SESSION['debug'] = "";
}
$_SESSION['debug'] .= "Reached process_register.php\n";

/* Require/Include */
require_once "../inc_db_params.php";
require_once "../utils.php";

/**
 * If user is already logged in, redirect
 */
if (isset($_SESSION['username'])) {
    $_SESSION['debug'] .= "User is already logged in as {$_SESSION['username']}.\n";
    $_SESSION['error'] = "You're already signed in, please log out first.";
    header("Location: /index.php");
    exit();
}

$_SESSION['debug'] .= "Request method: {$_SERVER["REQUEST_METHOD"]}\n";

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    $email          = trim($_POST["email"]);
    $password       = $_POST["password"];
    $first_name     = trim($_POST["first_name"]);
    $last_name      = trim($_POST["last_name"]);
    $error_message  = "";

    // Debug: capture inputs (excluding raw password for security)
    $_SESSION['debug'] .= "User submitted email: {$email}\n";
    $_SESSION['debug'] .= "User submitted firstName: {$first_name}, lastName: {$last_name}\n";
    $_SESSION['debug'] .= "Password length: " . strlen($password) . "\n";

    // Validate Email
    $_SESSION['debug'] .= "Validating email...\n";
    if (!is_valid_email($email, $error_message)) 
    {
        $_SESSION['debug'] .= "Email validation failed: {$error_message}\n";
        $_SESSION['error'] = $error_message;
        header("Location: register.php");
        exit();
    }

    // Validate password complexity
    $_SESSION['debug'] .= "Validating password...\n";
    if (!validate_password($password, $error_message)) 
    {
        $_SESSION['debug'] .= "Password validation failed: {$error_message}\n";
        $_SESSION['error'] = $error_message;
        header("Location: register.php");
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $_SESSION['debug'] .= "Password hashed.\n";

    try 
    {
        $_SESSION['debug'] .= "Connecting to DB with sqlite:../blog3795.sqlite\n";
        $db = new PDO("sqlite:../blog3795.sqlite");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email already exists
        $_SESSION['debug'] .= "Checking if email is already in Users table...\n";
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        
        if (!$stmt) {
            $_SESSION['debug'] .= "Failed to prepare statement for email check.\n";
        }
        
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) 
        {
            $_SESSION['debug'] .= "Email is already registered.\n";
            $_SESSION['error'] = "Email is already registered.";
            header("Location: register.php");
            exit();
        }

        // Insert new user with default values (IsApproved=0, role="contributor")
        $_SESSION['debug'] .= "Inserting new user into the database.\n";
        $stmt = $db->prepare("
            INSERT INTO users (username, password, firstName, lastName, registrationDate, isApproved, role) 
            VALUES (?, ?, ?, ?, datetime('now'), 0, 'contributor')
        ");
        $stmt->execute([$email, $hashed_password, $first_name, $last_name]);

        $_SESSION['message'] = "Registration successful! Waiting for admin approval.";
        $_SESSION['debug'] .= "User registered successfully. Redirecting to /login.php\n";
        header("Location: /login.php");
        exit();
    } 
    catch (PDOException $e) 
    {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        $_SESSION['debug'] .= "PDOException: " . $e->getMessage() . "\n";
        header("Location: register.php");
        exit();
    }
} 
else 
{
    $_SESSION['debug'] .= "Request method not POST; redirecting to register.\n";
    header("Location: register.php");
    exit();
}