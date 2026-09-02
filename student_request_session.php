<?php

session_start();

if(!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

include("db.php");

$student_id = (int) $_SESSION["user_id"];
$error = "";
$message = "";

$mentor_id = (int) ($_GET["mentor_id"] ?? 0);

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $mentor_id = (int) ($_POST["mentor_id"] ?? 0);
    $message = trim($_POST["message"] ?? "");
}

$is_post = $_SERVER["REQUEST_METHOD"] == "POST";

if($is_post)
{
    mysqli_begin_transaction($conn);
}

$sql = "SELECT role FROM users WHERE user_id = ?";

if($is_post)
{
    $sql .= " FOR UPDATE";
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if(!$user || $user["role"] != "student")
{
    if($is_post)
    {
        mysqli_rollback($conn);
    }

    exit("Only students can send session requests.");
}

$sql = "SELECT COUNT(*) AS total
        FROM session_requests
        WHERE student_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

$request_count = (int) $row["total"];

mysqli_stmt_close($stmt);

$sql = "SELECT users.user_id, users.full_name
        FROM users
        INNER JOIN mentor_profiles
        ON users.user_id = mentor_profiles.user_id
        WHERE users.user_id = ?
        AND users.role = 'mentor'
        AND mentor_profiles.verification_status = 'verified'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $mentor_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$mentor = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if($is_post)
{
    if($request_count >= 3)
    {
        $error = "You have used your 3 free requests.";
    }
    elseif(!$mentor)
    {
        $error = "Please choose a verified mentor from Find a Mentor.";
    }
    elseif($message == "")
    {
        $error = "Please write a message.";
    }
    else
    {
        $sql = "INSERT INTO session_requests
                (student_id, mentor_id, message, status)
                VALUES (?, ?, ?, 'pending')";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "iis",
            $student_id,
            $mentor_id,
            $message
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        mysqli_commit($conn);

        $_SESSION["request_success"] =
            "Your session request has been sent successfully.";

        header("Location: student_my_requests.php");
        exit();
    }

    mysqli_rollback($conn);
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Request a Session</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1 align="center">Request a Session</h1>

<hr>

<div style="width: 90%; max-width: 700px; margin: 30px auto;">

    <?php
    if($error != "")
    {
    ?>
        <p>
            <?php echo htmlspecialchars($error); ?>
        </p>
    <?php
    }

    if($request_count >= 3)
    {
    ?>
        <p>
            You have used your 3 free session requests.
            Buy a subscription to send more.
        </p>
    <?php
    }
    elseif(!$mentor)
    {
    ?>
        <p>
            This mentor is unavailable.
            Please choose a verified mentor from Find a Mentor.
        </p>
    <?php
    }
    else
    {
    ?>
        <h3>
            Mentor:
            <?php echo htmlspecialchars($mentor["full_name"]); ?>
        </h3>

        <p>
            Free requests remaining:
            <?php echo 3 - $request_count; ?>
        </p>

        <form method="POST" action="student_request_session.php">

            <input
                type="hidden"
                name="mentor_id"
                value="<?php echo $mentor_id; ?>">

            <label for="message">Your Message</label>

            <br><br>

            <textarea
                id="message"
                name="message"
                rows="6"
                style="width: 100%; box-sizing: border-box;"
                placeholder="Explain what help you need."
                required><?php echo htmlspecialchars($message); ?></textarea>

            <br><br>

            <button type="submit">Send Request</button>

        </form>
    <?php
    }
    ?>

    <br>

    <form action="student_my_requests.php" method="GET">
        <button type="submit">Back to My Requests</button>
    </form>

    <br>

    <a href="search_mentor.php">Back to Find a Mentor</a>

</div>

<hr>

<p align="center">
    Copyright &copy; <?php echo date("Y"); ?>
</p>

</body>

</html>