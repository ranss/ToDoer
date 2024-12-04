<?php
// Database connection details
$host = "localhost";
$username = "root"; // Your MySQL username
$password = "P@ssword!88"; // Your MySQL password
$dbname = "todo_list"; // The database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}