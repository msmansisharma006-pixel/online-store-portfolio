<?php
// dbconnection.php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "phpcrud"; // From phpMyAdmin 

// Create connection using the variable name your other files expect
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>