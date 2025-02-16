<?php include("./inc_header.php"); ?>
<?php include("./inc_db_params.php"); ?>

<!-- Bootstrap CSS/JS includes (if not already in your header) -->
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>

<h1>List of Blog Posts</h1>

<p>
    <a href="./crud/create/create.php" class="btn btn-small btn-success">Create New Post</a>
</p>

<?php
// Ensure $db has been initialized via inc_db_params.php (e.g. $db = new SQLite3('./blog.sqlite');)

if ($db) {
    // Create the Users table if it doesn't exist
    $SQL_create_user_table = "CREATE TABLE IF NOT EXISTS Users (
        id INTEGER PRIMARY KEY AUTOINCREMENT, 
        username TEXT UNIQUE NOT NULL, -- This will be the email address 
        password TEXT NOT NULL,
        firstName TEXT NOT NULL, 
        lastName TEXT NOT NULL,
        registrationDate DATETIME DEFAULT CURRENT_TIMESTAMP,
        isApproved INTEGER NOT NULL DEFAULT 0,
        role TEXT CHECK(role IN ('Admin', 'Contributor')) DEFAULT 'Contributor'
        )";
    $db->exec($SQL_create_user_table);

    // Create the Posts table if it doesn't exist.
    $SQL_create_post_table = "CREATE TABLE IF NOT EXISTS Posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title TEXT NOT NULL,
        slug TEXT UNIQUE NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES Users(id)
    );";
    $db->exec($SQL_create_post_table);

    // Insert default users if the Users table is empty.
    $SQL_check_user_empty = "SELECT COUNT(*) as count FROM Users";
    $result = $db->querySingle($SQL_check_user_empty, true);
    
    if ($result['count'] == 0) 
    {
        $SQL_insert_users = "
            INSERT INTO Users (username, password, firstName, lastName, registrationDate, isApproved, role)
            VALUES 
                ('alice@test.com', '".password_hash('secret', PASSWORD_BCRYPT)."', 'Alice', 'Johnson', datetime('now'), 1, 'Admin'),
                ('bob@test.com', '".password_hash('secret', PASSWORD_BCRYPT)."', 'Bob', 'Smith', datetime('now'), 0, 'Contributor'),
                ('charlie@test.com', '".password_hash('secret', PASSWORD_BCRYPT)."', 'Charlie', 'Brown', datetime('now'), 0, 'Contributor');
        ";
        $db->exec($SQL_insert_users);
    }

// Insert default posts if the Posts table is empty
$SQL_check_posts_empty = "SELECT COUNT(*) as count FROM Posts";
$result = $db->querySingle($SQL_check_posts_empty, true);

// Fetch user IDs to ensure they exist before inserting posts
$users = [];
$SQL_fetch_users = "SELECT id FROM Users ORDER BY id ASC LIMIT 3";
$query = $db->query($SQL_fetch_users);
while ($row = $query->fetchArray(SQLITE3_ASSOC)) 
{
    $users[] = $row['id'];
}

// Posts are only inserted when theres atleast 3 users, which is what we use to seed the users table
if ($result['count'] == 0 && count($users) >= 3) 
{
    $SQL_insert_posts = "
        INSERT INTO Posts (user_id, title, slug, content)
        VALUES 
            ({$users[0]}, 'Getting Started with Blogging', 'getting-started', 'Welcome to the blogging world! This post will guide you through the basics.'),
            ({$users[1]}, 'My Daily Routine', 'my-daily-routine', 'A glimpse into my daily life and how I stay productive.'),
            ({$users[2]}, 'Life Hacks for Efficiency', 'life-hacks', 'Here are some useful tricks to make life easier and more efficient.');
    ";
    $db->exec($SQL_insert_posts);
}
} else {
    echo "<p class='alert alert-danger'>Error creating database.</p>";
}
?>

<!-- Blog Layout Container -->
<div class="container">
    <div id="blog" class="row">
        <!-- Sidebar -->
        <div class="col-sm-2 paddingTop20">
            <nav class="nav-sidebar">
                <ul class="nav">
                    <li class="active"><a href="javascript:;"><span class="glyphicon glyphicon-star"></span> News</a></li>
                    <li><a href="javascript:;">Latest news</a></li>
                    <li><a href="javascript:;">Updates</a></li>
                    <li class="nav-divider"></li>
                    <li><a href="javascript:;"><i class="glyphicon glyphicon-off"></i> Sign in</a></li>
                    <!-- Registration Button -->
                    <li><a href="register.php" class="btn btn-small btn-primary">Register</a></li>
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
                        p.id, 
                        p.title, 
                        p.slug, 
                        p.content, 
                        p.created_at, 
                        u.username AS author
                    FROM Posts p
                    JOIN Users u ON p.user_id = u.id
                    ORDER BY p.created_at DESC
                ";
                $QueryResult = $db->query($SQLstring);

                if ($QueryResult) {
                    while ($row = $QueryResult->fetchArray(SQLITE3_ASSOC)) {
                        ?>
                        <div class="blogShort">
                            <h1><?php echo htmlspecialchars($row['title']); ?></h1>
                            <!-- Placeholder image; replace with your own image source if available -->
                            <img src="http://via.placeholder.com/150" alt="post img" class="pull-left img-responsive thumb margin10 img-thumbnail">
                            <article>
                                <p>
                                    <?php 
                                    // Display a snippet (first 150 characters) of the content
                                    $snippet = substr($row['content'], 0, 150);
                                    echo htmlspecialchars($snippet) . (strlen($row['content']) > 150 ? "..." : "");
                                    ?>
                                </p>
                            </article>
                            <a class="btn btn-blog pull-right marginBottom10" href="/crud/display/display.php?id=<?php echo urlencode($row['id']); ?>">READ MORE</a>
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
