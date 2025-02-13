<?php
if (isset($_POST['update'])) {
    include("../../inc_db_params.php");
    include("../../utils.php"); // Optional: for sanitize_input()

    // Extract and sanitize form data
    $postId  = sanitize_input($_POST['id']);
    $title   = sanitize_input($_POST['Title']);
    $slug    = sanitize_input($_POST['Slug']);
    $content = sanitize_input($_POST['Content']);

    // Prepare the SQL statement for updating the blog post.
    // We update the title, slug, and content, and set updated_at to the current timestamp.
    $sql = "UPDATE Posts 
            SET title = :Title, 
                slug = :Slug, 
                content = :Content, 
                updated_at = CURRENT_TIMESTAMP 
            WHERE id = :id";

    // Create a prepared statement
    $stmt = $db->prepare($sql);

    if ($stmt) {
        // Bind values securely
        $stmt->bindValue(':Title', $title, SQLITE3_TEXT);
        $stmt->bindValue(':Slug', $slug, SQLITE3_TEXT);
        $stmt->bindValue(':Content', $content, SQLITE3_TEXT);
        $stmt->bindValue(':id', $postId, SQLITE3_INTEGER);

        // Execute the statement
        $exec = $stmt->execute();

        // Log error if execution fails
        if (!$exec) {
            error_log('SQLite execute() failed: ' . $db->lastErrorMsg());
        }

        // Close database connection
        $db->close();

        // Redirect back to the blog list if update was successful
        if ($exec) {
            header('Location: ../../index.php');
            exit;
        }
    } else {
        error_log('SQLite prepare() failed: ' . $db->lastErrorMsg());
    }
}
?>
