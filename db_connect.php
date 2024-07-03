<?php
$HOSTNAME = "localhost";
$USERNAME = "root";
$PASSWORD = "new_password";
$DATABASE = "task_management_system";

$con = mysqli_connect($HOSTNAME, $USERNAME, $PASSWORD, $DATABASE);
if ($con) {
    echo "";
} else {
    die(mysqli_error($con));
}
