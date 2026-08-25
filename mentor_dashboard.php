<?php

// Work on it.
// Landing page after a mentor logs in. Links out to every mentor-facing feature page.


session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "mentor") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentor Dashboard - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="mentor_dashboard.php">PeerPath</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?> (Mentor)
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h2>
        <p>Use the menu below to manage your profile and respond to students reaching out to you.</p>
    </div>

    <div class="card">
        <h3>Your Menu</h3>
        <ul>
            <li><a href="mentor_profile_view.php">View My Profile</a></li>
            <li><a href="mentor_profile_edit.php">Edit My Profile</a></li>
            <li><a href="mentor_requests.php">Session Requests</a></li>
        </ul>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
