<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010";
$database = "FinanceTracker";

$conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    if(isset($_POST['login']))
?>