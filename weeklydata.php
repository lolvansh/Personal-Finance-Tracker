<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010";
$database = "FinanceTracker";

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Example query: Get SUM per day for past 7 days
$sql = "
SELECT 
  DATE_FORMAT(date, '%a') AS day_name,
  SUM(amount) AS total
FROM expense
WHERE date >= CURDATE() - INTERVAL 6 DAY
GROUP BY day_name
ORDER BY FIELD(day_name, 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
";

$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = [
        'day' => $row['day_name'],
        'total' => (float)$row['total']
    ];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
