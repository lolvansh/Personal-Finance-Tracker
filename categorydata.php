<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010";
$database = "FinanceTracker";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT category, SUM(amount) as total
    FROM expense
    GROUP BY category
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'category' => $row['category'],
        'total' => (float)$row['total']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
