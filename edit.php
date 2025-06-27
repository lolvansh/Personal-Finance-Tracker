<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010";
$database = "FinanceTracker";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? '';
$type = $_GET['type'] ?? ''; // 'Income' or 'Expense'
$table = ($type === 'Income') ? 'income' : 'expense';

// Handle update form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE $table SET amount = ?, category = ?, date = ? WHERE id = ?");
    $stmt->bind_param("dssi", $amount, $category, $date, $id);
    $stmt->execute();

    header("Location: transaction.php");
    exit;
}

// Fetch existing data
$result = $conn->query("SELECT * FROM $table WHERE id = $id");
$transaction = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaction</title>
</head>
<body>
    <h2>Edit <?= htmlspecialchars($type) ?> Transaction</h2>
    <form method="POST">
        <label>Amount:</label>
        <input type="number" name="amount" step="0.01" value="<?= $transaction['amount'] ?>" required><br><br>

        <label>Category:</label>
        <input type="text" name="category" value="<?= htmlspecialchars($transaction['category']) ?>" required><br><br>

        <label>Date:</label>
        <input type="datetime-local" name="date" value="<?= date('Y-m-d\TH:i', strtotime($transaction['date'])) ?>" required><br><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>


