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
    echo "You don't have permission to upload files.";
    exit();
}

// Check if the form was submitted and the file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // File properties
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // File extension
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    // Allowed file extensions and maximum file size (in bytes)
    $allowed_ext = array('jpg', 'jpeg', 'png', 'pdf', 'txt', 'docx'); // Added txt and docx
    $max_file_size = 5242880; // 5MB

    // Check if the file extension is allowed
    if (in_array($file_ext, $allowed_ext)) {
        // Check if the file size is within the limit
        if ($file_size <= $max_file_size) {
            // Generate a unique file name
            $unique_file_name = uniqid('', true) . '.' . $file_ext;

            // Move the uploaded file to the uploads directory
            $file_destination = 'uploads/' . $unique_file_name;
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Insert file details into the database
                $sql = "INSERT INTO files (user_id, file_name, file_path) VALUES ('$user_id', '$file_name', '$file_destination')";
                if (mysqli_query($con, $sql)) {
                    echo "<div class='container'>";
                    echo "<p>File uploaded successfully.</p>";
                    echo "<a href='dashboard.php' class='button'>Back to Dashboard</a>";
                    echo "</div>";
                } else {
                    echo "<div class='container'>";
                    echo "<p>Error uploading file: " . mysqli_error($con) . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<div class='container'>";
                echo "<p>Error uploading file.</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='container'>";
            echo "<p>File size exceeds the maximum limit.</p>";
            echo "</div>";
        }
    } else {
        echo "<div class='container'>";
        echo "<p>Invalid file type.</p>";
        echo "</div>";
    }
} else {
    echo "<div class='container'>";
    echo "<p>No file uploaded.</p>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Form</title>
    <link rel="stylesheet" href="z_css/styles.css">
</head>

<body>
    <div class="container">
        <h2>Upload a File</h2>
        <form action="upload_file.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>

</html>