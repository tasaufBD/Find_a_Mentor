<?php

session_start();

if(!isset($_SESSION["user_id"]))
{
    echo "<p>Please log in again to search for mentors.</p>";
    exit();
}

include("db.php");

$value = trim($_POST["value"] ?? "");

if($value == "")
{
    exit();
}

$sql = "SELECT users.user_id,
               users.full_name,
               mentor_profiles.skills,
               mentor_profiles.university,
               mentor_profiles.department
        FROM users
        INNER JOIN mentor_profiles
        ON users.user_id = mentor_profiles.user_id
        WHERE users.role = 'mentor'
        AND mentor_profiles.verification_status = 'verified'
        AND (
            users.full_name LIKE ?
            OR mentor_profiles.skills LIKE ?
            OR mentor_profiles.university LIKE ?
            OR mentor_profiles.department LIKE ?
        )
        ORDER BY users.full_name";

$stmt = mysqli_prepare($conn, $sql);

$search = "%" . $value . "%";

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $search,
    $search,
    $search,
    $search
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0)
{
?>

    <p>Click a mentor's name to request a session.</p>

<?php

    while($row = mysqli_fetch_assoc($result))
    {
?>

        <div>

            <h3>

                <a href="student_request_session.php?mentor_id=<?php
                    echo (int) $row["user_id"];
                ?>">
                    <?php echo htmlspecialchars(
                        $row["full_name"],
                        
                    ); ?>
                </a>
            </h3>

            <p>
                <strong>Skills:</strong>

                <?php echo htmlspecialchars(
                    $row["skills"] ?? "",
                   
                ); ?>
            </p>

            <p>
                <strong>University:</strong>

                <?php echo htmlspecialchars(
                    $row["university"] ?? "",
                   
                ); ?>
            </p>

            <p>
                <strong>Department:</strong>

                <?php echo htmlspecialchars(
                    $row["department"] ?? "",
                    
                ); ?>
            </p>

            <hr>

        </div>

<?php
    }
}
else
{
    echo "<p>No verified mentor found.</p>";
}

mysqli_stmt_close($stmt);

?>