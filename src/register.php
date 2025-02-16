<?php session_start();?>

<!-- Require/Include -->
<?php require_once  "inc_db_params.php";?>
<?php include       ("./inc_header.php"); ?>
<?php require_once  "utils.php";?>

<!-- Check if User is already logged in -->
<?php 
if (isset($_SESSION['username'])) 
{
    header("Location: index.php");
    exit();
}
?>

<!-- Registration Page START -->
    <h2 class="mt-5">Register</h2>

    <!-- Display Errors -->
    <?php
    if (isset($_SESSION['error'])) 
    {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <!-- Form -->
    <form action="process_register.php" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email (Username)</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <div class="mb-3">
            <label for="first_name" class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" required>
        </div>
        
        <!-- Register Button -->
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <!-- Form END -->
    
    <p class="mt-3">Already have an account? <a href="login.php">Login here</a></p>

    <?php include("./inc_footer.php"); ?>
    <!-- Registration Page END -->
