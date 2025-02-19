<?php
session_start();

/**
 * If the debug session variable doesn't exist, initialize it so we can append.
 */
if (!isset($_SESSION['debug'])) {
    $_SESSION['debug'] = "";
}

/**
 * Record that we've entered the script
 */
$_SESSION['debug'] .= "Reached process_register.php in " . __DIR__ . "\n";

/* Require/Include */
require_once "../inc_db_params.php";   // Adjust path if needed
require_once "../utils.php";           // Contains your validation functions, etc.

// Check if user is already logged in
if (isset($_SESSION['username'])) {
    $_SESSION['debug'] .= "User already logged in as {$_SESSION['username']}.\n";
    $_SESSION['error'] = "You're already signed in, please log out first.";
    header("Location: /index.php");
    exit();
}

// Note the request method
$_SESSION['debug'] .= "Request method: {$_SERVER["REQUEST_METHOD"]}\n";

if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    // Grab form inputs
    $email      = trim($_POST["email"]);
    $password   = $_POST["password"];
    $first_name = trim($_POST["first_name"]);
    $last_name  = trim($_POST["last_name"]);

    // Record inputs (excluding raw password for security)
    $_SESSION['debug'] .= "User submitted email: {$email}\n";
    $_SESSION['debug'] .= "User submitted firstName: {$first_name}, lastName: {$last_name}\n";
    $_SESSION['debug'] .= "Password length: " . strlen($password) . "\n";

    // Validate Email
    $_SESSION['debug'] .= "Validating email...\n";
    $error_message = "";
    if (!is_valid_email($email, $error_message)) 
    {
        $_SESSION['debug'] .= "Email validation failed: {$error_message}\n";
        $_SESSION['error'] = $error_message;
        header("Location: /register/register.php");
        exit();
    }

    // Validate password complexity
    $_SESSION['debug'] .= "Validating password...\n";
    if (!validate_password($password, $error_message)) 
    {
        $_SESSION['debug'] .= "Password validation failed: {$error_message}\n";
        $_SESSION['error'] = $error_message;
        header("Location: /register/register.php");
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $_SESSION['debug'] .= "Password hashed successfully.\n";

    try 
    {
        $_SESSION['debug'] .= "Connecting to DB with sqlite:../blog3795.sqlite\n";
        // If your sqlite file is actually 2 levels above, you might use ../../
        $db = new PDO("sqlite:../blog3795.sqlite");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $_SESSION['debug'] .= "DB connection successful.\n";

        // Check if email already exists
        $_SESSION['debug'] .= "Checking if email is already registered...\n";
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        if (!$stmt) {
            $_SESSION['debug'] .= "Statement preparation failed: " . print_r($db->errorInfo(), true) . "\n";
        }
        $stmt->execute([$email]);
        $count = $stmt->fetchColumn();
        $_SESSION['debug'] .= "Existing users with that email: {$count}\n";

        if ($count > 0) {
            $_SESSION['debug'] .= "Email is already registered.\n";
            $_SESSION['error'] = "Email is already registered.";
            header("Location: /register/register.php");
            exit();
        }

        // Insert new user (isApproved=0, role='contributor')
        $_SESSION['debug'] .= "Inserting new user into database...\n";
        $stmt = $db->prepare("
            INSERT INTO users (username, password, firstName, lastName, registrationDate, isApproved, role) 
            VALUES (?, ?, ?, ?, datetime('now'), 0, 'contributor')
        ");
        $stmt->execute([$email, $hashed_password, $first_name, $last_name]);

        $_SESSION['debug'] .= "User inserted successfully.\n";
        $_SESSION['message'] = "Registration successful! Waiting for admin approval.";
        $_SESSION['debug'] .= "Redirecting to /login/login.php\n";
        
        header("Location: /login/login.php");
        exit();
    } 
    catch (PDOException $e) 
    {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        $_SESSION['debug'] .= "PDOException: " . $e->getMessage() . "\n";
        header("Location: /register/register.php");
        exit();
    }
} 
else 
{
    $_SESSION['debug'] .= "Request method not POST; redirecting to /register/register.php\n";
    header("Location: /register/register.php");
    exit();
}