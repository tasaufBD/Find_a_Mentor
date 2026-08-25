<?php

$conn = mysqli_connect("localhost", "root", "", "mentor");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>
