<?php
// Include the database connection
include('config.php');

// Check if the id is passed via the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the task from the database
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the main page
    header("Location: index.php");
    exit();
}

// Close the connection
$conn->close();
?>