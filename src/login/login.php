<?php session_start(); ?>

<!-- Require/Include -->
<?php require_once "../inc_db_params.php"; ?>
<?php require_once "../utils.php"; ?>
<?php require_once "../inc_header.php"; ?>


<!-- Check if User is already logged in -->
<?php 
if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!-- Login Page START -->
<h2 class="mt-5">Login</h2>

<!-- Display Errors -->
<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<!-- Form -->
<form action="process_login.php" method="post">
    <div class="mb-3">
        <label for="email" class="form-label">Email (Username)</label>
        <input type="email" class="form-control" name="email" required>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" name="password" required>
    </div>
    
    <!-- Login Button -->
    <button type="submit" class="btn btn-primary">Login</button>
</form>
<!-- Form END -->

<p class="mt-3">Don't have an account? <a href="/register/register.php">Register here</a></p>

<?php include("../inc_footer.php"); ?>
<!-- Login Page END -->
