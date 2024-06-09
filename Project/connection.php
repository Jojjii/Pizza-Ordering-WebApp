<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "pizza peace";
$conn = mysqli_connect($servername, $username, $password, $db);
// Check connection
if (!$conn) {
die("Connection failed: " . mysqli_connect_error());
}
//echo "Connected Successfully";
?>