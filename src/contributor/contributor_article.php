<?php 
session_start();
include("../inc_header.php");
include("../inc_db_params.php");

// Redirect if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch the user ID for the logged-in user
$stmt_user = $db->prepare("SELECT id FROM Users WHERE username = :username");
$stmt_user->bindValue(':username', $_SESSION['username'], SQLITE3_TEXT);
$result_user = $stmt_user->execute();
$user = $result_user->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    echo "<p class='alert alert-danger'>User not found.</p>";
    exit();
}

$user_id = $user['id'];

// Prepare a statement to fetch posts by the current contributor
$stmt = $db->prepare("
    SELECT id AS PostId, title AS Title, content AS Body, created_at AS CreateDate, updated_at AS UpdateDate 
    FROM Posts 
    WHERE user_id = :user_id 
    ORDER BY created_at DESC
");
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
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
            <th>Last Updated</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($article = $articles->fetchArray(SQLITE3_ASSOC)): ?>
          <tr>
            <td><?php echo htmlspecialchars($article['Title']); ?></td>
            <td><?php echo htmlspecialchars($article['CreateDate']); ?></td>
            <td><?php echo htmlspecialchars($article['UpdateDate']); ?></td>
            <td>
              <a href="/crud/display/display.php?id=<?php echo urlencode($article['PostId']); ?>" class="btn btn-info btn-sm">View</a>
              <a href="/crud/update/update.php?id=<?php echo urlencode($article['PostId']); ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="/crud/delete/delete.php?id=<?php echo urlencode($article['PostId']); ?>" class="btn btn-danger btn-sm">Delete</a>
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