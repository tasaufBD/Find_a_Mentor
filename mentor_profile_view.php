<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "mentor") {
    header("Location: login.php");
    exit();
}

include("db.php");

$userId = $_SESSION["user_id"];

$stmt = mysqli_prepare($conn,
    "SELECT u.full_name, u.email,
            m.university, m.department, m.cgpa_range, m.graduation_year,
            m.current_organization, m.current_position, m.skills,
            m.achievements, m.career_story, m.external_profile_url,
            m.goal_achieved, m.verification_status
     FROM users u
     JOIN mentor_profiles m ON u.user_id = m.user_id
     WHERE u.user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$profile = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="mentor_dashboard.php">PeerPath</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>My Profile</h2>

        <p>
            Verification Status:
            <span class="status-<?php echo htmlspecialchars($profile["verification_status"]); ?>">
                <?php echo htmlspecialchars(ucfirst($profile["verification_status"])); ?>
            </span>
            <?php if ($profile["verification_status"] != "verified"): ?>
                — you won't appear in student search results until an admin verifies your profile.
            <?php endif; ?>
        </p>

        <table class="table-basic">
            <tr><td><strong>Full Name</strong></td><td><?php echo htmlspecialchars($profile["full_name"]); ?></td></tr>
            <tr><td><strong>Email</strong></td><td><?php echo htmlspecialchars($profile["email"]); ?></td></tr>
            <tr><td><strong>University</strong></td><td><?php echo htmlspecialchars($profile["university"]); ?></td></tr>
            <tr><td><strong>Department</strong></td><td><?php echo htmlspecialchars($profile["department"]); ?></td></tr>
            <tr><td><strong>Graduation Year</strong></td><td><?php echo htmlspecialchars($profile["graduation_year"]); ?></td></tr>
            <tr><td><strong>CGPA Range (at start)</strong></td><td><?php echo htmlspecialchars($profile["cgpa_range"]); ?></td></tr>
            <tr><td><strong>Current Organization</strong></td><td><?php echo htmlspecialchars($profile["current_organization"]); ?></td></tr>
            <tr><td><strong>Current Position</strong></td><td><?php echo htmlspecialchars($profile["current_position"]); ?></td></tr>
            <tr><td><strong>Goal Achieved</strong></td><td><?php echo htmlspecialchars($profile["goal_achieved"]); ?></td></tr>
            <tr><td><strong>Skills</strong></td><td><?php echo htmlspecialchars($profile["skills"]); ?></td></tr>
            <tr><td><strong>Achievements</strong></td><td><?php echo htmlspecialchars($profile["achievements"]); ?></td></tr>
            <tr><td><strong>Career Story</strong></td><td><?php echo nl2br(htmlspecialchars($profile["career_story"])); ?></td></tr>
            <tr>
                <td><strong>External Profile</strong></td>
                <td>
                    <?php if (!empty($profile["external_profile_url"])): ?>
                        <a href="<?php echo htmlspecialchars($profile["external_profile_url"]); ?>" target="_blank" rel="noopener noreferrer">
                            View Link
                        </a>
                    <?php else: ?>
                        Not provided
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="btn-row">
            <a href="mentor_profile_edit.php" class="btn">Edit Profile</a>
            <a href="mentor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
