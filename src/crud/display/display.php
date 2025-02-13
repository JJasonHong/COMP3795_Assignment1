<?php include("../../inc_header.php"); ?>
<?php include("../../inc_db_params.php"); ?>

<h1>Display Blog Post</h1>

<?php
// Initialize variables
$postId = $title = $slug = $content = $created_at = $updated_at = "";

if (isset($_GET['id'])) {
    // Get post id from URL
    $id = $_GET['id'];

    // Prepare an SQL statement to fetch the post data
    $stmt = $db->prepare("SELECT * FROM Posts WHERE id = :id");

    if ($stmt) {
        // Bind the post ID parameter as an integer
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        // Execute the query
        $result = $stmt->execute();

        // Fetch the post data
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $postId     = $row['id'];
            $title      = $row['title'];
            $slug       = $row['slug'];
            $content    = $row['content'];
            $created_at = $row['created_at'];
            $updated_at = $row['updated_at'];
        } else {
            echo "<p class='alert alert-danger'>Blog post not found.</p>";
        }

        // Finalize the result set
        $result->finalize();
    } else {
        echo "<p class='alert alert-danger'>Error preparing statement: " . $db->lastErrorMsg() . "</p>";
    }

    // Close database connection
    $db->close();
}
?>

<table class="table table-striped">
    <tr>
        <td><strong>Post ID:</strong></td>
        <td><?php echo htmlspecialchars($postId); ?></td>
    </tr>
    <tr>
        <td><strong>Title:</strong></td>
        <td><?php echo htmlspecialchars($title); ?></td>
    </tr>
    <tr>
        <td><strong>Slug:</strong></td>
        <td><?php echo htmlspecialchars($slug); ?></td>
    </tr>
    <tr>
        <td><strong>Content:</strong></td>
        <td><?php echo nl2br(htmlspecialchars($content)); ?></td>
    </tr>
    <tr>
        <td><strong>Created At:</strong></td>
        <td><?php echo htmlspecialchars($created_at); ?></td>
    </tr>
    <tr>
        <td><strong>Updated At:</strong></td>
        <td><?php echo htmlspecialchars($updated_at); ?></td>
    </tr>
</table>

<br />
<a href="../../index.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>

<?php include("../../inc_footer.php"); ?>
