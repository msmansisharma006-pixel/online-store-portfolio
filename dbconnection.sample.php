<?php
// Read connection settings from environment variables if present (for Render/Cloud)
// Falls back to local XAMPP defaults if not set
$servername = getenv('DB_HOST') ?: "127.0.0.1";
$username   = getenv('DB_USER') ?: "root";
$password   = getenv('DB_PASS') ?: "";
$dbname     = getenv('DB_NAME') ?: "phpcrud";
$port       = getenv('DB_PORT') ? (int)getenv('DB_PORT') : 3306;

// Enable SSL flags for cloud MySQL connections if DB_SSL_CA or DB_PORT indicates cloud
$conn = mysqli_init();

if (getenv('DB_HOST')) {
    // Aiven requires SSL mode
    $conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
    $conn->real_connect($servername, $username, $password, $dbname, $port, NULL, MYSQLI_CLIENT_SSL);
} else {
    // Local development (XAMPP standard connection)
    $conn->real_connect($servername, $username, $password, $dbname, $port);
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>