<?php session_start(); ?>

<!-- Require/Include -->
<?php 
require_once "../inc_db_params.php";
require_once "../inc_header_before_login.php";
require_once "../utils.php";

// Initialize Debug Log (if not already set)
if (!isset($_SESSION['debug'])) {
    $_SESSION['debug'] = "";
}

$_SESSION['debug'] .= "Reached register.php\n";

/* Check if User is already logged in */
if (isset($_SESSION['username'])) {
    $_SESSION['debug'] .= "User already logged in as {$_SESSION['username']}. Redirecting to main page.\n";
    header("Location: ../main.php");
    exit();
}
?>

<!-- Registration Page START -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mt-5">Register</h1>

            <!-- Display Debug Info (For Debugging Purposes) -->
            <?php
            if (isset($_SESSION['debug']) && !empty($_SESSION['debug'])) {
                echo '<div class="alert alert-secondary"><pre>' . htmlspecialchars($_SESSION['debug']) . '</pre></div>';
                unset($_SESSION['debug']); // Clear debug messages after displaying
            }

            // Display Errors
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); // Clear after displaying
            }
            ?>

            <!-- Form -->
            <form action="process_register.php" method="post">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" name="email" 
                        value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" 
                        required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                    <small class="text-muted">Password must contain:</small>
                    <ul class="text-muted small">
                        <li>At least 8 characters</li>
                        <li>At least one uppercase letter</li>
                        <li>At least one lowercase letter</li>
                        <li>At least one numeric character</li>
                        <li>At least one special character</li>
                    </ul>
                </div>

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name"
                        value="<?php echo isset($_SESSION['form_data']['first_name']) ? htmlspecialchars($_SESSION['form_data']['first_name']) : ''; ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name"
                        value="<?php echo isset($_SESSION['form_data']['last_name']) ? htmlspecialchars($_SESSION['form_data']['last_name']) : ''; ?>"
                        required>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn btn-primary">Register</button>
                <a href="/" class="btn btn-secondary">Back</a>
            </form>
            <!-- Form END -->

            <p class="mt-3">Already have an account? <a href="/login/login.php">Login here</a></p>

        </div>
    </div>
</div>

<?php include("../inc_footer.php"); ?>
<!-- Registration Page END -->

<!-- Custom Styling -->
<style>
    body {
        background-color: #d9b99b;
    }

    form {
        background-color: #faf0e6;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    }
</style>

<?php 
// Clear session form data after displaying to prevent retention across multiple loads
unset($_SESSION['form_data']);
?>