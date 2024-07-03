<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $status = mysqli_real_escape_string($con, $_POST['status']);

    // Check if the user is an admin or normal user
    $user_role = $_SESSION['role'];

    if ($user_role == 'admin') {
        // Admin can edit any task
        $sql = "UPDATE tasks SET title='$title', description='$description', status='$status' WHERE task_id=$task_id";
        if (mysqli_query($con, $sql)) {
            header('Location: ../dashboard.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } elseif ($user_role == 'normal') {
        // Normal user can only edit their own tasks
        $sql = "SELECT user_id FROM tasks WHERE task_id=$task_id";
        $result = mysqli_query($con, $sql);
        $task = mysqli_fetch_assoc($result);

        $task_user_id = $task['user_id'];
        $current_user_id = $_SESSION['user_id'];

        if ($task_user_id != $current_user_id) {
            echo "You do not have permission to edit this task.";
            exit();
        }

        $sql = "UPDATE tasks SET title='$title', description='$description', status='$status' WHERE task_id=$task_id";
        if (mysqli_query($con, $sql)) {
            header('Location: ../dashboard.php');
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    }
} else {
    $task_id = $_GET['id'];
    $sql = "SELECT * FROM tasks WHERE task_id=$task_id";
    $result = mysqli_query($con, $sql);
    $task = mysqli_fetch_assoc($result);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Task</title>
        <link rel="stylesheet" href="../z_css/styles.css">
    </head>

    <body>
        <div class="container">
            <h2>Edit Task</h2>
            <form action="edit_task.php" method="POST">
                <input type="hidden" name="task_id" value="<?php echo $task['task_id']; ?>">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $task['title']; ?>" required>
                <br>
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="10" style="width:100%; resize:both;" required><?php echo $task['description']; ?></textarea>
                <br>
                <label for="status">Status:</label>
                <select id="status" name="status">
                    <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                    <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                </select>
                <br>
                <button type="submit">Update Task</button>
            </form>
        </div>
    </body>

    </html>
<?php
}
?>