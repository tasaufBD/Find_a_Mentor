<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

include("db.php");

$studentId = $_SESSION["user_id"];

//Load student's own profile for matching
$stmt = mysqli_prepare($conn, "SELECT * FROM student_profiles WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $studentId);
mysqli_stmt_execute($stmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$profileIncomplete = empty($student["career_goal"]);

$suggestedMentors = [];

if (!$profileIncomplete) {

    // scoring
    $mentorResult = mysqli_query($conn,
        "SELECT u.user_id, u.full_name, m.university, m.department, m.cgpa_range,
                m.goal_achieved, m.skills, m.current_organization, m.current_position
         FROM users u
         JOIN mentor_profiles m ON u.user_id = m.user_id
         WHERE m.verification_status = 'verified'");

    $studentSkills = array_map("trim", array_map("strtolower", explode(",", $student["skills"] ?? "")));

    while ($mentor = mysqli_fetch_assoc($mentorResult)) {

        $score   = 0;
        $reasons = [];

        if ($mentor["university"] == $student["university"] && !empty($mentor["university"])) {
            $score += 3;
            $reasons[] = "Same University";
        }

        if ($mentor["department"] == $student["department"] && !empty($mentor["department"])) {
            $score += 2;
            $reasons[] = "Same Department";
        }

        if ($mentor["goal_achieved"] == $student["career_goal"]) {
            $score += 5;
            $reasons[] = "Matches Your Goal";
        }

        if ($mentor["cgpa_range"] == $student["cgpa_range"] && !empty($mentor["cgpa_range"])) {
            $score += 4;
            $reasons[] = "Similar Academic Background";
        }

        $mentorSkills = array_map("trim", array_map("strtolower", explode(",", $mentor["skills"] ?? "")));
        $sharedSkills = array_intersect($studentSkills, $mentorSkills);
        $sharedSkills = array_filter($sharedSkills);
        $sharedCount  = min(count($sharedSkills), 3);

        if ($sharedCount > 0) {
            $score += $sharedCount * 2;
            $reasons[] = "Shared Skills: " . implode(", ", array_slice($sharedSkills, 0, 3));
        }

        if ($score > 0) {
            $suggestedMentors[] = [
                "user_id"  => $mentor["user_id"],
                "name"     => $mentor["full_name"],
                "org"      => $mentor["current_organization"],
                "position" => $mentor["current_position"],
                "score"    => $score,
                "reasons"  => $reasons,
            ];
        }
    }

    usort($suggestedMentors, function ($a, $b) {
        return $b["score"] - $a["score"];
    });
    $suggestedMentors = array_slice($suggestedMentors, 0, 3);
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
        <p>Here's what's happening on PeerPath right now.</p>
    </div>

    <div class="card">
        <h3>Suggested Mentors for You</h3>

        <?php if ($profileIncomplete): ?>

            <p>Complete your profile to get mentors matched to your background and goal.</p>
            <a href="student_profile_edit.php" class="btn">Complete My Profile</a>

        <?php elseif (empty($suggestedMentors)): ?>

            <p>No strong matches yet. Try browsing all mentors instead.</p>
            <a href="search_mentor.php" class="btn">Browse All Mentors</a>

        <?php else: ?>

            <div class="row">
                <?php foreach ($suggestedMentors as $mentor): ?>
                    <div class="col-4">
                        <div class="card">
                            <h4><?php echo htmlspecialchars($mentor["name"]); ?></h4>
                            <p><?php echo htmlspecialchars($mentor["position"]); ?> at
                               <?php echo htmlspecialchars($mentor["org"]); ?></p>
                            <p><small>Match Score: <?php echo $mentor["score"]; ?></small></p>
                            <p><small><?php echo htmlspecialchars(implode(" · ", $mentor["reasons"])); ?></small></p>
                            <a href="view_mentor.php?id=<?php echo $mentor["user_id"]; ?>" class="btn">View Profile</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Your Menu</h3>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <h4>My Profile</h4>
                    <p>View your saved details.</p>
                    <a href="student_profile_view.php" class="btn">View Profile</a>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h4>Edit Profile</h4>
                    <p>Update your background and goal.</p>
                    <a href="student_profile_edit.php" class="btn">Edit Profile</a>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h4>Find a Mentor</h4>
                    <p>Search all verified mentors.</p>
                    <a href="search_mentor.php" class="btn">Search Mentors</a>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <h4>My Requests</h4>
                    <p>Track your session requests.</p>
                    <a href="student_my_requests.php" class="btn">My Requests</a>
                </div>
            </div>
        </div>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
