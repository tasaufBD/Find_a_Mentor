<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "mentor") {
    header("Location: login.php");
    exit();
}

include("db.php");

$mentorId = $_SESSION["user_id"];
$message  = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $requestId = $_POST["request_id"];
    $action    = $_POST["action"];

    if ($action == "accept") {
        $newStatus = "accepted";
    } elseif ($action == "reject") {
        $newStatus = "rejected";
    } else {
        $newStatus = "";
    }

    if ($newStatus != "") {
        $stmt = mysqli_prepare($conn,
            "UPDATE session_requests
             SET status = ?, responded_at = NOW()
             WHERE request_id = ? AND mentor_id = ?");
        mysqli_stmt_bind_param($stmt, "sii", $newStatus, $requestId, $mentorId);
        mysqli_stmt_execute($stmt);
        $message = "Request " . $newStatus . ".";
    }
}

$stmt = mysqli_prepare($conn,
    "SELECT sr.request_id, sr.message, sr.status, sr.requested_at,
            u.full_name, u.email,
            sp.university, sp.career_goal
     FROM session_requests sr
     JOIN users u ON sr.student_id = u.user_id
     JOIN student_profiles sp ON sr.student_id = sp.user_id
     WHERE sr.mentor_id = ?
     ORDER BY (sr.status = 'pending') DESC, sr.requested_at DESC");
mysqli_stmt_bind_param($stmt, "i", $mentorId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Session Requests - PeerPath</title>
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
        <h2>Session Requests</h2>

        <?php if ($message != ""): ?>
            <div class="alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <?php if (mysqli_num_rows($result) == 0): ?>

            <p>You haven't received any session requests yet.</p>

        <?php else: ?>

            <table class="table-basic">
                <tr>
                    <th>Student</th>
                    <th>University</th>
                    <th>Goal</th>
                    <th>Message</th>
                    <th>Requested</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($row["full_name"]); ?><br>
                            <small><?php echo htmlspecialchars($row["email"]); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row["university"]); ?></td>
                        <td><?php echo htmlspecialchars($row["career_goal"]); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row["message"])); ?></td>
                        <td><?php echo htmlspecialchars($row["requested_at"]); ?></td>
                        <td>
                            <span class="status-<?php echo htmlspecialchars($row["status"]); ?>">
                                <?php echo htmlspecialchars(ucfirst($row["status"])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row["status"] == "pending"): ?>
                                <form method="post">
                                    <input type="hidden" name="request_id" value="<?php echo $row["request_id"]; ?>">
                                    <input type="submit" name="action" value="accept" class="btn">
                                    <input type="submit" name="action" value="reject" class="btn btn-danger">
                                </form>
                            <?php else: ?>
                                &mdash;
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

            </table>

        <?php endif; ?>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
