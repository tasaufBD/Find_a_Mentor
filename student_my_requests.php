<?php

session_start();

if(!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
    exit();
}

include("db.php");

$student_id = (int) $_SESSION["user_id"];
$success = "";

if(isset($_SESSION["request_success"]))
{
    $success = $_SESSION["request_success"];
    unset($_SESSION["request_success"]);
}

$sql = "SELECT role FROM users WHERE user_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if(!$user || $user["role"] != "student")
{
    exit("Only students can view this page.");
}

$sql = "SELECT session_requests.request_id,
               session_requests.message,
               session_requests.status,
               session_requests.requested_at,
               session_requests.responded_at,
               users.full_name
        FROM session_requests
        LEFT JOIN users
        ON session_requests.mentor_id = users.user_id
        WHERE session_requests.student_id = ?
        ORDER BY session_requests.requested_at DESC,
                 session_requests.request_id DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$request_count = mysqli_num_rows($result);

$remaining = 3 - $request_count;

if($remaining < 0)
{
    $remaining = 0;
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>My Session Requests</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<h1 align="center">My Session Requests</h1>

<hr>

<div style="width: 90%; max-width: 700px; margin: 30px auto;">

    <a href="student_dashboard.php">Back to Dashboard</a>

    <br><br>

<?php
if($success != "")
{
?>
    <p><?php echo htmlspecialchars($success); ?></p>
<?php
}
?>

    <p>
        <strong>Free requests remaining:</strong>
        <?php echo $remaining; ?>
    </p>

    <?php
    if($request_count >= 3)
    {
    ?>

        <p>
            You have used your 3 free session requests.
            Buy a subscription to send more.
        </p>

    <?php
    }

    if($request_count == 0)
    {
    ?>

        <p>You haven't sent any requests yet.</p>

        <p>
            To send a request, open Find a Mentor from your
            dashboard and click a mentor's name.
        </p>

    <?php
    }
    else
    {
        while($row = mysqli_fetch_assoc($result))
        {
    ?>

        <hr>

        <div>

 <h3>
    Mentor:
    <?php echo htmlspecialchars(
        $row["full_name"] ?? "Mentor unavailable"
    ); ?>
</h3>

<p>
    <strong>Your Message:</strong>
    <br>

    <?php echo htmlspecialchars($row["message"]); ?>
</p>

<p>
    <strong>Status:</strong>

    <?php echo htmlspecialchars(
        ucfirst($row["status"] ?? "pending")
    ); ?>
</p>

            <p>
<p>
    <strong>Requested At:</strong>

    <?php echo htmlspecialchars($row["requested_at"]); ?>
</p>

            <p>
                <strong>Responded At:</strong>

<?php
if($row["responded_at"] == null)
{
    echo "No response recorded yet.";
}
else
{
    echo htmlspecialchars($row["responded_at"]);
}
?>
            </p>

        </div>

    <?php
        }
    }

    mysqli_stmt_close($stmt);

    ?>

</div>

<hr>

<p align="center">
    Copyright &copy; <?php echo date("Y"); ?>
</p>

</body>

</html>