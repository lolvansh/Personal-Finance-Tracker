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
SELECT 
    DATE_FORMAT(date, '%b') AS month, 
    SUM(CASE WHEN type = 'Income' THEN amount ELSE 0 END) AS income,
    SUM(CASE WHEN type = 'Expense' THEN amount ELSE 0 END) AS expense
FROM (
    SELECT date, amount, 'Income' as type FROM income
    UNION ALL
    SELECT date, amount, 'Expense' as type FROM expense
) AS combined
WHERE date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
GROUP BY DATE_FORMAT(date, '%Y-%m')
ORDER BY DATE_FORMAT(date, '%Y-%m')
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = [
        'month' => $row['month'],
        'income' => (float)$row['income'],
        'expense' => (float)$row['expense']
    ];
}

echo json_encode($data);

?>
