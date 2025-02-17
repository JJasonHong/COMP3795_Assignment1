<?php session_start(); ?>

<!-- Require/Include -->
<?php include("./inc_header.php"); ?>
<?php include("./inc_db_params.php"); ?>
<?php include("./seed.php")?>

<!-- Welcome Message START -->
<?php if (isset($_SESSION['username'])): ?>
    <p class="alert alert-success">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
    <p><a href="logout/logout.php" class="btn btn-danger">Logout</a></p>
<?php else: ?>
    <p class="alert alert-info">You are not logged in.</p>
<?php endif; ?>
<!-- Welcome Message END -->

<h1>List of Blog Posts</h1>

<p>
    <a href="./crud/create/create.php" class="btn btn-small btn-success">Create New Post</a>
</p>

<!-- Blog Layout Container -->
<div class="container">
    <div id="blog" class="row">
        <!-- Sidebar -->
        <div class="col-sm-2 paddingTop20">
        <nav class="nav-sidebar">
        <ul class="nav">
            <?php if (isset($_SESSION['username'])): ?>
                <!-- Show these items only when logged in -->
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'Admin' || $_SESSION['role'] === 'admin')): ?>
                    <li>
                        <a href="admin/manage_users.php" class="btn btn-warning">
                            <i class="glyphicon glyphicon-cog"></i> Admin Panel
                        </a>
                    </li>
                <?php endif; ?>
                <li><a href="logout/logout.php" class="btn btn-danger">Logout</a></li>
            <?php else: ?>
                <!-- Show these items when not logged in -->
                <li><a href="login/login.php">Login</a></li>
                <li><a href="register/register.php">Register</a></li>
            <?php endif; ?>
            <li class="nav-divider"></li>
            <li class="active"><a href="javascript:;"><span class="glyphicon glyphicon-star"></span> News</a></li>
            <li><a href="javascript:;">Latest news</a></li>
            <li><a href="javascript:;">Updates</a></li>
        </ul>
    </nav>
            <div><h2 class="add">Space for somthing</h2></div>
        </div>

        <!-- Blog Posts -->
        <div class="col-md-10">
            <?php
            if ($db !== FALSE) {
                // Query to fetch all posts along with the author's username.
                $SQLstring = "
                    SELECT 
                        a.ArticleId, 
                        a.Title, 
                        a.Body, 
                        a.CreateDate, 
                        a.StartDate,
                        a.EndDate,
                        a.ContributorUsername,
                        u.firstName || ' ' || u.lastName as authorName
                    FROM Articles a
                    LEFT JOIN Users u ON a.ContributorUsername = u.username
                    WHERE date('now') BETWEEN a.StartDate AND a.EndDate
                    ORDER BY a.CreateDate DESC
                ";
                $QueryResult = $db->query($SQLstring);

                if ($QueryResult) {
                    while ($row = $QueryResult->fetchArray(SQLITE3_ASSOC)) {
                        ?>
                        <div class="blogShort">
                            <h1><?php echo htmlspecialchars($row['Title']); ?></h1>
                            <!-- Placeholder image; replace with your own image source if available -->
                            <img src="http://via.placeholder.com/150" alt="post img" class="pull-left img-responsive thumb margin10 img-thumbnail">
                            <article>
                                <p>
                                    <?php 
                                    // Display a snippet (first 150 characters) of the content
                                    $snippet = substr($row['Body'], 0, 150);
                                    echo htmlspecialchars($snippet) . (strlen($row['Body']) > 150 ? "..." : "");
                                    ?>
                                </p>
                                <p class="text-muted">
                                    <small>Posted by: <?php echo htmlspecialchars($row['authorName']); ?></small>
                                </p>
                            </article>
                            <div class="pull-right">
                                <a class="btn btn-blog marginBottom10" href="/crud/display/display.php?id=<?php echo urlencode($row['ArticleId']); ?>">READ MORE</a>
                                
                                <?php if (isset($_SESSION['user_id']) && 
                                         (strtolower($_SESSION['role']) === 'admin' || 
                                          $_SESSION['user_id'] === $row['ContributorUsername'])): ?>
                                    <a href="/crud/update/update.php?id=<?php echo urlencode($row['ArticleId']); ?>" 
                                       class="btn btn-warning marginBottom10">Edit</a>
                                    <a href="/crud/delete/delete.php?id=<?php echo urlencode($row['ArticleId']); ?>" 
                                       class="btn btn-danger marginBottom10">Delete</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                    echo '<div class="col-md-12 gap10"></div>';
                } else {
                    echo "<p class='alert alert-danger'>Error: Unable to fetch post data.</p>";
                }
                // Close the database connection.
                $db->close();
            }
            ?>
        </div>
    </div>
</div>

<a href="/" class="btn btn-small btn-primary">&lt;&lt; BACK</a>

<!-- Inline CSS for styling the blog layout -->
<style>
    .blogShort { 
        border-bottom: 1px solid #ddd;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .add { 
        background: #333; 
        padding: 10%; 
        height: 300px; 
        color: #fff;
        text-align: center;
    }
    .nav-sidebar { 
        width: 100%;
        padding: 8px 0; 
        border-right: 1px solid #ddd;
    }
    .nav-sidebar a {
        color: #333;
        -webkit-transition: all 0.08s linear;
        -moz-transition: all 0.08s linear;
        -o-transition: all 0.08s linear;
        transition: all 0.08s linear;
    }
    .nav-sidebar .active a { 
        cursor: default;
        background-color: #34ca78; 
        color: #fff; 
    }
    .nav-sidebar .active a:hover {
        background-color: #37D980;   
    }
    .nav-sidebar .text-overflow a,
    .nav-sidebar .text-overflow .media-body {
        white-space: nowrap;
        overflow: hidden;
        -o-text-overflow: ellipsis;
        text-overflow: ellipsis; 
    }
    .btn-blog {
        color: #ffffff;
        background-color: #37d980;
        border-color: #37d980;
        border-radius: 0;
        margin-bottom: 10px;
    }
    .btn-blog:hover,
    .btn-blog:focus,
    .btn-blog:active,
    .btn-blog.active,
    .open .dropdown-toggle.btn-blog {
        color: white;
        background-color: #34ca78;
        border-color: #34ca78;
    }
    h2 { color: #34ca78; }
    .margin10 { margin-bottom: 10px; margin-right: 10px; }
</style>

<?php include("./inc_footer.php"); ?>
