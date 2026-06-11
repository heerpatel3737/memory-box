<?php
$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    $env_array = parse_ini_file($env_file);
    if ($env_array) {
        foreach ($env_array as $key => $value) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$dbname = getenv('DB_NAME') ?: "memoryboxDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 for emoji support (moods, titles, descriptions)
$conn->set_charset("utf8mb4");
?>
