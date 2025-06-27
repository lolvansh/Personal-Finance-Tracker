<?php
$host = "localhost";
$username = "root";
$password = "Cyanide@1010"; // replace with yours
$database = "FinanceTracker"; // your database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$month = date('m');
$year = date('Y');

$all_income = $conn ->query('SELECT SUM(amount) AS total FROM income')->fetch_assoc()['total'] ?? 0;
$all_expense = $conn ->query('SELECT SUM(amount) AS total FROM expense')->fetch_assoc()['total'] ?? 0;
$all_balance = $all_income - $all_expense;

$month_income = $conn->query("SELECT SUM(amount) AS total FROM income WHERE MONTH(date) = $month AND YEAR(date) = $year")->fetch_assoc()['total'] ?? 0;
$month_expense = $conn->query("SELECT SUM(amount) AS total FROM expense WHERE MONTH(date) = $month AND YEAR(date) = $year")->fetch_assoc()['total'] ?? 0;
$month_balance = $month_income - $month_expense;

$year_income = $conn ->query("SELECT SUM(amount) AS total FROM income WHERE YEAR(date) = $year")->fetch_assoc()['total'] ?? 0;
$year_expense = $conn ->query("SELECT SUM(amount) AS total FROM expense WHERE YEAR(date) = $year")->fetch_assoc()['total'] ?? 0;
$year_balance = $year_income - $year_expense;

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance-tracker</title>
    <link rel="stylesheet" href="style.css">
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
                            <p class="incomeDisplay">₹<?= number_format($month_income,2)?></p>
                        </div>
                        <div class="displayExpense">
                            <p class="expenseTextDisplay">Totol Expense</p>
                            <p class="expenseDisplay">₹<?=number_format($month_expense,2) ?></p>
                        </div>
                        <div class="displayBalance">
                            <p class="balanceTextDisplay">Totol Balance</p>
                            <p class="balanceDisplay">₹<?= number_format($month_balance,2)?></p>
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
                            <p class="incomeDisplay">₹<?= number_format($year_income,2)?></p>
                        </div>
                        <div class="displayExpense">
                            <p class="expenseTextDisplay">Totol Expense</p>
                            <p class="expenseDisplay">₹<?= number_format($year_expense,2)?></p>
                        </div>
                        <div class="displayBalance">
                            <p class="balanceTextDisplay">Totol Balance</p>
                            <p class="balanceDisplay">₹<?= number_format($year_balance,2)?></p>
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
                            <p class="incomeDisplay">₹<?= number_format($all_income,2) ?></p>
                        </div>
                        <div class="displayExpense">
                            <p class="expenseTextDisplay">Totol Expense</p>
                            <p class="expenseDisplay">₹<?= number_format($all_expense,2) ?></p>
                        </div>
                        <div class="displayBalance">
                            <p class="balanceTextDisplay">Totol Balance</p>
                            <p class="balanceDisplay">₹<?= number_format($all_balance,2)?></p>
                        </div>
                    </div>
                </div>

                <div class="chart-section">
                    <p class="chartName" >Weekly Spending Trend</p>
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
                    <canvas id="incomeExpenseLineChart" width="650" height="500"></canvas>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js?v=1.0"></script>
    <script src="stats.js?v=1.0"></script>
</body>
</html>