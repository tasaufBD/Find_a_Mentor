<?php
//Work on it.
//Landing page after admin logs in. Links out to admin-only pages.

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include("db.php");

// Small stat counts for the dashboard cards below
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM users WHERE role = 'student'"))["c"];

$totalMentors = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM users WHERE role = 'mentor'"))["c"];

$pendingMentors = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM mentor_profiles WHERE verification_status = 'pending'"))["c"];

$pendingSessions = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM session_requests WHERE status = 'pending'"))["c"];
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
        <h2>Platform Overview</h2>
        <table class="table-basic">
            <tr>
                <th>Total Students</th>
                <th>Total Mentors</th>
                <th>Mentors Pending Verification</th>
                <th>Pending Session Requests</th>
            </tr>
            <tr>
                <td><?php echo $totalStudents; ?></td>
                <td><?php echo $totalMentors; ?></td>
                <td><?php echo $pendingMentors; ?></td>
                <td><?php echo $pendingSessions; ?></td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h3>Admin Menu</h3>
        <ul>
            <li><a href="admin_manage_users.php">Manage Users</a></li>
            <li><a href="admin_verify_mentors.php">Verify Mentors</a></li>
        </ul>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
