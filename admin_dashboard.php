<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="admin_dashboard.php">PeerPath Admin</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?> (Admin)
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h2>
        <p><b>Use the Menu below to Manage Users and Verify Mentors.</b></p>
    </div>

    <div class="card">
        <h3>Admin Menu</h3>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <h4>Manage Users</h4>
                    <p>View and manage all registered accounts.</p>
                    <a href="admin_manage_users.php" class="btn">Manage Users</a>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <h4>Verify Mentors</h4>
                    <p>Review and approve pending mentor profiles.</p>
                    <a href="admin_verify_mentors.php" class="btn">Verify Mentors</a>
                </div>
            </div>
        </div>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
