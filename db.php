<?php
// Database connection details
$host = 'localhost'; // Your database host (usually localhost)
$user = 'root'; // Your database username
$password = ''; // Your database password (leave blank if using XAMPP default)
$dbname = 'votingsystem'; // Replace with your database name

// Create a connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


    