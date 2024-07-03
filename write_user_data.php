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
    echo "You don't have permission to perform this action.";
    exit();
}

// Fetch user data from the database
$sql = "SELECT * FROM users";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result) > 0) {
    $file = fopen('user_data.txt', 'w');

    while ($row = mysqli_fetch_assoc($result)) {
        $user_data = "ID: " . $row['user_id'] . ", Username: " . $row['username'] . ", Email: " . $row['email'] . ", Role: " . $row['role'] . "\n";
        fwrite($file, $user_data);
    }

    fclose($file);
    echo "User data written to file successfully.";
    echo "<br><a href='dashboard.php'>Back to Dashboard</a>";
} else {
    echo "No user data found.";
}
