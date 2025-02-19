<?php
session_start();

// Debug: capture that we reached the script
$_SESSION['debug'] = "Reached process_login.php\n";

/* Require Database Connection */
require_once "../inc_db_params.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Debug: store the inputs
    $_SESSION['debug'] .= "Email: {$email}\nPassword length: " . strlen($password) . "\n";

    try {
        $_SESSION['debug'] .= "Attempting DB connection using sqlite:../blog3795.sqlite\n";

        // Connect to SQLite
        $db = new PDO("sqlite:../blog3795.sqlite");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $_SESSION['debug'] .= "DB connection successful.\n";

        // Fetch user by email
        $_SESSION['debug'] .= "Preparing SELECT: 'SELECT id, password, role, isApproved, firstName, lastName FROM Users WHERE username = ?'\n";
        $stmt = $db->prepare("SELECT id, password, role, isApproved, firstName, lastName FROM Users WHERE username = ?");
        
        if (!$stmt) {
            $_SESSION['debug'] .= "Statement preparation failed: " . print_r($db->errorInfo(), true) . "\n";
        }
        
        // Execute statement
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug: show the fetched user (cautious in production)
        $_SESSION['debug'] .= "Fetched user row: " . print_r($user, true) . "\n";

        // Check password
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['debug'] .= "Password verified, checking role/isApproved...\n";
            if (strtolower($user['role']) === 'admin' || $user['isApproved']) {
                // Set session vars
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $email;
                $_SESSION['role']      = $user['role'];
                $_SESSION['firstName'] = $user['firstName'];
                $_SESSION['lastName']  = $user['lastName'];

                $_SESSION['debug'] .= "User authorized. Redirecting to /main.php\n";
                header("Location: /main.php");
                exit();
            } else {
                $_SESSION['error'] = "Your account is pending approval.";
                $_SESSION['debug'] .= "User is not approved. Redirecting back to login.\n";
                header("Location: login.php");
                exit();
            }
        } else {
            // Invalid credentials
            $_SESSION['error'] = "Invalid email or password.";
            $_SESSION['debug'] .= "Invalid credentials. Email or password mismatch.\n";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        $_SESSION['debug'] .= "Caught PDOException: " . $e->getMessage() . "\n";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['debug'] .= "Request method not POST, redirecting to login.\n";
    header("Location: login.php");
    exit();
}