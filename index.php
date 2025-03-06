<!-- Style sheets -->
<!-- Require/Include: Should we put db params and seed into the header file? -->
<?php if (isset($_SESSION['username'])): ?>
    <?php include("./inc_header.php"); ?>
<?php else: ?>
    <?php include("./inc_header_before_login.php"); ?>
<?php endif; ?>
<?php include("./inc_db_params.php"); ?> 
<?php include("./seed.php") ?>


<!-- Container Start -->
<div class="container" id="mainContainer">
    <!-- Section Start -->
    <section class="header-nav-wrap content has-banner has-avatar avatar-style-circle has-title has-description has-nav">
        <!-- HEADER Start -->
        <header id="header" class="blog-header" role="banner">
            <figure id="header-banner" class="header-image-wrapper header-module">
                <a href="/" class="header-image cover loaded imgLiquid_bgSize imgLiquid_ready">
                    <!-- Banner IMG -->
                    <img src="/images/bg3.jpeg" alt="banner">
                </a>
            </figure>

            <!-- Blog Posts -->
            <div>
                <?php
                if ($db !== FALSE) {
                    // Query to fetch all articles along with the author's name (if any).
                    $SQLstring = "
                        SELECT 
                            a.ArticleId, 
                            a.Title, 
                            a.Body, 
                            a.CreatDate AS CreateDate, 
                            a.StartDate, 
                            a.EndDate,
                            a.ContributorUsername,
                            u.firstName || ' ' || u.lastName AS authorName
                        FROM Articles a
                        LEFT JOIN Users u ON a.ContributorUsername = u.username
                        WHERE DATE('now') BETWEEN a.StartDate AND a.EndDate
                        ORDER BY a.CreatDate DESC
                    ";
                    $QueryResult = $db->query($SQLstring);

                    if ($QueryResult) {
                        while ($row = $QueryResult->fetchArray(SQLITE3_ASSOC)) {
                            // Display a snippet (first 100 characters) of the content
                            $snippet = substr($row['Body'], 0, 100);
                ?>
                            <div class="blogShort">
                                <h1><?php echo htmlspecialchars($row['Title']); ?></h1>
                                <br>
                                <article>
                                    <p>
                                        <?php
                                        echo htmlspecialchars($snippet) . (strlen($row['Body']) > 100 ? "..." : "");
                                        ?>
                                    </p>
                                </article>
                                <p class="text-muted">
                                    <small>Posted by: <?php echo htmlspecialchars($row['authorName']); ?></small>
                                </p>
                                <a class="btn btn-blog pull-right marginBottom10" href="/crud/display/display.php?id=<?php echo urlencode($row['ArticleId']); ?>">READ MORE</a>
                            </div>
                <?php
                        }
                        echo '<div class="col-md-12 gap10"></div>';
                    } else {
                        echo "<p class='alert alert-danger'>Error: Unable to fetch article data.</p>";
                    }
                    // Close the database connection.
                    $db->close();
                }
                ?>
            </div>

        </header>
        <!-- HEADER End -->
    </section>
    <!-- Section END -->
</div>
<!-- Container End -->
<?php include("./inc_footer.php"); ?>