<?php 

// Include the Database class
require_once 'database.php';

// Database configuration
$host = "localhost";
$username = "root";
$password = "P@ssword!88";
$dbname = "todo_list";

// Get the database instance
$db = Database::getInstance($host, $username, $password, $dbname);
$conn = $db->getConnection();