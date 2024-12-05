<?php
// Include the database connection
include('config.php');


// Add a new task if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];

    // Use prepared statements to insert the new task
    $stmt = $conn->prepare("INSERT INTO tasks (task) VALUES (?)");
    if ($stmt) {
        $stmt->bind_param("s", $task);
        $stmt->execute();
        $stmt->close();
    } else {
        die("Error preparing statement: " . $conn->error);
    }
}

// Retrieve all tasks from the database
$query = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $conn->query($query);

// Include the header
include('header.php');
?>

<!-- Content of the page -->
<h1 class="text-center">To-Do List</h1>

<!-- Form to add a new task -->
<form method="POST" action="" class="mb-4">
    <div class="input-group">
        <input type="text" class="form-control" name="task" placeholder="Enter a new task" required>
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Add Task</button>
        </div>
    </div>
</form>

<!-- Display the tasks -->
<ul class="list-group">
    <?php if($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $taskClass = $row['status'] == 'Completed' ? 'bg-success text-white' : 'bg-light text-dark'; ?>
            <li class="list-group-item d-flex justify-content-between align-items-center <?php echo $taskClass; ?>">
                <div>
                    <strong><?php echo htmlspecialchars($row['task']); ?></strong>
                    <br>
                    <small class="text-muted">
                        <?php if($row['created_at'] === $row['updated_at']) : ?>
                            Created: <?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?>
                        <?php else : ?>
                            Updated: <?php echo date('F j, Y, g:i a', strtotime($row['updated_at'])); ?>
                        <?php endif; ?>
                    </small>
                </div>
                <span>
                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                </span>
            </li>
        <?php endwhile; ?>
    <?php else : ?>
        <!-- Show message if no tasks exist -->
        <div class="alert alert-info text-center" role="alert">
            No tasks found. Add a new task to get started!
        </div>
    <?php endif; ?>

</ul>

<!-- Include the footer -->
<?php include('footer.php'); ?>

<?php
// Close the connection
$conn->close();
?>