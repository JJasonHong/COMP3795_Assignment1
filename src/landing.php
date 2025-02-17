<!-- Style sheets -->
<!-- Require/Include: Should we put db params and seed into the header file? -->
<?php include("./inc_header.php"); ?>
<?php include("./inc_db_params.php"); ?> 
<?php include("./seed.php") ?>
<!-- font -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text:ital@0;1&display=swap" rel="stylesheet">

<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<link href="landing-css.css" rel="stylesheet">

<!-- Navbar START -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">

        <!-- Left Section (Row with Two Columns) -->
        <div class="col text-center"> 
            <a class="navbar-brand" href="#" id="navbar-home">Home</a>
        </div>

        <div class="row w-100">
            <div class="col d-flex align-items-center">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Latest</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./crud/create/create.php">Post</a>
                    </li>
                </ul>
            </div>
            <!-- Left Section END -->


            <!-- Right Section (Row with Three Columns) -->
            <div class="col d-flex justify-content-end">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/register/register.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/login/login.php">Login</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Account
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Admin Stuff?</a>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- Right Section END -->
        </div>
    </div>
</nav>
<!-- Navbar END -->


<!-- Container Start -->
<div class="container" id="mainContainer">
    <!-- Section Start -->
    <section class="header-nav-wrap content has-banner has-avatar avatar-style-circle has-title has-description has-nav">
        <!-- HEADER Start -->
        <header id="header" class="blog-header" role="banner">
            <figure id="header-banner" class="header-image-wrapper header-module">
                <a href="/" class="header-image cover loaded imgLiquid_bgSize imgLiquid_ready">
                    <!-- Banner IMG -->
                    <img src="/images/banner-sand.webp" alt="banner">
                </a>
            </figure>
            <!-- Logo START -->
            <!-- <a href="#" id="header-avatar" class="blogger-avatar header-module ease">
                <img src="/images/logo.png" class="img-responsive" alt="avatar">
            </a> -->
            <!-- Logo END -->

            <!-- Blog Posts -->
            <div>
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
                                <img src="/images/stock-post-img.jpeg" alt="post img" class="pull-left img-responsive thumb margin10 img-thumbnail">
                                <br>
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

            <!-- Welcome Message START -->
            <?php if (isset($_SESSION['username'])): ?>
                <p class="alert alert-success">Welcome, <strong>
                        <?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
                <p><a href="logout/logout.php" class="btn btn-danger">Logout</a></p>
            <?php else: ?>
            <?php endif; ?>
            <!-- Welcome Message END -->
        </header>
        <!-- HEADER End -->
    </section>
    <!-- Section END -->
</div>
<!-- Container End -->
