<?php
session_start();

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] == "student") {
        header("Location: student_dashboard.php");
    } elseif ($_SESSION["role"] == "mentor") {
        header("Location: mentor_dashboard.php");
    } else {
        header("Location: admin_dashboard.php");
    }
    exit();
}

include("db.php");

// ---- Live platform stats
$totalMentors = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM mentor_profiles WHERE verification_status = 'verified'"))["c"];

$totalStudents = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM users WHERE role = 'student'"))["c"];

$totalSessions = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS c FROM session_requests"))["c"];

// ---- A few featured, verified mentors ----
$featuredResult = mysqli_query($conn,
    "SELECT u.full_name, m.current_position, m.current_organization, m.goal_achieved
     FROM users u
     JOIN mentor_profiles m ON u.user_id = m.user_id
     WHERE m.verification_status = 'verified'
     ORDER BY RAND()
     LIMIT 3");
?>

<!DOCTYPE html>
<html>
<head>
    <title>PeerPath - Find Someone Who Was You</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="guest-navbar">
    <div class="brand">PeerPath</div>
    <div class="guest-links">
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </div>
</div>

<!-- Hero -->
<div class="hero">
    <h1>Find Someone Who Was You — And Made It.</h1>
    <p>PeerPath connects students with mentors who started from a similar place<br>
       and already reached the goal you're working toward.</p>
    <a href="register.php" class="btn">Get Started</a>
    <a href="login.php" class="btn btn-secondary">Login</a>
</div>

<!-- Problem statement -->
<div class="section">
    <h2>Career Advice Shouldn't Be Generic</h2>
    <p>
        Most career guidance online is written for everyone, which means it fits almost no one.
        A student with a low CGPA aiming for a scholarship abroad needs a very different roadmap
        than a top-ranked student aiming for the same goal. PeerPath doesn't show you generic
        advice — it connects you to a mentor who started from a background similar to yours and
        already reached your goal.
    </p>
</div>

<!-- How it works -->
<div class="section">
    <h2>How It Works</h2>
    <div class="row">
        <div class="col-4">
            <div class="card">
                <h3>1. Create Your Profile</h3>
                <p>Tell us your university, academic background, skills, and the goal you're working toward.</p>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h3>2. Discover Mentors</h3>
                <p>Search and filter verified mentors by university, field, goal, and skills.</p>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h3>3. Request a Session</h3>
                <p>Message a mentor directly about what you want help. They accept or decline.</p>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <h3>4. Move Toward Your Goal</h3>
                <p>Get guidance from someone who has actually walked the path you're on.</p>
            </div>
        </div>
    </div>
</div>

<!-- Features -->
<div class="section">
    <h2>What PeerPath Offers</h2>
    <div class="row">
        <div class="col-3">
            <div class="card">
                <h3>Goal-Based Matching</h3>
                <p>Get connected with mentors who share your academic background and career goal.</p>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <h3>Verified Mentors</h3>
                <p>Every mentor profile is manually reviewed and verified by an admin before it goes live.</p>
            </div>
        </div>
        <div class="col-3">
            <div class="card">
                <h3>Focused Sessions</h3>
                <p>No noise, no infinite feeds — just direct requests and honest conversations.</p>
            </div>
        </div>
    </div>
</div>

<!-- Live stats -->
<div class="section">
    <h2>PeerPath in Numbers</h2>
    <div class="row">
        <div class="col-4">
            <p class="stat-number"><?php echo $totalMentors; ?></p>
            <p class="stat-label">Verified Mentors</p>
        </div>
        <div class="col-4">
            <p class="stat-number"><?php echo $totalStudents; ?></p>
            <p class="stat-label">Students</p>
        </div>
        <div class="col-4">
            <p class="stat-number"><?php echo $totalSessions; ?></p>
            <p class="stat-label">Session Requests</p>
        </div>
    </div>
</div>

<!-- Featured mentors -->
<?php if (mysqli_num_rows($featuredResult) > 0): ?>
<div class="section">
    <h2>Meet a Few of Our Mentors</h2>
    <div class="row">
        <?php while ($mentor = mysqli_fetch_assoc($featuredResult)): ?>
            <div class="col-3">
                <div class="card">
                    <h3><?php echo htmlspecialchars($mentor["full_name"]); ?></h3>
                    <p><?php echo htmlspecialchars($mentor["current_position"]); ?> at
                       <?php echo htmlspecialchars($mentor["current_organization"]); ?></p>
                    <p><span class="status-verified">Goal Achieved:</span>
                       <?php echo htmlspecialchars($mentor["goal_achieved"]); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <p style="text-align: center;">Register to view full profiles and request a session.</p>
</div>
<?php endif; ?>

<!-- Call to action -->
<div class="cta-banner">
    <h2>Ready to find your mentor?</h2>
    <a href="register.php" class="btn">Create Your Free Account</a>
</div>

<p class="site-footer">Copyright &copy; <?php echo date("Y"); ?> PeerPath</p>

</body>
</html>
