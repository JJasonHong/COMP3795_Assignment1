<?php include("../../inc_header.php"); ?>
<?php include("../../inc_db_params.php"); ?>

<h1>Delete Blog Post</h1>

<?php
// Initialize variables
$postId = $title = $slug = $content = "";

// Check if 'id' is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare an SQL statement to fetch the blog post data
    $stmt = $db->prepare("SELECT * FROM Posts WHERE id = :id");

    if ($stmt) {
        // Bind the post ID parameter as an integer
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        // Execute the query
        $result = $stmt->execute();

        // Fetch the blog post data
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $postId  = $row['id'];
            $title   = $row['title'];
            $slug    = $row['slug'];
            $content = $row['content'];
        } else {
            echo "<p class='alert alert-danger'>Blog post not found.</p>";
        }

        // Finalize the result set
        $result->finalize();
    } else {
        echo "<p class='alert alert-danger'>Error preparing statement: " . $db->lastErrorMsg() . "</p>";
    }
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
</table>

<br />
<form action="process_delete.php" method="post">
    <input type="hidden" value="<?php echo htmlspecialchars($postId); ?>" name="id" />
    <a href="../../main.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
    &nbsp;&nbsp;&nbsp;
    <input type="submit" value="Delete" class="btn btn-danger" />
</form>

<br />

<?php include("../../inc_footer.php"); ?>
