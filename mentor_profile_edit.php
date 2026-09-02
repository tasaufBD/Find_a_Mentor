<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "mentor") {
    header("Location: login.php");
    exit();
}

include("db.php");

$userId = $_SESSION["user_id"];

$stmt = mysqli_prepare($conn, "SELECT * FROM mentor_profiles WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$current = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

$university          = $current["university"];
$department          = $current["department"];
$cgpaRange           = $current["cgpa_range"];
$graduationYear      = $current["graduation_year"];
$currentOrganization = $current["current_organization"];
$currentPosition     = $current["current_position"];
$skills              = $current["skills"];
$achievements        = $current["achievements"];
$careerStory         = $current["career_story"];
$externalProfileUrl  = $current["external_profile_url"];
$goalAchieved        = $current["goal_achieved"];

$universityError = "";
$departmentError = "";
$cgpaError       = "";
$gradYearError   = "";
$orgError        = "";
$positionError   = "";
$skillsError     = "";
$goalError       = "";
$urlError        = "";
$message         = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $university          = trim($_POST["university"]);
    $department          = trim($_POST["department"]);
    $cgpaRange           = $_POST["cgpa_range"];
    $graduationYear      = trim($_POST["graduation_year"]);
    $currentOrganization = trim($_POST["current_organization"]);
    $currentPosition     = trim($_POST["current_position"]);
    $skills              = trim($_POST["skills"]);
    $achievements        = trim($_POST["achievements"]);
    $careerStory         = trim($_POST["career_story"]);
    $externalProfileUrl  = trim($_POST["external_profile_url"]);
    $goalAchieved        = $_POST["goal_achieved"];

    if (empty($university))          $universityError = "Enter your university";
    if (empty($department))          $departmentError = "Enter your department";
    if (empty($cgpaRange))           $cgpaError = "Select a CGPA range";
    if (empty($graduationYear) || !is_numeric($graduationYear)) $gradYearError = "Enter a valid graduation year";
    if (empty($currentOrganization)) $orgError = "Enter your current organization";
    if (empty($currentPosition))     $positionError = "Enter your current position";
    if (empty($skills))              $skillsError = "List at least one skill";
    if (empty($goalAchieved))        $goalError = "Select the goal you achieved";

    if (!empty($externalProfileUrl) && !filter_var($externalProfileUrl, FILTER_VALIDATE_URL)) {
        $urlError = "Enter a valid URL (e.g. https://linkedin.com/in/yourname)";
    }

    if ($universityError == "" && $departmentError == "" && $cgpaError == "" &&
        $gradYearError == "" && $orgError == "" && $positionError == "" &&
        $skillsError == "" && $goalError == "" && $urlError == "") {

        $update = mysqli_prepare($conn,
            "UPDATE mentor_profiles SET
                university = ?, department = ?, cgpa_range = ?, graduation_year = ?,
                current_organization = ?, current_position = ?, skills = ?,
                achievements = ?, career_story = ?, external_profile_url = ?,
                goal_achieved = ?
             WHERE user_id = ?");

        mysqli_stmt_bind_param($update, "sssisssssssi",
            $university, $department, $cgpaRange, $graduationYear,
            $currentOrganization, $currentPosition, $skills,
            $achievements, $careerStory, $externalProfileUrl,
            $goalAchieved, $userId);

        if (mysqli_stmt_execute($update)) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Update failed. Please try again.";
        }
    }
}


$cgpaOptions = ["<2.5", "2.5-3.0", "3.0-3.5", "3.5-4.0"];
$goalOptions = ["FAANG", "MS_Abroad", "PhD_Abroad", "PhD_Local", "Research", "Startup", "Government", "Other"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit My Profile - PeerPath</title>
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
        <h2>Edit My Profile</h2>

        <?php if ($message != ""): ?>
            <div class="<?php echo str_contains($message, 'successfully') ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label>University</label>
                <input type="text" name="university" value="<?php echo htmlspecialchars($university); ?>">
                <?php if ($universityError) echo "<span class='field-error'>$universityError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Department</label>
                <input type="text" name="department" value="<?php echo htmlspecialchars($department); ?>">
                <?php if ($departmentError) echo "<span class='field-error'>$departmentError</span>"; ?>
            </div>

            <div class="form-group">
                <label>CGPA Range (when you started pursuing this goal)</label>
                <select name="cgpa_range">
                    <option value="">-- Select --</option>
                    <?php foreach ($cgpaOptions as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo ($cgpaRange == $option) ? "selected" : ""; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($cgpaError) echo "<span class='field-error'>$cgpaError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Graduation Year</label>
                <input type="number" name="graduation_year" value="<?php echo htmlspecialchars($graduationYear); ?>">
                <?php if ($gradYearError) echo "<span class='field-error'>$gradYearError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Current Organization</label>
                <input type="text" name="current_organization" value="<?php echo htmlspecialchars($currentOrganization); ?>">
                <?php if ($orgError) echo "<span class='field-error'>$orgError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Current Position</label>
                <input type="text" name="current_position" value="<?php echo htmlspecialchars($currentPosition); ?>">
                <?php if ($positionError) echo "<span class='field-error'>$positionError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Skills (comma-separated)</label>
                <input type="text" name="skills" value="<?php echo htmlspecialchars($skills); ?>">
                <?php if ($skillsError) echo "<span class='field-error'>$skillsError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Achievements</label>
                <textarea name="achievements" rows="3"><?php echo htmlspecialchars($achievements); ?></textarea>
            </div>

            <div class="form-group">
                <label>Career Story</label>
                <textarea name="career_story" rows="5"><?php echo htmlspecialchars($careerStory); ?></textarea>
            </div>

            <div class="form-group">
                <label>External Profile URL (LinkedIn, GitHub, etc.)</label>
                <input type="text" name="external_profile_url" value="<?php echo htmlspecialchars($externalProfileUrl); ?>">
                <?php if ($urlError) echo "<span class='field-error'>$urlError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Goal Achieved</label>
                <select name="goal_achieved">
                    <option value="">-- Select --</option>
                    <?php foreach ($goalOptions as $option): ?>
                        <option value="<?php echo $option; ?>" <?php echo ($goalAchieved == $option) ? "selected" : ""; ?>>
                            <?php echo $option; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if ($goalError) echo "<span class='field-error'>$goalError</span>"; ?>
            </div>

            <div class="btn-row">
                <input type="submit" value="Save Changes" class="btn">
                <a href="mentor_profile_view.php" class="btn btn-secondary">Back to My Profile</a>
            </div>

        </form>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
