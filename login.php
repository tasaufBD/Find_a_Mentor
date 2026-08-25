<?php

session_start();
include("db.php");

$email = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email)) {
        $error = "Enter your email";
    } elseif (empty($password)) {
        $error = "Enter your password";
    } else {

        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);

            if (password_verify($password, $row["password"])) {

                $_SESSION["user_id"]   = $row["user_id"];
                $_SESSION["full_name"] = $row["full_name"];
                $_SESSION["role"]      = $row["role"];

                if ($row["role"] == "student") {
                    header("Location: student_dashboard.php");
                } elseif ($row["role"] == "mentor") {
                    header("Location: mentor_dashboard.php");
                } else {
                    header("Location: admin_dashboard.php");
                }
                exit();

            } else {
                $error = "Invalid Email or Password";
            }

        } else {
            $error = "Invalid Email or Password";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page-header">
    <h1>PeerPath</h1>
</div>

<div class="container">
    <div class="card form-box">
        <h2>Log in</h2>

        <?php if ($error != ""): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post">

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password">
            </div>

            <div class="btn-row">
                <input type="submit" value="Login" class="btn btn-primary">
                <a href="register.php" class="btn btn-secondary">Create an account</a>
            </div>

        </form>
    </div>
</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
