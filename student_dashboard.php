<?php


session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="student_dashboard.php">PeerPath</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?> (Student)
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["full_name"]); ?></h2>
        <p>Use the menu below to manage your profile and connect with mentors.</p>
    </div>

    <div class="card">
        <h3>Your Menu</h3>
        <ul>
            <li><a href="student_profile_view.php">View My Profile</a></li>
            <li><a href="student_profile_edit.php">Edit My Profile</a></li>
            <li><a href="search_mentor.php">Find a Mentor</a></li>
            <li><a href="student_my_requests.php">My Session Requests</a></li>
        </ul>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
