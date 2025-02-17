<?php include("../../inc_header.php"); ?>
<?php include("../../inc_db_params.php"); ?>

<h1>Display Article</h1>

<?php
// Initialize variables
$articleId = $title = $body = $createDate = $startDate = $endDate = $contributorUsername = $authorName = "";

if (isset($_GET['id'])) {
    // Get article id from URL
    $id = $_GET['id'];

    // Prepare an SQL statement to fetch the article data with author name
    $stmt = $db->prepare("
        SELECT 
            a.*,
            u.firstName || ' ' || u.lastName as authorName
        FROM Articles a
        LEFT JOIN Users u ON a.ContributorUsername = u.username
        WHERE a.ArticleId = :id
    ");

    if ($stmt) {
        // Bind the article ID parameter as an integer
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

        // Execute the query
        $result = $stmt->execute();

        // Fetch the article data
        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $articleId = $row['ArticleId'];
            $title = $row['Title'];
            $body = $row['Body'];
            $createDate = $row['CreateDate'];
            $startDate = $row['StartDate'];
            $endDate = $row['EndDate'];
            $contributorUsername = $row['ContributorUsername'];
            $authorName = $row['authorName'];
        } else {
            echo "<p class='alert alert-danger'>Article not found.</p>";
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

<div class="blogShort">
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <img src="http://via.placeholder.com/150" alt="post img" class="pull-left img-responsive thumb margin10 img-thumbnail">
    <article>
        <?php echo $body; // Don't use htmlspecialchars here since we want to render HTML ?>
        <p class="text-muted">
            <small>Posted by: <?php echo htmlspecialchars($authorName); ?></small>
            <br>
            <small>Created on: <?php echo htmlspecialchars($createDate); ?></small>
            <br>
            <small>Valid from: <?php echo htmlspecialchars($startDate); ?> 
                   to: <?php echo htmlspecialchars($endDate); ?></small>
        </p>
    </article>
    <div class="pull-right">
        <?php if (isset($_SESSION['username']) && 
                 (strtolower($_SESSION['role']) === 'admin' || 
                  $_SESSION['username'] === $contributorUsername)): ?>
            <a href="/crud/update/update.php?id=<?php echo urlencode($articleId); ?>" 
               class="btn btn-warning marginBottom10">Edit</a>
            <a href="/crud/delete/delete.php?id=<?php echo urlencode($articleId); ?>" 
               class="btn btn-danger marginBottom10">Delete</a>
        <?php endif; ?>
    </div>
</div>

<br />
<a href="../../index.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>

<?php include("../../inc_footer.php"); ?>
