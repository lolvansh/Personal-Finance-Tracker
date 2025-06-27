<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

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
    month,
    SUM(CASE WHEN type = 'Income' THEN amount ELSE 0 END) AS income,
    SUM(CASE WHEN type = 'Expense' THEN amount ELSE 0 END) AS expense
FROM (
    SELECT DATE_FORMAT(date, '%Y-%m') AS month, amount, 'Income' AS type, user_id FROM income
    UNION ALL
    SELECT DATE_FORMAT(date, '%Y-%m') AS month, amount, 'Expense' AS type, user_id FROM expense
) AS combined
WHERE 
    month >= DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), '%Y-%m')
    AND user_id = $user_id
GROUP BY month
ORDER BY month;
";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {

    $timestamp = strtotime($row['month'].'-01');
    $formattedmonth = date('M',$timestamp);
    $data[] = [
        'month' => $formattedmonth,
        'income' => (float)$row['income'],
        'expense' => (float)$row['expense']
    ];
}

echo json_encode($data);

?>
