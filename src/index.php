<?php session_start(); ?>
<?php include("./inc_header.php"); ?>
<?php include("./inc_db_params.php"); ?>
<?php include("./seed.php"); ?>

<?php 
if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
?>

<?php
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>

<!-- Inline CSS for a warm background, full viewport height, and fixed footer -->
<style>
  html, body {
    height: 100vh;
    overflow: hidden;
    margin: 0;
  }
  body {
    background-color: #fff4e6; /* Warm, creamy background color */
  }
  /* Fixed footer styling */
  .fixed-footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    background-color: #f8f9fa; /* Light gray background (adjust as needed) */
    text-align: center;
    padding: 1rem 0;
  }
</style>

<!-- Main content container filling the viewport -->
<div class="container d-flex align-items-center justify-content-center vh-100">
  <div class="row align-items-center g-lg-5 py-5">
    <div class="col-lg-7 text-center text-lg-start">
      <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">
        COMP3975 Assignment 1
      </h1>
      <p class="col-lg-10 fs-4">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Porro libero rem corrupti natus reiciendis obcaecati? Voluptatibus deserunt magni at asperiores, laborum ullam ut tempora facere aliquid deleniti ad nulla in!
      </p>
    </div>
    <div class="col-md-10 mx-auto col-lg-5">
      <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary">
        <div class="form-floating mb-3">
          <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
          <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>
        <div class="checkbox mb-3">
          <!-- Additional checkbox content can be added here if needed -->
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="button" onclick="window.location.href='/register/register.php'">
          Sign up
        </button>
        <hr class="my-4">
        <small class="text-body-secondary">
          By clicking Sign up, you agree to the terms of use.
        </small>
      </form>
    </div>
  </div>
</div>

<!-- Fixed footer -->
<footer class="fixed-footer">
  <?php include("./inc_footer.php"); ?>
</footer>