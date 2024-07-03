<?php
session_start();
include 'db_connect.php';  // Adjust the path if necessary

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.html');
    exit();
}

// Check if the file ID is set
if (isset($_GET['id'])) {
    $file_id = $_GET['id'];

    // Fetch the file details from the database
    $sql = "SELECT * FROM files WHERE file_id = $file_id";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        $file = mysqli_fetch_assoc($result);
        $file_path = $file['file_path'];

        // Check if the user has permission to delete the file
        if ($_SESSION['role'] == 'admin' || $file['user_id'] == $_SESSION['user_id']) {
            // Check if the file exists before deleting
            if (file_exists($file_path)) {
                // Delete the file from the server
                if (unlink($file_path)) {
                    // Delete the file record from the database
                    $sql = "DELETE FROM files WHERE file_id = $file_id";
                    if (mysqli_query($con, $sql)) {
                        echo "File record deleted successfully.";
                    } else {
                        echo "Error deleting file record: " . mysqli_error($con);
                    }
                } else {
                    echo "Error deleting file from the server.";
                }
            } else {
                echo "File does not exist on the server.";
            }
        } else {
            echo "You do not have permission to delete this file.";
        }
    } else {
        echo "File not found in the database.";
    }
} else {
    echo "Invalid request.";
}

// Redirect back to the view files page
header('Location: view_files.php');
exit();
