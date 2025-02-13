<?php
if (isset($_POST['id'])) {
    include("../../inc_db_params.php"); // Include database connection

    // Get the post ID from the POST request
    $id = $_POST['id'];

    // Prepare the DELETE SQL statement for the blog post
    $stmt = $db->prepare("DELETE FROM Posts WHERE id = :id");

    if ($stmt) {
        // Bind the post ID parameter as an integer to prevent SQL injection
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        // Execute the query
        $exec = $stmt->execute();

        // Check if execute() was successful
        if ($exec) {
            header('Location: ../../index.php'); // Redirect back to the list
            exit;
        } else {
            error_log('SQLite execute() failed: ' . $db->lastErrorMsg());
        }
    } else {
        error_log('SQLite statement preparation failed: ' . $db->lastErrorMsg());
    }

    // Close the database connection
    $db->close();
}
?>
<p>
    <a href="../../index.php" class="btn btn-primary">&lt;&lt; Back to List</a>
</p>
