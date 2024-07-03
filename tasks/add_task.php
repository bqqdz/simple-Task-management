<?php
session_start();
include '../db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO tasks (title, description, status, user_id) VALUES ('$title', '$description', '$status', $user_id)";
    if (mysqli_query($con, $sql)) {
        header('Location: ../dashboard');
        exit();
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>
    <link rel="stylesheet" href="../z_css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Add New Task</h2>
        <form action="add_task.php" method="POST">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
            <br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="10" style="width:100%; resize:both;" required></textarea>
            <br>
            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
            </select>
            <br>
            <button type="submit">Add Task</button>
        </form>
    </div>
</body>

</html>