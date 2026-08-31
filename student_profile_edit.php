<?php

session_start();

if(!isset($_SESSION["user_id"]))
{
    header("Location: login.php");
}

include("db.php");

$id=$_SESSION["user_id"];

$sql="SELECT * FROM student_profiles WHERE user_id='$id'";

$result=mysqli_query($conn,$sql);

$row=mysqli_fetch_assoc($result);

$university=$row["university"];
$department=$row["department"];
$academic_year=$row["academic_year"];
$cgpa_range=$row["cgpa_range"];
$skill=$row["skills"];
$interest=$row["interests"];
$career_goal=$row["career_goal"];
$target_detail=$row["target_detail"];
$experience=$row["experience"];

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $university=$_POST["university"];
    $department=$_POST["department"];
    $academic_year=$_POST["academic_year"];
    $cgpa_range=$_POST["cgpa_range"];
    $skill=$_POST["skills"];
    $interest=$_POST["interests"];
    $career_goal=$_POST["career_goal"];
    $target_detail=$_POST["target_detail"];
    $experience=$_POST["experience"];

    $sql="UPDATE student_profiles
    SET university='$university',
        department='$department',
        academic_year='$academic_year',
        cgpa_range='$cgpa_range',
        skills='$skill',
        interests='$interest',
        career_goal='$career_goal',
        target_detail='$target_detail',
        experience='$experience'
    WHERE user_id='$id'";

    if(mysqli_query($conn,$sql))
    {
        $message="Profile Updated Successfully";
    }
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Profile</title>
<link rel="stylesheet" href="style.css">

</head>

<body>

<h1 align="center">

Edit Profile

</h1>

<hr>

<form method="post">

University

<br>

<input
type="text"
name="university"
value="<?php echo $university;?>">

<br><br>

Department

<br>

<input
type="text"
name="department"
value="<?php echo $department;?>">

<br><br>

Academic Year

<br>

<input
type="number"
name="academic_year"
value="<?php echo $academic_year;?>">

<br><br>

Cgpa Range

<select name="cgpa_range">
    <option value="<2.5">&lt;2.5</option>
    <option value="2.5-3.0">2.5-3.0</option>
    <option value="3.0-3.5">3.0-3.5</option>
    <option value="3.5-4.0">3.5-4.0</option>
</select>

<br><br>

Skills

<br>

<input
type="text"
name="skills"
value="<?php echo $skill;?>">

<br><br>

Interests

<br>

<input
type="text"
name="interests"
value="<?php echo $interest;?>">

<br><br>

Career Goal

<br>

<select name="career_goal">
    <option value="FAANG">FAANG</option>
    <option value="MS_Abroad">MS_Abroad</option>
    <option value="PhD_Abroad">PhD_Abroad</option>
    <option value="PhD_Local">PhD_Local</option>
	<option value="Research">Research</option>
    <option value="Startup">Startup</option>
    <option value="Government">Government</option>
    <option value="Other">Other</option>
</select>


<br><br>

Target Detail

<br>

<input
type="text"
name="target_detail"
value="<?php echo $target_detail;?>">

<br><br>

Experience
<br>

<input
type="text"
name="experience"
value="<?php echo $experience;?>">

<br><br>

<input
type="submit"
value="Update">

<a href="student_dashboard.php">
    <input type="button" value="Back">
</a>
<br><br>

<span style="color:green;">

<?php echo $message;?>

</span>

</form>

<hr>

<p align="center">

Copyright &copy;

<?php echo date("Y");?>

</p>

</body>

</html>