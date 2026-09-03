<?php

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
        <p><b>Use the Menu below to manage your profile and respond to students reaching out to you.</b></p>
    </div>

    <div class="card">
        <h3>Your Menu</h3>
        <div class="row">
            <div class="col-3">
                <div class="card">
                    <h4>My Profile</h4>
                    <p>View your saved details.</p>
                    <a href="mentor_profile_view.php" class="btn">View Profile</a>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <h4>Edit Profile</h4>
                    <p>Update your background and journey.</p>
                    <a href="mentor_profile_edit.php" class="btn">Edit Profile</a>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <h4>Session Requests</h4>
                    <p>Review and respond to students.</p>
                    <a href="mentor_requests.php" class="btn">View Requests</a>
                </div>
            </div>
        </div>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
