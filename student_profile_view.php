<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

include("db.php");

$id = $_SESSION["user_id"];


$sql = "SELECT
            users.full_name,
            users.email,
            users.created_at,
            student_profiles.*
        FROM users
        INNER JOIN student_profiles
            ON users.user_id = student_profiles.user_id
        WHERE users.user_id = '$id'
        AND users.role = 'student'";

$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);


$profileFound = true;

if (!$row) {
    $profileFound = false;
}

$completion = 0;
$completedFields = 0;
$totalFields = 9;

if ($profileFound) {

    $profileFields = [
        $row["university"],
        $row["department"],
        $row["academic_year"],
        $row["cgpa_range"],
        $row["skills"],
        $row["interests"],
        $row["career_goal"],
        $row["target_detail"],
        $row["experience"]
    ];

    foreach ($profileFields as $field) {
        if ($field !== null && trim((string)$field) !== "") {
            $completedFields++;
        }
    }

    $completion = round(($completedFields / $totalFields) * 100);
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Student Profile - PeerPath</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="navbar">

    <div class="nav-links">
        <a href="student_dashboard.php">PeerPath</a>
    </div>

    <div class="nav-user">
        Logged in as
        <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        (Student)

        &nbsp;|&nbsp;

        <a href="logout.php">Logout</a>
    </div>

</div>

<div class="container">

    <?php if (!$profileFound): ?>

        <div class="card">

            <h2>Student Profile</h2>

            <p class="alert-error">
                Your student profile could not be found.
            </p>

            <a href="student_dashboard.php" class="btn btn-secondary">
                Back to Dashboard
            </a>

        </div>

    <?php else: ?>

      
        <div class="card">

            <h2>Profile Completion</h2>

            <p>
                You have completed
                <strong><?php echo $completedFields; ?></strong>
                out of
                <strong><?php echo $totalFields; ?></strong>
                profile fields.
            </p>

            <progress
                value="<?php echo $completion; ?>"
                max="100"
                style="width: 100%; height: 25px;">
            </progress>

            <p>
                Your profile is
                <strong><?php echo $completion; ?>% complete.</strong>
            </p>

            <?php if ($completion < 100): ?>

                <p class="status-pending">
                    Complete your profile to receive better mentor recommendations.
                </p>

            <?php else: ?>

                <p class="status-verified">
                    Your profile is complete.
                </p>

            <?php endif; ?>

        </div>

        <div class="card">

            <h2>My Profile</h2>
<table class="table-basic">

    <tr>
        <td><strong>User ID</strong></td>
        <td>
            <?php echo trim((string)$row["user_id"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["user_id"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Full Name</strong></td>
        <td>
            <?php echo trim((string)$row["full_name"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["full_name"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Email</strong></td>
        <td>
            <?php echo trim((string)$row["email"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["email"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>University</strong></td>
        <td>
            <?php echo trim((string)$row["university"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["university"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Department</strong></td>
        <td>
            <?php echo trim((string)$row["department"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["department"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Academic Year</strong></td>
        <td>
            <?php echo trim((string)$row["academic_year"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["academic_year"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>CGPA Range</strong></td>
        <td>
            <?php echo trim((string)$row["cgpa_range"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["cgpa_range"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Skills</strong></td>
        <td>
            <?php echo trim((string)$row["skills"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["skills"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Interests</strong></td>
        <td>
            <?php echo trim((string)$row["interests"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["interests"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Career Goal</strong></td>
        <td>
            <?php echo trim((string)$row["career_goal"]) === ""
                ? "Not provided"
                : htmlspecialchars(str_replace("_", " ", $row["career_goal"])); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Target Detail</strong></td>
        <td>
            <?php echo trim((string)$row["target_detail"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["target_detail"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Experience</strong></td>
        <td>
            <?php echo trim((string)$row["experience"]) === ""
                ? "Not provided"
                : htmlspecialchars($row["experience"]); ?>
        </td>
    </tr>

    <tr>
        <td><strong>Member Since</strong></td>
        <td>
            <?php
            echo date("d F Y", strtotime($row["created_at"]));
            ?>
        </td>
    </tr>

</table>

            <br>

            <div class="btn-row">

                <a href="student_profile_edit.php" class="btn">
                    Edit Profile
                </a>

                <a href="student_dashboard.php" class="btn btn-secondary">
                    Back to Dashboard
                </a>

            </div>

        </div>

    <?php endif; ?>

</div>

<p class="site-footer">
    Copyright &copy; <?php echo date("Y"); ?> PeerPath
</p>

</body>

</html>