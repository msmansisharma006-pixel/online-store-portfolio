<?php
$servername = getenv('DB_HOST') ?: "127.0.0.1";
$username   = getenv('DB_USER') ?: "root";
$password   = getenv('DB_PASS') ?: "";
$dbname     = getenv('DB_NAME') ?: "phpcrud";
$port       = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

$conn = mysqli_init();

if (getenv('DB_HOST')) {
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    $conn->real_connect($servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);
} else {
    $conn->real_connect($servername, $username, $password, $dbname, $port);
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>