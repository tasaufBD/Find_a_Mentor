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

?>
 
<!DOCTYPE html>
 
<html>
 
<head>
 
<title>Profile</title>
<link rel="stylesheet" href="style.css">
 
</head>
 
<body>
 
<h1 align="center">
 
Profile
 
</h1>
 
<hr>
 
<table class="table-basic">
 
<tr>
 
<td>User ID</td>
 
<td>  <?php echo $row["user_id"]; ?>   </td>
 
</tr>
 
<tr>
 
<td>Uiversity</td>
 
<td>
 
<?php echo $row["university"]; ?>
 
</td>
 
</tr>
 
<tr>
 
<td>Department</td>
 
<td>
 
<?php echo $row["department"]; ?>
 
</td>
 
</tr>
 
<tr>
 
<td>Academic Year</td>
 
<td>
 
<?php echo $row["academic_year"]; ?>
 
</td>
 
</tr>
<tr>
 
<td>Cgpa Range</td>
 
<td>
 
<?php echo $row["cgpa_range"]; ?>
 
</td>
 
</tr>
<tr>
 
<td>Skills</td>
 
<td>
 
<?php echo $row["skills"]; ?>
 
</td>
 
</tr>
<tr>
 
<td>Interests</td>
 
<td>
 
<?php echo $row["interests"]; ?>
 
</td>
 
</tr>
<tr>
 
<td>Career Goal</td>
 
<td>
 
<?php echo $row["career_goal"]; ?>
 
</td>
 
</tr>
<tr>
 
<td>Target Detail</td>
 
<td>
 
<?php echo $row["target_detail"]; ?>
 
</td>
 
</tr>
<tr>
 
<td>Experience</td>
 
<td>
 
<?php echo $row["experience"]; ?>
 
</td>
 
</tr>
 
</table>
 
<br>
 
<center>
 
<a href="student_dashboard.php" class="btn btn-secondary">Back</a>
 
</a>
 
</center>
 
<hr>
 
<p align="center">
 
Copyright &copy;
<?php echo date("Y");?>
 
</p>
 
</body>
 
</html>
 