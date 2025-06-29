<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$host = "localhost";
$username = "root";
$password = "Cyanide@1010"; // replace with yours
$database = "FinanceTracker"; // your database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$incomeResult = $conn -> query("select SUM(amount) AS total_income from income WHERE user_id = $user_id");
$totalIncome = $incomeResult ->fetch_assoc()['total_income'] ?? 0;


$expenseResult = $conn ->query("select SUM(amount) as total_expense from expense WHERE user_id = $user_id");
$totalExpense = $expenseResult ->fetch_assoc()['total_expense'] ?? 0;

$balance = $totalIncome - $totalExpense;

$transaction = $conn ->query(
    "
    SELECT category, amount, date, 'Expense' AS type FROM expense WHERE user_id = $user_id
    UNION
    SELECT category, amount, date, 'Income' AS type FROM income WHERE user_id = $user_id
    ORDER BY date DESC
    LIMIT 5
"
);

$monthlyincomeResult = $conn -> query("select SUM(amount) AS total_income from income WHERE user_id = $user_id AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())");
$monthlytotalIncome = $monthlyincomeResult ->fetch_assoc()['total_income'] ?? 0;


$monthlyexpenseResult = $conn ->query("select SUM(amount) as total_expense from expense WHERE user_id = $user_id AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())");
$monthlytotalExpense = $monthlyexpenseResult ->fetch_assoc()['total_expense'] ?? 0;

$monthlybalance = $monthlytotalIncome - $monthlytotalExpense;


$sql = "
    SELECT 
        SUM(amount) / DAY(CURDATE()) AS average_daily_expense
    FROM expense
    WHERE 
        user_id = $user_id
        AND MONTH(date) = MONTH(CURDATE())
        AND YEAR(date) = YEAR(CURDATE())
";

$result = $conn->query($sql);
$average = $result->fetch_assoc()['average_daily_expense'] ?? 0;


$transactionsql = "
    SELECT COUNT(*) AS total_transactions
    FROM (
        SELECT id, date FROM income
        WHERE user_id = $user_id
        AND MONTH(date) = MONTH(CURDATE())
        AND YEAR(date) = YEAR(CURDATE())
        
        UNION ALL
        
        SELECT id, date FROM expense
        WHERE user_id = $user_id
        AND MONTH(date) = MONTH(CURDATE())
        AND YEAR(date) = YEAR(CURDATE())
    ) AS all_transactions
";

$transactionresult = $conn->query($transactionsql);
$totaltransaction = $transactionresult->fetch_assoc()['total_transactions'] ?? 0;




$monthnameresult = $conn ->query("SELECT DATE_FORMAT(date, '%M') AS month_name FROM expense
where month(date) = month(curdate()) limit 1;");
$monthname = $monthnameresult->fetch_assoc()['month_name'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance-tracker</title>
    <link rel="stylesheet" href="style.css?v=2">
</head>
<body>
    <div class="top-section">
        <h1>Finance Tracker</h1>
    </div>


    <div class="middle-section">
        <div class="nav-bar">
            <nav class="navbar">
                <ul class="navbar-list">
                    <li class="items">
                        <img src="./assests/nav/dashboard.svg" alt="dashboard.svg" class="nav-image" width="40px">
                        <a href="index.php" class="active">Overview</a></li>
                    <li class="items">
                        <img src="./assests/nav/stats.svg" alt="transaction.svg" class="nav-image" width="40px">
                        <a href="stats.php">Statistics</a></li>
                    <li class="items">
                        <img src="./assests/nav/transaction.svg" alt="stats.svg" class="nav-image" width="40px">
                        <a href="transaction.php">Transactions</a></li>
                    <li class="items">
                        <img src="./assests/nav/report.svg" alt="report.svg" class="nav-image" width="40px">
                        <a href="budget.php">Budget</a></li>
                    <li class="items">
                        <img src="./assests/log-out.svg" alt="logout.svg" class="nav-image" width="40px">
                        <a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- dashboard -->
        <div class="page-overview">
            <!-- middle part -->
            <p class="page-name">Dashboard</p>
            <div class="page-content">
                <div class="overview-section">
                    <div class="transfers">
                        <div class="income">
                            <img src="./assests/income.svg" alt="income.svg" width="100px" class="image">
                            <div class="income-desc">
                                <p class="income-text">Total Income</p>
                                <p class="income-value">₹<?= number_format($totalIncome,2) ?></p>
                            </div>
                        </div>
                        <div class="expense">
                            <img src="./assests/expense.svg" alt="expense.svg" width="100px" class="image">
                            <div class="expense-desc">
                                <p class="income-text">Total Expense</p>
                                <p class="expense-value">₹<?= number_format($totalExpense,2) ?></p>
                            </div>
                        </div>
                        <div class="budget">
                            <img src="./assests/budget.svg" alt="budget.svg" width="100px" class="image">
                            <div class="budget-desc">
                                <p class="income-text">Total Budget</p>
                                <p class="budget-value">₹<?= number_format($balance,2)?></p>
                            </div>
                        </div>
                    </div>
                    <div class="recent-transactions">
                        <div class="recent-top">
                            <p class="recent-text">Recent transactions</p>
                            <div class="audits">
                                <?php while($row = $transaction->fetch_assoc()): ?>
                                <div class="transaction">
                                    <img src="./assests/transactions/<?= $row['type'] ==='Expense' ? 'expense.svg' : 'income.svg' ?>  " width="50px" class="icon">
                                    <p class="usage"> <?= htmlspecialchars($row['category']) ?> </p>
                                    <p class="Time"> <?= date("d M y, H:i", strtotime($row['date'])) ?> </p>
                                    <p class="amount">
                                        <?= $row['type'] === 'Expense' ? '-' : '+' ?>
                                        ₹<?= number_format($row['amount'],2) ?>
                                    </p>
                                    <img src="./assests/transactions/dots.svg" width="50px" class="menu-icon">
                                </div>
                                
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- left-part -->
                <div class="quick-buttons">
                    <p class="quick-text">Quick Buttons</p>
                    <div class="add">
                        <a href="add_expense.html" class="add-expense">
                            <img src="./assests/add-expense.svg" width="50px" class="img-add">
                            <button class="income-btn">Add Expense</button>
                        </a>
                    </div>
                    <div class="add">
                        <a href="add_income.html" class="add-expense">
                            <img src="./assests/add-income.svg" width="50px" class="img-add">
                            <button class="expense-btn">Add Income</button>
                        </a>
                    </div>
                    <div class="summary">
                        <div class="summary-heading">Summary</div>
                        
                        <div class="summary-descri">
                            <div class="this-month"><?= htmlspecialchars($monthname)?></div>
                            <div class="desc-container">
                                <div class="summary-balance">Balance: ₹<?= number_format($monthlybalance,0)?></div>
                                <div class="summary-average">Average(spent): ₹<?= number_format($average,0)?> </div>
                                <div class="summary-transaction">Transactions: <?= number_format($totaltransaction,0)?> </div>
                            </div>
                        </div>
                    </div>
                    <div class="cat-image">
                        <img src="./assests/cat.svg" width="150px">
                    </div>
                </div>
                </div>
        </div>
    </div>
</body>
</html>