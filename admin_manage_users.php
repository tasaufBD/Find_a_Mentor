<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include("db.php");

$message = "";

// Handle Delete Request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "delete") {
    $userIdToDelete = $_POST["user_id"];

    // Prevent admin from deleting themselves
    if ($userIdToDelete == $_SESSION["user_id"]) {
        $message = "Error: You cannot delete your own admin account.";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $userIdToDelete);
        
        if (mysqli_stmt_execute($stmt)) {
            $message = "User deleted successfully.";
        } else {
            $message = "Error deleting user.";
        }
    }
}

// Fetch all users from the database
$sql = "SELECT user_id, full_name, email, role FROM users ORDER BY full_name ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="admin_dashboard.php">PeerPath Admin</a>
        <a href="admin_verify_mentors.php">Verify Mentors</a>
        <a href="admin_manage_users.php">Manage Users</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container" style="max-width: 98%; width: 100%;">

    <h1 style="text-align: center; color: #2f6f5e; margin-bottom: 20px;">Manage Users</h1>

    <?php if ($message != ""): ?>
    <div style="text-align: center; margin-bottom: 15px; color: green; font-weight: bold;">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <?php endif; ?>

    <table class="table-basic" style="width: 100%; margin: 0 auto; table-layout: fixed;">
        <tr>
            <th style="width: 10%;">ID</th>
            <th style="width: 30%;">Full Name</th>
            <th style="width: 35%;">Email</th>
            <th style="width: 15%;">Role</th>
            <th style="width: 10%;">Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($row["user_id"]); ?></td>
            <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["email"]); ?></td>
            <td><?php echo htmlspecialchars($row["role"]); ?></td>
            <td>
                <form method="post" onsubmit="return confirm('Are you sure you want to delete this user?');" style="margin: 0;">
                    <input type="hidden" name="user_id" value="<?php echo $row["user_id"]; ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="submit" value="Delete" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">
                </form>
            </td>
        </tr>
<?php endwhile; ?>
    </table>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>