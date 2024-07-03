<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.html');
    exit();
}

// Fetch tasks based on user role
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'admin') {
    $sql = "SELECT * FROM tasks";
} else {
    $sql = "SELECT * FROM tasks WHERE user_id=$user_id";
}
$result = mysqli_query($con, $sql);

// Display tasks
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management System - Dashboard</title>
    <link rel="stylesheet" href="z_css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Task Management System - Dashboard</h2>
        <h3>Welcome, <?php echo $_SESSION['username']; ?>!</h3>

        <h3>Your Tasks:</h3>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?php echo $row['title']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['status']; ?></td>
                    <td>
                        <a href="tasks/edit_task.php?id=<?php echo $row['task_id']; ?>">Edit</a> |
                        <a href="tasks/delete_task.php?id=<?php echo $row['task_id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <a href="tasks/add_task.php">Add New Task</a>
        <br>
        <br>

        <?php if ($role == 'admin') : ?>
            <h3>Admin Functions:</h3>
            <a href="upload_file.php">Upload a File</a> |
            <a href="view_files.php">View Uploaded Files</a> |
            <a href="write_user_data.php">Write User Data to File</a> |
            <a href="view_user_data.php">View User Data</a>
            <br><br>
        <?php endif; ?>

        <a href="auth/logout.php">Logout</a>
    </div>
</body>

</html>