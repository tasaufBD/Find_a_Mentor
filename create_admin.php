<?php
include("db.php");

$fullName = "Super Admin";
$email = "superadmin@peerpath.com";
$password = "admin123"; 
$role = "admin";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt, "ssss", $fullName, $email, $hashedPassword, $role);

if (mysqli_stmt_execute($stmt)) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>