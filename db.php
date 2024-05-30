<?php
$servername = "localhost";
$username = "twa334";
$password = "twa334Cv";
$dbname = "fitness334";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
