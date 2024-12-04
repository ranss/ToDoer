<?php

// Include the database connection
include('config.php');

// Initialize variables for form feedback
$error_msg      = "";
$success_msg    = "";

// Check if the id is passed via the URL
if (isset($_GET['id'])) {
    
    $id = $_GET['id'];

    // Retrieve the task from the database
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $task = $result->fetch_assoc();
    } else {
        // If no task found, redirect with an error message
        header("Location: index.php?error=Task not found");
        exit();
    }
    $stmt->close();

    // If the form is submitted, update the task
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
        $new_task = trim($_POST['task']); // Trim any extra spaces

        // Simple validation to ensure the task is not empty
        if (empty($new_task)) {
            $error_msg = "Task cannot be empty!";
        } else {
            // Update the task in the database
            $stmt = $conn->prepare("UPDATE tasks SET task = ?, priority = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->bind_param("sssi", $new_task, $priority, $status, $id);
            if ($stmt->execute()) {
                $success_msg = "Task updated successfully!";
            } else {
                $error_msg = "Error updating the task. Please try again.";
            }
            $stmt->close();
        }

        // Redirect to the main page if successful
        if ($success_msg) {
            header("Location: index.php");
            exit();
        }
    }
} else {
    // If no ID is passed, redirect to the main page with an error
    header("Location: index.php?error=No task ID provided");
    exit();
}

// Include the header
include('header.php');
?>

<!-- Content of the page -->
<h1 class="text-center">Edit Task</h1>

<!-- Display feedback messages -->
<?php if ($error_msg): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($error_msg); ?>
    </div>
<?php elseif ($success_msg): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($success_msg); ?>
    </div>
<?php endif; ?>

<!-- Form to edit an existing task -->
<form method="POST" action="">
    <div class="input-group mb-3">
        <input type="text" class="form-control" name="task" value="<?php echo htmlspecialchars($task['task']); ?>" required>
        <div class="input-group-append">
            <button type="submit" class="btn btn-success">Update Task</button>
        </div>
    </div>
    <!-- Priority selection -->
    <div class="form-group">
        <label for="priority">Priority</label>
        <select class="form-control" id="priority" name="priority">
            <option value="Low" <?php echo ($task['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
            <option value="Medium" <?php echo ($task['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
            <option value="High" <?php echo ($task['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
        </select>
    </div>

    <!-- Status selection -->
    <div class="form-group">
        <label for="status">Status</label>
        <select class="form-control" id="status" name="status">
            <option value="Pending" <?php echo ($task['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Completed" <?php echo ($task['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
        </select>
    </div>
</form>


<!-- Display the updated_at timestamp -->
<div class="text-muted mt-4">
    <p><strong>Task Created At:</strong> <?php echo date('F j, Y, g:i a', strtotime($task['created_at'])); ?></p>
    <p><strong>Last Updated At:</strong> <?php echo date('F j, Y, g:i a', strtotime($task['updated_at'])); ?></p>
</div>

<!-- Back to the To-Do list -->
<br>
<a href="index.php" class="btn btn-secondary">Back to To-Do List</a>

<!-- Include the footer -->
<?php include('footer.php'); ?>

<?php $conn->close(); ?>