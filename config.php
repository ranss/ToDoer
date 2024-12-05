<?php 

// Include the Database class
require_once 'database.php';

// Database configuration
$host = "database_host";
$username = "database_password";
$password = "database_password";
$dbname = "database_name";

// Get the database instance
$db = Database::getInstance($host, $username, $password, $dbname);
$conn = $db->getConnection();