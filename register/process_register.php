<?php
session_start();

// Debug: capture that we reached the script
$_SESSION['debug'] = "Reached process_register.php\n";

// Require Database Connection
require_once "../inc_db_params.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $firstName = trim($_POST["firstName"]);
    $lastName = trim($_POST["lastName"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash password

    $_SESSION['debug'] .= "Received form data: \nFirst Name: $firstName\nLast Name: $lastName\nEmail: $email\n";

    try {
        $_SESSION['debug'] .= "Attempting DB connection using sqlite:../blog3795.sqlite\n";

        // Connect to SQLite
        $db = new PDO("sqlite:" . __DIR__ . "/../blog3795.sqlite");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $_SESSION['debug'] .= "DB connection successful.\n";

        // Check if user already exists
        $_SESSION['debug'] .= "Checking if email already exists...\n";
        $stmt = $db->prepare("SELECT id FROM Users WHERE username = ?");
        $stmt->execute([$email]);
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingUser) {
            $_SESSION['error'] = "Email already registered.";
            $_SESSION['debug'] .= "Email already exists. Redirecting to register.\n";
            header("Location: register.php");
            exit();
        }

        // Insert new user
        $_SESSION['debug'] .= "Inserting new user...\n";
        $stmt = $db->prepare("INSERT INTO Users (firstName, lastName, username, password, role, isApproved) VALUES (?, ?, ?, ?, 'user', 0)");
        $stmt->execute([$firstName, $lastName, $email, $hashedPassword]);

        $_SESSION['debug'] .= "User successfully registered. Redirecting to login.\n";

        $_SESSION['success'] = "Registration successful! Please wait for approval.";
        header("Location: ../login/login.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        $_SESSION['debug'] .= "Caught PDOException: " . $e->getMessage() . "\n";
        header("Location: register.php");
        exit();
    }
} else {
    $_SESSION['debug'] .= "Request method not POST, redirecting to register.\n";
    header("Location: register.php");
    exit();
}
