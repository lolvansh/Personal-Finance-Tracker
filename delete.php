<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010"; // Change if needed
$database = "FinanceTracker";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate input
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$type = $_GET['type'] ?? '';
$table = ($type === 'Income') ? 'income' : 'expense';


$stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: transaction.php");
    exit;
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
