<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include("db.php");

$viewerRole = $_SESSION["role"];
$viewerId   = $_SESSION["user_id"];

if ($viewerRole == "student") {
    $dashboardLink = "student_dashboard.php";
} elseif ($viewerRole == "mentor") {
    $dashboardLink = "mentor_dashboard.php";
} else {
    $dashboardLink = "admin_dashboard.php";
}

$mentorId = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

if ($mentorId <= 0) {
    header("Location: " . $dashboardLink);
    exit();
}

//Fetch mentor profile
$stmt = mysqli_prepare($conn,
    "SELECT u.user_id, u.full_name, u.email,
            m.university, m.department, m.cgpa_range, m.graduation_year,
            m.current_organization, m.current_position, m.skills,
            m.achievements, m.career_story, m.external_profile_url,
            m.goal_achieved, m.verification_status
     FROM users u
     JOIN mentor_profiles m ON u.user_id = m.user_id
     WHERE u.user_id = ? AND u.role = 'mentor'");

mysqli_stmt_bind_param($stmt, "i", $mentorId);
mysqli_stmt_execute($stmt);
$mentorResult = mysqli_stmt_get_result($stmt);
$mentor = mysqli_fetch_assoc($mentorResult);

$notFound = false;
$notAvailable = false;

if (!$mentor) {
    $notFound = true;
} elseif ($mentor["verification_status"] != "verified" && $viewerRole != "admin") {
    // Students/mentors should not be able to view unverified or rejected mentors just by guessing an id in the URL.
    $notAvailable = true;
}

$requestMessage = "";
$requestError   = "";
$existingRequest = null;

if ($viewerRole == "student" && !$notFound && !$notAvailable) {

    // Check if this student already has an active request with this mentor
    $checkStmt = mysqli_prepare($conn,
        "SELECT status FROM session_requests
         WHERE student_id = ? AND mentor_id = ?
         ORDER BY requested_at DESC LIMIT 1");
    mysqli_stmt_bind_param($checkStmt, "ii", $viewerId, $mentorId);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);
    $existingRequest = mysqli_fetch_assoc($checkResult);

    $hasActiveRequest = $existingRequest &&
        ($existingRequest["status"] == "pending" || $existingRequest["status"] == "accepted");

    // new request submission
    if ($_SERVER["REQUEST_METHOD"] == "POST" && !$hasActiveRequest) {

        $requestMessage = trim($_POST["message"]);

        if (empty($requestMessage)) {
            $requestError = "Please write a short message before sending your request.";
        } else {
            $insertStmt = mysqli_prepare($conn,
                "INSERT INTO session_requests (student_id, mentor_id, message, status, requested_at)
                 VALUES (?, ?, ?, 'pending', NOW())");
            mysqli_stmt_bind_param($insertStmt, "iis", $viewerId, $mentorId, $requestMessage);

            if (mysqli_stmt_execute($insertStmt)) {
                // Refresh the existing-request check so the form is replaced with the new pending status below.
                $existingRequest = ["status" => "pending"];
                $hasActiveRequest = true;
                $requestMessage = "";
            } else {
                $requestError = "Could not send your request. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mentor Profile - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="<?php echo $dashboardLink; ?>">PeerPath</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <?php if ($notFound): ?>

        <div class="card">
            <p class="alert-error">Mentor not found.</p>
            <a href="<?php echo $dashboardLink; ?>" class="btn">Back to Dashboard</a>
        </div>

    <?php elseif ($notAvailable): ?>

        <div class="card">
            <p class="alert-error">This mentor profile is not currently available.</p>
            <a href="<?php echo $dashboardLink; ?>" class="btn">Back to Dashboard</a>
        </div>

    <?php else: ?>

        <div class="card">
            <h2>
                <?php echo htmlspecialchars($mentor["full_name"]); ?>
                <?php if ($mentor["verification_status"] == "verified"): ?>
                    <span class="status-verified">(Verified Mentor)</span>
                <?php endif; ?>
            </h2>

            <table class="table-basic">
                <tr><td><strong>University</strong></td><td><?php echo htmlspecialchars($mentor["university"]); ?></td></tr>
                <tr><td><strong>Department</strong></td><td><?php echo htmlspecialchars($mentor["department"]); ?></td></tr>
                <tr><td><strong>Graduation Year</strong></td><td><?php echo htmlspecialchars($mentor["graduation_year"]); ?></td></tr>
                <tr><td><strong>CGPA Range (at start)</strong></td><td><?php echo htmlspecialchars($mentor["cgpa_range"]); ?></td></tr>
                <tr><td><strong>Current Organization</strong></td><td><?php echo htmlspecialchars($mentor["current_organization"]); ?></td></tr>
                <tr><td><strong>Current Position</strong></td><td><?php echo htmlspecialchars($mentor["current_position"]); ?></td></tr>
                <tr><td><strong>Goal Achieved</strong></td><td><?php echo htmlspecialchars($mentor["goal_achieved"]); ?></td></tr>
                <tr><td><strong>Skills</strong></td><td><?php echo htmlspecialchars($mentor["skills"]); ?></td></tr>
                <tr><td><strong>Achievements</strong></td><td><?php echo htmlspecialchars($mentor["achievements"]); ?></td></tr>
                <tr><td><strong>Career Story</strong></td><td><?php echo nl2br(htmlspecialchars($mentor["career_story"])); ?></td></tr>
                <tr>
                    <td><strong>External Profile</strong></td>
                    <td>
                        <?php if (!empty($mentor["external_profile_url"])): ?>
                            <a href="<?php echo htmlspecialchars($mentor["external_profile_url"]); ?>" target="_blank" rel="noopener noreferrer">
                                View Profile Link
                            </a>
                        <?php else: ?>
                            Not provided
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php if ($viewerRole == "student"): ?>
            <div class="card">
                <h3>Request a Session</h3>

                <?php if ($existingRequest && ($existingRequest["status"] == "pending" || $existingRequest["status"] == "accepted")): ?>

                    <p>
                        Your request to this mentor is currently:
                        <span class="status-<?php echo htmlspecialchars($existingRequest["status"]); ?>">
                            <?php echo htmlspecialchars(ucfirst($existingRequest["status"])); ?>
                        </span>
                    </p>

                <?php else: ?>

                    <?php if ($requestError != ""): ?>
                        <div class="alert-error"><?php echo htmlspecialchars($requestError); ?></div>
                    <?php endif; ?>

                    <?php if ($existingRequest && $existingRequest["status"] == "rejected"): ?>
                        <p>Your previous request to this mentor was rejected. You may send a new one below.</p>
                    <?php endif; ?>

                    <form method="post">
                        <div class="form-group">
                            <label>What would you like to discuss?</label>
                            <textarea name="message" rows="4"><?php echo htmlspecialchars($requestMessage); ?></textarea>
                        </div>
                        <input type="submit" value="Send Request" class="btn">
                    </form>

                <?php endif; ?>
            </div>
        <?php endif; ?>

        <a href="<?php echo $dashboardLink; ?>" class="btn btn-secondary">Back to Dashboard</a>

    <?php endif; ?>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
