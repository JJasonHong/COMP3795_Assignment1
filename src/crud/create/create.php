<?php 
session_start();
include("../../inc_header.php"); 

// Check if user is logged in and is either admin or contributor
if (!isset($_SESSION['username'])) {
    header("Location: ../../login/login.php");
    exit();
}
?>

<h1>Create New Article</h1>

<div class="row">
    <div class="col-md-6">
        <form action="process_create.php" method="post">
            <!-- Title Field -->
            <div class="form-group">
                <label for="Title" class="control-label">Title</label>
                <input type="text" class="form-control" name="Title" id="Title" required />
            </div>

            <!-- Body Field -->
            <div class="form-group">
                <label for="Body" class="control-label">Body</label>
                <textarea class="form-control" name="Body" id="Body" rows="8" required></textarea>
            </div>

            <!-- Start Date Field -->
            <div class="form-group">
                <label for="StartDate" class="control-label">Start Date</label>
                <input type="date" class="form-control" name="StartDate" id="StartDate" required />
            </div>

            <!-- End Date Field -->
            <div class="form-group">
                <label for="EndDate" class="control-label">End Date</label>
                <input type="date" class="form-control" name="EndDate" id="EndDate" required />
            </div>

            <!-- Hidden ContributorUsername field -->
            <input type="hidden" name="ContributorUsername" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" />

            <div class="form-group">
                <a href="../../index.php" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="Create" name="create" class="btn btn-success" />
            </div>
        </form>
    </div>
</div>

<br />

<?php include("../../inc_footer.php"); ?>
