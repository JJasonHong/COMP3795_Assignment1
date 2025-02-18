<?php 
session_start();
include("../inc_header.php");
include("../inc_db_params.php");

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Prepare a statement to fetch articles by the current contributor (using the username as key)
$stmt = $db->prepare("SELECT ArticleId, Title, Body, CreateDate, StartDate, EndDate 
                      FROM Articles 
                      WHERE ContributorUsername = :username 
                      ORDER BY CreateDate DESC");
$stmt->bindValue(':username', $_SESSION['username'], SQLITE3_TEXT);
$articles = $stmt->execute();
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <h1 class="text-center mt-5">My Articles</h1>
      
      <table class="table mt-4">
        <thead>
          <tr>
            <th>Title</th>
            <th>Created On</th>
            <th>Valid From</th>
            <th>Valid To</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($article = $articles->fetchArray(SQLITE3_ASSOC)): ?>
          <tr>
            <td><?php echo htmlspecialchars($article['Title']); ?></td>
            <td><?php echo htmlspecialchars($article['CreateDate']); ?></td>
            <td><?php echo htmlspecialchars($article['StartDate']); ?></td>
            <td><?php echo htmlspecialchars($article['EndDate']); ?></td>
            <td>
              <a href="/crud/display/display.php?id=<?php echo urlencode($article['ArticleId']); ?>" class="btn btn-info btn-sm">View</a>
              <a href="/crud/update/update.php?id=<?php echo urlencode($article['ArticleId']); ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="/crud/delete/delete.php?id=<?php echo urlencode($article['ArticleId']); ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
      
      <div class="mt-3 text-center">
        <a href="../main.php" class="btn btn-primary">
          <i class="glyphicon glyphicon-arrow-left"></i> Back to Home
        </a>
      </div>
    </div>
  </div>
</div>

<?php include("../inc_footer.php"); ?>