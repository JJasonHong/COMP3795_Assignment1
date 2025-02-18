<?php 
session_start(); // Add this at the top
include("../inc_header.php");
include("../inc_db_params.php");

// Update role check to handle case sensitivity
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Admin', 'admin'])) {
    header("Location: ../index.php");
    exit();
}

// Handle approve/reject actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id']) && isset($_POST['action'])) {
        $user_id = $_POST['user_id'];
        $action = $_POST['action'];
        
        if ($action === 'approve') {
            // Fix: Changed is_approved to isApproved to match the database schema
            $stmt = $db->prepare("UPDATE Users SET isApproved = 1 WHERE id = :id");
        } elseif ($action === 'change_role') {
            $new_role = $_POST['new_role'];
            $stmt = $db->prepare("UPDATE Users SET role = :role WHERE id = :id");
            $stmt->bindValue(':role', $new_role, SQLITE3_TEXT);
        }
        
        if ($stmt) {
            $stmt->bindValue(':id', $user_id, SQLITE3_INTEGER);
            $stmt->execute();
        }
    }
}

// Fix column names in the query to match your schema
$users = $db->query("SELECT id, username, firstName, lastName, role, isApproved FROM Users ORDER BY registrationDate DESC");
?>

<style>
.table {
    margin-top: 20px;
}
.btn-sm {
    margin: 2px;
}
.form-control-sm {
    display: inline-block;
    width: auto;
    margin-right: 5px;
}
.mt-3 {
    margin-top: 15px;
}
</style>

<h1>User Management</h1>

<table class="table">
    <thead>
        <tr>
            <th>Username (Email)</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $users->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars($user['firstName']); ?></td>
            <td><?php echo htmlspecialchars($user['lastName']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td><?php echo $user['isApproved'] ? 'Approved' : 'Pending'; ?></td>
            <td>
                <?php if (!$user['isApproved']): ?>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                </form>
                <?php endif; ?>
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <input type="hidden" name="action" value="change_role">
                    <select name="new_role" class="form-control-sm">
                        <option value="admin">Admin</option>
                        <option value="contributor">Contributor</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Change Role</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<div class="mt-3">
    <a href="../main.php" class="btn btn-primary">
        <i class="glyphicon glyphicon-arrow-left"></i> Back to Home
    </a>
</div>

<?php include("../inc_footer.php"); ?>