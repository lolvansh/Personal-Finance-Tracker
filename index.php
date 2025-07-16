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
    ORDER BY date 
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

$weeklyBudgets = $conn->query("SELECT * FROM budgets WHERE user_id = $user_id AND budget_type = 'weekly'");
$monthlyBudgets = $conn->query("SELECT * FROM budgets WHERE user_id = $user_id AND budget_type = 'monthly'");


$monthnameresult = $conn ->query("SELECT DATE_FORMAT(date, '%M') AS month_name FROM expense
where month(date) = month(curdate()) limit 1;");
$monthname = $monthnameresult->fetch_assoc()['month_name'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changes</title>
    <link rel="stylesheet" href="style.css?v=3">
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
            <div class="bento-container">
                <div class="left-bento">
                    <div class="money-grid">
                        <div class="incomeDisplay-index">
                            <a href="http://localhost/Changes/transaction.php?type=Income&category=">
                            <img src="./assests/income.svg" alt="income.svg" width="60px" class="image">
                            <div class="income-desc">
                                
                                <p class="income-text">Total Income</p>
                                <p class="income-value">₹<?= number_format($totalIncome,2) ?></p>
                            </a>
                            </div>
                        </div>
                        <div class="expenseDisplay-index">
                            
                            <img src="./assests/expense.svg" alt="expense.svg" width="60px" class="image">
                            <div class="expense-desc">
                                <a href="http://localhost/Changes/transaction.php?type=Expense&category=">
                                <p class="income-text">Total Expense</p>
                                <p class="expense-value">₹<?= number_format($totalExpense,2) ?></p>
                                </a>
                            </div>
                            
                        </div>
                        <div class="balanceDisplay-index">
                            
                            <img src="./assests/budget.svg" alt="budget.svg" width="60px" class="image">
                            <div class="budget-desc">
                                <a href="transaction.php">
                                <p class="income-text">Total Budget</p>
                                <p class="budget-value">₹<?= number_format($balance,2)?></p>
                                </a>
                            </div>

                            
                        </div>
                    </div>

                    <div class="transaction-grid">
                            <a href="transaction.php"><p class="recent-text">Recent transactions</p></a>
                            <div class="audits-index">
                                <?php while($row = $transaction->fetch_assoc()): ?>
                                <div class="transaction">

                                    <img src="./assests/transactions/<?= $row['type'] ==='Expense' ? 'expense.svg' : 'income.svg' ?>  " width="50px" class="icon">
                                    
                                    <div class="usage"> <p><?= htmlspecialchars($row['category']) ?></p></div>
                                    <p class="Time"> <?= date("d M y, H:i", strtotime($row['date'])) ?> </p>
                                    <p class="amount">
                                        <?= $row['type'] === 'Expense' ? '-' : '+' ?>
                                        ₹<?= number_format($row['amount'],2) ?>
                                    </p>
                                </div>
                                <?php endwhile; ?>
                        </div>
                    </div>
                    <div class="summary-grid">
                        <div class="monthlyDisplay">
                            <div class="this-month"><?= htmlspecialchars($monthname)?></div>
                            <div class="desc-container">
                                <div class="amount-container">
                                    <div class="summary-balance">Balance: </div>
                                    <div class="summary-balance">₹<?= number_format($monthlybalance,0)?></div>
                                </div>
                                <div class="amount-container">
                                    <div class="summary-average">Average(spent): </div>
                                    <div class="summary-average">₹<?= number_format($average,0)?> </div>
                                </div>
                                <div class="amount-container">
                                    <div class="summary-transaction">Transactions:  </div>
                                    <div class="summary-transaction">₹<?= number_format($totaltransaction,0)?> </div>
                                </div>
                            </div>
                        </div>
                        <div class="yearlyDisplay">
                            <div class="this-month">2025</div>
                            <div class="desc-container">
                                <div class="amount-container">
                                    <div class="summary-balance">Balance: </div>
                                    <div class="summary-balance">₹<?= number_format($monthlybalance,0)?></div>
                                </div>
                                <div class="amount-container">
                                    <div class="summary-average">Average(spent): </div>
                                    <div class="summary-average">₹<?= number_format($average,0)?> </div>
                                </div>
                                <div class="amount-container">
                                    <div class="summary-transaction">Transactions:  </div>
                                    <div class="summary-transaction">₹<?= number_format($totaltransaction,0)?> </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="buttons-container">
                        <div class="expense-btn-container">
                            <a href="add_income.html" class="add-expense">
                            <img src="./assests/add-income.svg" width="50px" class="img-add">
                            <p class="expense-btn">Add Income</p>
                            </a>
                        </div>
                        <div class="expense-btn-container">
                            <a href="add_expense.html" class="add-expense">
                            <img src="./assests/add-expense.svg" width="50px" class="img-add">
                            <p class="income-btn">Add Expense</p>
                            </a>
                        </div>
                        <div class="expense-btn-container">
                            <a href="add_expense.html" class="add-expense">
                            <img src="./assests/addBudget.svg" width="50px" class="img-add">
                            <p class="income-btn">Add Budget</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="right-bento">
                    <div class="graphDisplay">
                        <a href="stats.php"><p class="weekly-text">Weekly Spending Trend</p></a>
                        <canvas id="weeklychart" width="500" height="300"></canvas>
                    </div>
                    <div class="budgetDisplay">
                            <a href="budget.php"><p class="budget-text">Budget</p></a>
                              <?php while ($row = $weeklyBudgets->fetch_assoc()): ?>
                                <?php
                                    $budget_amount = $row['amount'];
                                    $category = $conn->real_escape_string($row['category']);
                                    $spent_result = $conn->query("SELECT SUM(amount) AS spent FROM expense WHERE user_id = $user_id AND category = '$category' AND date BETWEEN '{$row['start_date']}' AND '{$row['end_date']}'");
                                    $spent = $spent_result->fetch_assoc()['spent'] ?? 0;
                                    $percentage = $budget_amount > 0 ? round(($spent / $budget_amount) * 100) : 0;
                                    $residual = max(0, $budget_amount - $spent);
                                    
                                    $categoryIcons = [
                                    'Grocery' => 'grocery.svg',
                                    'Transport' => 'transport.svg',
                                    'Shopping' => 'shopping.svg',
                                    'Medical' => 'medical.svg',
                                    'Entertainment' => 'entertainment.svg',
                                    'Others' => 'others.svg'
                                    ];

                                    $category = $row['category'];
                                    $icon = isset($categoryIcons[$category]) ? $categoryIcons[$category] : 'default.svg';

                                ?>
                                <div class="weekly-display">
                                   
                                    <div class="category-container">
                                        <div class="category-text"><?= htmlspecialchars($row['category']) ?></div>
                                    </div>
                                    
                                    <div class="progress-container">
                                        <div class="budget-image"><img src="./assests/transactions/<?= $icon?>" width="50px"></div>
                                        <div class="progress-bar-detials">
                                        <div class="progress-details-top">
                                            <div class="starting-date"><?= date('d/m/Y', strtotime($row['start_date'])) ?></div>
                                            <div class="progress-percentage"><?= $percentage ?>%</div>
                                            <div class="ending-date"><?= date('d/m/Y', strtotime($row['end_date'])) ?></div>
                                        </div>
                                        <div class="progress-bar"><div class="progress" style="width: <?= min($percentage, 100) ?>%"></div></div>
                                        <div class="progress-details-bottom">
                                            <div class="starting-amount">0.00</div>
                                            <div class="spent-amount"><?= number_format($spent, 2) ?></div>
                                            <div class="ending-amount"><?= number_format($budget_amount, 2) ?></div>
                                        </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <?php endwhile; ?>

                    </div>
                    <div class="quickButtons"></div>
                </div>
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="stats.js?v=1.0"></script>
</body>
</html>