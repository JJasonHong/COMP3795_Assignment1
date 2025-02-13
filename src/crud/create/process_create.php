<?php include("../../inc_header.php"); ?>
<?php include("../../inc_db_params.php"); ?>

<?php
// Verify that the Posts table exists
$table_check = $db->querySingle("SELECT name FROM sqlite_master WHERE type='table' AND name='Posts'");
if (!$table_check) {
    die("<p class='alert alert-danger'>Error: The 'Posts' table does not exist! Make sure to create it first.</p>");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create'])) {
    // Retrieve input from the form
    $title   = $_POST['Title'];
    $slug    = $_POST['Slug'];
    $content = $_POST['Content'];
    // Use the provided UserId, or default to 1 if not set
    $userId  = isset($_POST['UserId']) ? $_POST['UserId'] : 1;

    // Prepare an INSERT statement to avoid SQL injection
    $stmt = $db->prepare("INSERT OR IGNORE INTO Posts (user_id, title, slug, content) VALUES (:user_id, :title, :slug, :content)");

    // Bind form values to the SQL statement
    $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':slug', $slug, SQLITE3_TEXT);
    $stmt->bindValue(':content', $content, SQLITE3_TEXT);

    // Execute the statement
    $result = $stmt->execute();

    // Provide a success or error message
    if ($result) {
        echo "<p class='alert alert-success'>Post added successfully.</p>";
    } else {
        echo "<p class='alert alert-danger'>Error adding post: " . $db->lastErrorMsg() . "</p>";
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
