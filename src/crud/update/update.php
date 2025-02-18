<?php include("../../inc_header.php"); ?>
<?php include("../../inc_db_params.php"); ?>

<?php
// Initialize variables for the blog post
$postId  = $title = $slug = $content = "";

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

        // Fetch the post data
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

<h1>Update Blog Post</h1>

<div class="row">
    <div class="col-md-6">
        <form action="process_update.php" method="post">
            <!-- Hidden field to carry the post ID -->
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($postId); ?>" />

            <div class="form-group">
                <label class="control-label">Post ID</label>
                <p><?php echo htmlspecialchars($postId); ?></p>
            </div>

            <div class="form-group">
                <label for="Title" class="control-label">Title</label>
                <input type="text" class="form-control" name="Title" id="Title" value="<?php echo htmlspecialchars($title); ?>" required />
            </div>

            <div class="form-group">
                <label for="Slug" class="control-label">Slug</label>
                <input type="text" class="form-control" name="Slug" id="Slug" value="<?php echo htmlspecialchars($slug); ?>" required />
            </div>

            <div class="form-group">
                <label for="Content" class="control-label">Content</label>
                <textarea class="form-control" name="Content" id="Content" rows="8" required><?php echo htmlspecialchars($content); ?></textarea>
            </div>

            <div class="form-group">
                <a href="../../main.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="Update" name="update" class="btn btn-warning" />
            </div>
        </form>
    </div>
</div>

<br />

<?php include("../../inc_footer.php"); ?>
