<?php
$servername = "localhost";
$username = "root";
$password = "";   // default XAMPP has no password
$dbname   = "library_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
