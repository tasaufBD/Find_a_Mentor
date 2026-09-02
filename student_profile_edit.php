<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    header("Location: login.php");
    exit();
}

include("db.php");

$id = $_SESSION["user_id"];

$sql = "SELECT * FROM student_profiles WHERE user_id = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: student_dashboard.php");
    exit();
}

$university   = $row["university"];
$department   = $row["department"];
$academic_year = $row["academic_year"];
$cgpa_range   = $row["cgpa_range"];
$skill        = $row["skills"];
$interest     = $row["interests"];
$career_goal  = $row["career_goal"];
$target_detail = $row["target_detail"];
$experience   = $row["experience"];

$universityError   = "";
$departmentError   = "";
$academicYearError = "";
$cgpaError         = "";
$skillError        = "";
$interestError     = "";
$careerGoalError   = "";
$targetDetailError = "";
$databaseError     = "";

$allowedCgpaRanges = [
    "<2.5",
    "2.5-3.0",
    "3.0-3.5",
    "3.5-4.0"
];

$allowedCareerGoals = [
    "FAANG",
    "MS_Abroad",
    "PhD_Abroad",
    "PhD_Local",
    "Research",
    "Startup",
    "Government",
    "Other"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $university    = trim($_POST["university"] ?? "");
    $department    = trim($_POST["department"] ?? "");
    $academic_year = trim($_POST["academic_year"] ?? "");
    $cgpa_range    = $_POST["cgpa_range"] ?? "";
    $skill         = trim($_POST["skills"] ?? "");
    $interest      = trim($_POST["interests"] ?? "");
    $career_goal   = $_POST["career_goal"] ?? "";
    $target_detail = trim($_POST["target_detail"] ?? "");
    $experience    = trim($_POST["experience"] ?? "");


    if ($university == "") {
        $universityError = "University is required.";
    } elseif (strlen($university) > 150) {
        $universityError = "University cannot exceed 150 characters.";
    }

    if ($department == "") {
        $departmentError = "Department is required.";
    } elseif (strlen($department) > 100) {
        $departmentError = "Department cannot exceed 100 characters.";
    }

    if ($academic_year == "") {
        $academicYearError = "Academic year is required.";
    } elseif (
        filter_var($academic_year, FILTER_VALIDATE_INT) === false ||
        $academic_year < 1 ||
        $academic_year > 5
    ) {
        $academicYearError = "Academic year must be between 1 and 5.";
    }

if (
    $cgpa_range != "<2.5" &&
    $cgpa_range != "2.5-3.0" &&
    $cgpa_range != "3.0-3.5" &&
    $cgpa_range != "3.5-4.0"
) {
    $cgpaError = "Select a valid CGPA range.";
}

if (
    $career_goal != "FAANG" &&
    $career_goal != "MS_Abroad" &&
    $career_goal != "PhD_Abroad" &&
    $career_goal != "PhD_Local" &&
    $career_goal != "Research" &&
    $career_goal != "Startup" &&
    $career_goal != "Government" &&
    $career_goal != "Other"
) {
    $careerGoalError = "Select a valid career goal.";
}


    if (strlen($skill) > 255) {
        $skillError = "Skills cannot exceed 255 characters.";
    }

    if (strlen($interest) > 255) {
        $interestError = "Interests cannot exceed 255 characters.";
    }

    if (strlen($target_detail) > 255) {
        $targetDetailError = "Target detail cannot exceed 255 characters.";
    }

    if (
        $universityError == "" &&
        $departmentError == "" &&
        $academicYearError == "" &&
        $cgpaError == "" &&
        $skillError == "" &&
        $interestError == "" &&
        $careerGoalError == "" &&
        $targetDetailError == ""
    ) {


        $sql = "UPDATE student_profiles
                SET university = '$university',
                    department = '$department',
                    academic_year = $academic_year,
                    cgpa_range = '$cgpa_range',
                    skills = '$skill',
                    interests = '$interest',
                    career_goal = '$career_goal',
                    target_detail = '$target_detail',
                    experience = '$experience'
                WHERE user_id = $id";

        if (mysqli_query($conn, $sql)) {

            header("Location: student_profile_view.php?updated=1");
            exit();

        } else {
            $databaseError = "Profile could not be updated. Please try again.";
        }
    }
}

?>

<!DOCTYPE html>

<html>

<head>

    <title>Edit Profile - PeerPath</title>
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

    <div class="card">

        <h2>Edit Profile</h2>

        <?php if ($databaseError != ""): ?>

            <div class="alert-error">
                <?php echo htmlspecialchars($databaseError); ?>
            </div>

        <?php endif; ?>

        <form method="post">

            <!-- University -->
            <div class="form-group">

                <label>University</label>

                <input
                    type="text"
                    name="university"
                    maxlength="150"
                    required
                    value="<?php echo htmlspecialchars($university); ?>">

                <?php if ($universityError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($universityError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <div class="form-group">

                <label>Department</label>

                <input
                    type="text"
                    name="department"
                    maxlength="100"
                    required
                    value="<?php echo htmlspecialchars($department); ?>">

                <?php if ($departmentError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($departmentError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <div class="form-group">

                <label>Academic Year</label>
                <input
                    type="number"
                    name="academic_year"
                    min="1"
                    max="5"
                    required
                    value="<?php echo htmlspecialchars($academic_year); ?>">

                <?php if ($academicYearError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($academicYearError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <div class="form-group">

                <label>CGPA Range</label>

                <select name="cgpa_range" required>


                    <option value="<2.5"
                        <?php echo ($cgpa_range == "<2.5") ? "selected" : ""; ?>>
                        &lt;2.5
                    </option>

                    <option value="2.5-3.0"
                        <?php echo ($cgpa_range == "2.5-3.0") ? "selected" : ""; ?>>
                        2.5-3.0
                    </option>

                    <option value="3.0-3.5"
                        <?php echo ($cgpa_range == "3.0-3.5") ? "selected" : ""; ?>>
                        3.0-3.5
                    </option>

                    <option value="3.5-4.0"
                        <?php echo ($cgpa_range == "3.5-4.0") ? "selected" : ""; ?>>
                        3.5-4.0
                    </option>

                </select>

                <?php if ($cgpaError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($cgpaError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <!-- Skills -->
            <div class="form-group">

                <label>Skills</label>

                <input
                    type="text"
                    name="skills"
                    maxlength="255"
                    placeholder="Example: PHP, MySQL, HTML, CSS"
                    value="<?php echo htmlspecialchars($skill); ?>">

                <?php if ($skillError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($skillError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <!-- Interests -->
            <div class="form-group">

                <label>Interests</label>

                <input
                    type="text"
                    name="interests"
                    maxlength="255"
                    placeholder="Example: Web development, cybersecurity"
                    value="<?php echo htmlspecialchars($interest); ?>">

                <?php if ($interestError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($interestError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <!-- Career Goal -->
            <div class="form-group">

                <label>Career Goal</label>

                <select name="career_goal" required>

                    <option value="FAANG"
                        <?php echo ($career_goal == "FAANG") ? "selected" : ""; ?>>
                        FAANG
                    </option>

                    <option value="MS_Abroad"
                        <?php echo ($career_goal == "MS_Abroad") ? "selected" : ""; ?>>
                        MS Abroad
                    </option>

                    <option value="PhD_Abroad"
                        <?php echo ($career_goal == "PhD_Abroad") ? "selected" : ""; ?>>
                        PhD Abroad
                    </option>

                    <option value="PhD_Local"
                        <?php echo ($career_goal == "PhD_Local") ? "selected" : ""; ?>>
                        PhD Local
                    </option>

                    <option value="Research"
                        <?php echo ($career_goal == "Research") ? "selected" : ""; ?>>
                        Research
                    </option>

                    <option value="Startup"
                        <?php echo ($career_goal == "Startup") ? "selected" : ""; ?>>
                        Startup
                    </option>

                    <option value="Government"
                        <?php echo ($career_goal == "Government") ? "selected" : ""; ?>>
                        Government
                    </option>

                    <option value="Other"
                        <?php echo ($career_goal == "Other") ? "selected" : ""; ?>>
                        Other
                    </option>

                </select>

                <?php if ($careerGoalError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($careerGoalError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <!-- Target Detail -->
            <div class="form-group">

                <label>Target Detail</label>

                <textarea
                    name="target_detail"
                    rows="4"
                    maxlength="255"
                    placeholder="Describe your specific career target"><?php
                    echo htmlspecialchars($target_detail);
                ?></textarea>

                <?php if ($targetDetailError != ""): ?>

                    <span class="field-error">
                        <?php echo htmlspecialchars($targetDetailError); ?>
                    </span>

                <?php endif; ?>

            </div>

            <div class="form-group">

                <label>Experience</label>

                <textarea
                    name="experience"
                    rows="5"
                    placeholder="Write about your projects, work or research experience"><?php
                    echo htmlspecialchars($experience);
                ?></textarea>

            </div>

            <div class="btn-row">

                <input
                    type="submit"
                    value="Update Profile"
                    class="btn">

                <a
                    href="student_profile_view.php"
                    class="btn btn-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

<p class="site-footer">
    Copyright &copy; <?php echo date("Y"); ?> PeerPath
</p>

</body>

</html>