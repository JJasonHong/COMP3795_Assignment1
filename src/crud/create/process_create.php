<?php include("../../inc_header.php"); ?>
<?php include("../../inc_db_params.php"); ?>

<?php
// Verify that the Articles table exists
$table_check = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='Articles'");
if (!$table_check) {
    die("<p class='alert alert-danger'>Error: The 'Articles' table does not exist! Make sure to create it first.</p>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../../login/login.php");
        exit();
    }

    // Retrieve input from the form
    $title = $_POST['Title'];
    $body = $_POST['Body'];
    $startDate = $_POST['StartDate'];
    $endDate = $_POST['EndDate'];
    $contributorUsername = $_SESSION['username'];

    // Prepare an INSERT statement to avoid SQL injection
    $stmt = $db->prepare("INSERT INTO Articles (Title, Body, StartDate, EndDate, ContributorUsername) 
                         VALUES (:title, :body, :startDate, :endDate, :contributorUsername)");

    // Bind form values to the SQL statement
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':body', $body, SQLITE3_TEXT);
    $stmt->bindValue(':startDate', $startDate, SQLITE3_TEXT);
    $stmt->bindValue(':endDate', $endDate, SQLITE3_TEXT);
    $stmt->bindValue(':contributorUsername', $contributorUsername, SQLITE3_TEXT);

    // Execute the statement
    $result = $stmt->execute();

    // Provide a success or error message
    if ($result) {
        echo "<p class='alert alert-success'>Article added successfully.</p>";
    } else {
        echo "<p class='alert alert-danger'>Error adding article: " . $db->lastErrorMsg() . "</p>";
    }

    // Close the database connection
    $db->close();
}
?>

<p>
    <a href="../../index.php" class="btn btn-primary">&lt;&lt; Back to List</a>
</p>
<br />
<?php include("../../inc_footer.php"); ?>
