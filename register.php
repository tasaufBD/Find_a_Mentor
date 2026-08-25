<?php

include("db.php");

$fullName = "";
$email    = "";
$role     = "";

$fullNameError = "";
$emailError    = "";
$passwordError = "";
$confirmError  = "";
$roleError     = "";
$message       = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = trim($_POST["full_name"]);
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];
    $role     = $_POST["role"] ?? "";

    //Validation
    if (empty($fullName)) {
        $fullNameError = "Enter your full name";
    }

    if (empty($email)) {
        $emailError = "Enter your email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Enter a valid email address";
    }

    if (empty($password)) {
        $passwordError = "Enter a password";
    } elseif (strlen($password) < 6) {
        $passwordError = "Password must be at least 6 characters";
    }

    if (empty($confirm)) {
        $confirmError = "Confirm your password";
    } elseif ($password !== $confirm) {
        $confirmError = "Passwords do not match";
    }

    if ($role !== "student" && $role !== "mentor") {
        $roleError = "Select a role";
    }

    if ($fullNameError == "" && $emailError == "" && $passwordError == "" &&
        $confirmError == "" && $roleError == "") {

        //Duplicate email check
        $check = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $checkResult = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($checkResult) > 0) {
            $message = "An account with this email already exists.";
        } else {

            //hashed password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insert = mysqli_prepare($conn,
                "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert, "ssss", $fullName, $email, $hashedPassword, $role);

            if (mysqli_stmt_execute($insert)) {

                $newUserId = mysqli_insert_id($conn);

                // Immediately create the matching empty profile row
                if ($role == "student") {
                    $profileStmt = mysqli_prepare($conn,
                        "INSERT INTO student_profiles (user_id) VALUES (?)");
                } else {
                    $profileStmt = mysqli_prepare($conn,
                        "INSERT INTO mentor_profiles (user_id) VALUES (?)");
                }
                mysqli_stmt_bind_param($profileStmt, "i", $newUserId);
                mysqli_stmt_execute($profileStmt);

                $message = "Registration successful. You can now log in.";
                $fullName = "";
                $email = "";
                $role = "";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page-header">
    <h1>PeerPath</h1>
</div>

<div class="container">
    <div class="card form-box">
        <h2>Create an account</h2>

        <?php if ($message != ""): ?>
            <div class="alert <?php echo str_contains($message, 'successful') ? 'alert-success' : 'alert-error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($fullName); ?>">
                <?php if ($fullNameError) echo "<span class='field-error'>$fullNameError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <?php if ($emailError) echo "<span class='field-error'>$emailError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
                <?php if ($passwordError) echo "<span class='field-error'>$passwordError</span>"; ?>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password">
                <?php if ($confirmError) echo "<span class='field-error'>$confirmError</span>"; ?>
            </div>

            <div class="form-group">
                <label>I am a</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="role" value="student" <?php echo ($role == "student") ? "checked" : ""; ?>>
                        Student
                    </label>
                    <label>
                        <input type="radio" name="role" value="mentor" <?php echo ($role == "mentor") ? "checked" : ""; ?>>
                        Mentor
                    </label>
                </div>
                <?php if ($roleError) echo "<span class='field-error'>$roleError</span>"; ?>
            </div>

            <div class="btn-row">
                <input type="submit" value="Register" class="btn btn-primary">
                <a href="login.php" class="btn btn-secondary">Already have an account?</a>
            </div>

        </form>
    </div>
</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
