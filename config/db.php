<?php
$host = "localhost";
$username = "root";
$password = ""; 
$database = "prescription_system";
$port = 3306;

// Create connection
$conn = new mysqli($host, $username, $password, $database, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
