<?php include("../../inc_header.php"); ?>

<h1>Create New Blog Post</h1>

<div class="row">
    <div class="col-md-6">
        <form action="process_create.php" method="post">
            <!-- Title Field -->
            <div class="form-group">
                <label for="Title" class="control-label">Title</label>
                <input type="text" class="form-control" name="Title" id="Title" required />
            </div>

            <!-- Slug Field (a URL-friendly version of the title) -->
            <div class="form-group">
                <label for="Slug" class="control-label">Slug</label>
                <input type="text" class="form-control" name="Slug" id="Slug" required />
            </div>

            <!-- Content Field -->
            <div class="form-group">
                <label for="Content" class="control-label">Content</label>
                <textarea class="form-control" name="Content" id="Content" rows="8" required></textarea>
            </div>

            <!-- Hidden UserId (assign a default user for now) -->
            <input type="hidden" name="UserId" value="1" />

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
