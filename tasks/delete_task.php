<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.html');
    exit();
}

// Check if the task ID is set
if (isset($_GET['id'])) {
    $task_id = $_GET['id'];

    // Fetch the task details from the database
    $sql = "SELECT * FROM tasks WHERE task_id = $task_id";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $task = mysqli_fetch_assoc($result);

        // Check if the user has permission to delete the task
        if ($_SESSION['role'] == 'admin' || $task['user_id'] == $_SESSION['user_id']) {
            // Delete the task from the database
            $sql = "DELETE FROM tasks WHERE task_id=$task_id";
            if (mysqli_query($con, $sql)) {
                echo "Task deleted successfully.";
            } else {
                echo "Error deleting task: " . mysqli_error($con);
            }
        } else {
            echo "You do not have permission to delete this task.";
        }
    } else {
        echo "Task not found in the database.";
    }
} else {
    echo "Invalid request.";
}

// Redirect back to the dashboard page
header('Location: ../dashboard.php');
exit();
