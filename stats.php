<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010"; // replace with yours
$database = "FinanceTracker"; // your database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$incomeResult = $conn -> query("select SUM(amount) AS total_income from income");
$totalIncome = $incomeResult ->fetch_assoc()['total_income'] ?? 0;


$expenseResult = $conn ->query("select SUM(amount) as total_expense from expense");
$totalExpense = $expenseResult ->fetch_assoc()['total_expense'] ?? 0;

$balance = $totalIncome - $totalExpense;

$transaction = $conn ->query(
    "
    SELECT category, amount, date, 'Expense' AS type FROM expense
    UNION
    SELECT category, amount, date, 'Income' AS type FROM income
    ORDER BY date DESC
    LIMIT 5
"
);

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance-tracker</title>
    <link rel="stylesheet" href="style.css?v=1.0">
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
                        <a href="index.php" >Overview</a></li>
                    <li class="items">
                        <img src="./assests/nav/stats.svg" alt="transaction.svg" class="nav-image" width="40px">
                        <a href="stats.php" class="active">Statistics</a></li></li>
                    <li class="items">
                        <img src="./assests/nav/transaction.svg" alt="stats.svg" class="nav-image" width="40px">
                        Transactions</li>
                    <li class="items">
                        <img src="./assests/nav/report.svg" alt="report.svg" class="nav-image" width="40px">
                        Report</li>
                </ul>
            </nav>
        </div>
        <!-- dashboard -->
        <div class="page-overview">
            <!-- middle part -->
            <p class="page-name">Statistics</p>
            <div class="top-section">
                
                <!-- 3 cards -->
                <div class="reportCards">
                    <!-- monthly -->
                    <div class="monthlyCard">
                        <div class="reportName">
                            <img src="./assests/calender.svg" alt="monthly.svg" width="30px" class="reportImg">
                            <p class="name">Monthly Report</p>
                        </div>
                        <div class="displayIncome">
                            <p class="incomeTextDisplay">Totol Income</p>
                            <p class="incomeDisplay">65,000.00</p>
                        </div>
                        <div class="displayExpense">
                            <p class="expenseTextDisplay">Totol Expense</p>
                            <p class="expenseDisplay">65,000.00</p>
                        </div>
                        <div class="displayBalance">
                            <p class="balanceTextDisplay">Totol Balance</p>
                            <p class="balanceDisplay">65,000.00</p>
                        </div>
                    </div>
                    <!--yearly-->
                    <div class="yearlyCard">
                        <div class="reportName">
                            <img src="./assests/calender.svg" alt="monthly.svg" width="30px" class="reportImg">
                            <p class="name">Yearly Report</p>
                        </div>
                        <div class="displayIncome">
                            <p class="incomeTextDisplay">Totol Income</p>
                            <p class="incomeDisplay">65,000.00</p>
                        </div>
                        <div class="displayExpense">
                            <p class="expenseTextDisplay">Totol Expense</p>
                            <p class="expenseDisplay">65,000.00</p>
                        </div>
                        <div class="displayBalance">
                            <p class="balanceTextDisplay">Totol Balance</p>
                            <p class="balanceDisplay">65,000.00</p>
                        </div>
                    </div>
                    <!--all time  -->
                    <div class="allTimeCard">
                        <div class="reportName">
                            <img src="./assests/calender.svg" alt="monthly.svg" width="30px" class="reportImg">
                            <p class="name">All-Time Report</p>
                        </div>
                        <div class="displayIncome">
                            <p class="incomeTextDisplay">Totol Income</p>
                            <p class="incomeDisplay">65,000.00</p>
                        </div>
                        <div class="displayExpense">
                            <p class="expenseTextDisplay">Totol Expense</p>
                            <p class="expenseDisplay">65,000.00</p>
                        </div>
                        <div class="displayBalance">
                            <p class="balanceTextDisplay">Totol Balance</p>
                            <p class="balanceDisplay">65,000.00</p>
                        </div>
                    </div>
                </div>

                <div class="chart-section">
                    <p>Weekly Spending Trend</p>
                    <canvas id="weeklychart" width="500" height="350"></canvas>
                </div>

            </div>

            <div class="bottom">
                <div class="category-chart">
                    <p class="chartName">Categorical Expenses</p>
                    <div class="chart-container">
                        <canvas id="doughnutChart" width="500" height="500"></canvas>
                    </div>
                </div>
                <div class="bar-chart">
                    <p class="chartName">Monthly Income vs Expense</p>
                    
                    <canvas id="incomeExpenseBarChart" width="700" height="500"></canvas>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js?v=1.0"></script>
    <script src="stats.js?v=1.0"></script>
</body>
</html>