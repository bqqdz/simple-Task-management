<?php
session_start();
include 'db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.html');
    exit();
}

// Check if the user is an admin
$user_id = $_SESSION['user_id'];
$sql = "SELECT role FROM users WHERE user_id = $user_id";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$user_role = $row['role'];

if ($user_role !== 'admin') {
    echo "<div class='container'>";
    echo "<p>You don't have permission to perform this action.</p>";
    echo "<a href='dashboard.php' class='button'>Back to Dashboard</a>";
    echo "</div>";
    exit();
}

// Fetch user data from the database
$sql = "SELECT u.user_id, u.username, u.role, COUNT(t.task_id) as task_count 
        FROM users u 
        LEFT JOIN tasks t ON u.user_id = t.user_id 
        GROUP BY u.user_id, u.username, u.role";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data</title>
    <link rel="stylesheet" href="z_css/styles.css">
</head>

<body>
    <div class="container">
        <h2>User Data:</h2>
        <?php
        if (mysqli_num_rows($result) > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Number of Tasks</th>
                    </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>" . $row['user_id'] . "</td>
                        <td>" . $row['username'] . "</td>
                        <td>" . $row['role'] . "</td>
                        <td>" . $row['task_count'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No user data found.</p>";
        }
        ?>
        <br>
        <a href="dashboard.php" class="button">Back to Dashboard</a>
    </div>
</body>

</html>