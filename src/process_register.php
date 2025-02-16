<?php session_start();?>

<!-- Require/Include -->
<?php require_once "inc_db_params.php";?>
<?php require_once "utils.php";?>


<!-- Process Registration -->
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    $email      = trim($_POST["email"]);
    $password   = $_POST["password"];
    $first_name = trim($_POST["first_name"]);
    $last_name  = trim($_POST["last_name"]);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
        $_SESSION['error'] = "Invalid email format.";
        header("Location: register.php");
        exit();
    }

    // Validate password complexity
    if (!validate_password($password)) 
    {
        $_SESSION['error'] = "Password must be at least 8 characters long, 
                              include one uppercase, one lowercase, 
                              one number, and one special character.";
        header("Location: register.php");
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try 
    {
        $db = new PDO("sqlite:blog3795.sqlite");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if email already exists
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) 
        {
            $_SESSION['error'] = "Email is already registered.";
            header("Location: register.php");
            exit();
        }

        // Insert new user with default values (IsApproved=0, Role="contributor")
        $stmt = $db->prepare("INSERT INTO users (username, password, firstName, lastName, registrationDate, isApproved, role) 
                              VALUES (?, ?, ?, ?, datetime('now'), 0, 'contributor')");
        $stmt->execute([$email, $hashed_password, $first_name, $last_name]);

        $_SESSION['message'] = "Registration successful! Waiting for admin approval.";
        header("Location: login.php");
        exit();
    } 
    
    catch (PDOException $e) 
    {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        header("Location: register.php");
        exit();
    }
} 

// Redirect if accessed without POST request
else 
{
    header("Location: register.php");
    exit();
}
?>