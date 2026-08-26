<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit();
}

include("db.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $mentorId = $_POST["user_id"];
    $action   = $_POST["action"];

    if ($action == "verify") {
        $newStatus = "verified";
    } elseif ($action == "reject") {
        $newStatus = "rejected";
    } else {
        $newStatus = "";
    }

    if ($newStatus != "") {
        $stmt = mysqli_prepare($conn,
            "UPDATE mentor_profiles SET verification_status = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $newStatus, $mentorId);
        mysqli_stmt_execute($stmt);
        $message = "Mentor status updated to " . $newStatus . ".";
    }
}

// Fetch every mentor
$sql = "SELECT u.user_id, u.full_name, u.email,
               m.university, m.department, m.graduation_year,
               m.current_organization, m.current_position,
               m.goal_achieved, m.verification_status
        FROM users u
        JOIN mentor_profiles m ON u.user_id = m.user_id
        ORDER BY (m.verification_status = 'pending') DESC, u.full_name";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify Mentors - PeerPath</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="navbar">
    <div class="nav-links">
        <a href="admin_dashboard.php">PeerPath Admin</a>
    </div>
    <div class="nav-user">
        Logged in as <?php echo htmlspecialchars($_SESSION["full_name"]); ?>
        &nbsp;|&nbsp;
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card">
        <h2>Verify Mentors</h2>

        <?php if ($message != ""): ?>
            <div class="alert-success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <table class="table-basic">
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>University</th>
                <th>Department</th>
                <th>Grad Year</th>
                <th>Organization</th>
                <th>Position</th>
                <th>Goal Achieved</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                    <td><?php echo htmlspecialchars($row["university"]); ?></td>
                    <td><?php echo htmlspecialchars($row["department"]); ?></td>
                    <td><?php echo htmlspecialchars($row["graduation_year"]); ?></td>
                    <td><?php echo htmlspecialchars($row["current_organization"]); ?></td>
                    <td><?php echo htmlspecialchars($row["current_position"]); ?></td>
                    <td><?php echo htmlspecialchars($row["goal_achieved"]); ?></td>
                    <td>
                        <span class="status-<?php echo htmlspecialchars($row["verification_status"]); ?>">
                            <?php echo htmlspecialchars(ucfirst($row["verification_status"])); ?>
                        </span>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?php echo $row["user_id"]; ?>">
                            <input type="submit" name="action" value="verify" class="btn">
                            <input type="submit" name="action" value="reject" class="btn btn-danger">
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>

        </table>
    </div>

</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
