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
    echo "You don't have permission to view files.";
    exit();
}

// Fetch files from the database
$sql = "SELECT * FROM files";
$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="z_css/styles.css">
    <title>View Files</title>
</head>

<body>
    <h2>Uploaded Files</h2>
    <table border="1">
        <tr>
            <th>File Name</th>
            <th>Download</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['file_name']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' download>Download</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='2'>No files uploaded yet.</td></tr>";
        }
        ?>
    </table>
</body>

</html>