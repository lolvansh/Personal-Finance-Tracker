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
    SELECT id, category, amount, date, 'Expense' AS type FROM expense
    UNION
    SELECT id, category, amount, date, 'Income' AS type FROM income
    ORDER BY date DESC
"
);



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
                <ul class="navbar-list transaction-nav">
                    <li class="items">
                        <img src="./assests/nav/dashboard.svg" alt="dashboard.svg" class="nav-image" width="40px">
                        <a href="index.php">Overview</a></li>
                    <li class="items">
                        <img src="./assests/nav/stats.svg" alt="transaction.svg" class="nav-image" width="40px">
                        <a href="stats.php">Statistics</a></li>
                    <li class="items">
                        <img src="./assests/nav/transaction.svg" alt="stats.svg" class="nav-image" width="40px">
                        <a href="transaction.php" class="active">Transactions</a></li>
                    <li class="items">
                        <img src="./assests/nav/report.svg" alt="report.svg" class="nav-image" width="40px">
                        Report</li>
                </ul>
            </nav>
        </div>
        <!-- dashboard -->
        <div class="page-overview transaction-overview">
            <!-- middle part -->
            <div class="text-container">
                <div class="page-name transaction-page-name">Transaction</div>
                <div class="search-container">
                    <input type="text" class="userinput" placeholder="Search">
                </div>
            </div>
            
            <div class="page-content">
                <div class="audits transaction-audits">
                    <?php while($row = $transaction->fetch_assoc()): ?>
                        <div class="transaction total-transaction <?= $row['type'] === 'Expense' ? 'expense' : 'income' ?>">
                            <img src="./assests/transactions/<?= $row['type'] === 'Expense' ? 'expense.svg' : 'income.svg' ?>" width="50px" class="icon">
                            <p class="usage"> <?= htmlspecialchars($row['category']) ?> </p>
                            <p class="Time"> <?= date("d M y, H:i", strtotime($row['date'])) ?> </p>
                            <p class="amount">
                                <?= $row['type'] === 'Expense' ? '-' : '+' ?>
                                ₹<?= number_format($row['amount'], 2) ?>
                            </p>
                            <img src="./assests/transactions/dots.svg" width="50px" class="menu-icon">
                            <div class="actions">
                                <a href="edit.php?id=<?= $row['id'] ?>&type=<?= $row['type'] ?>" class="edit-btn">✏️ Edit</a>
                                <a href="delete.php?id=<?= $row['id'] ?>&type=<?= $row['type'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this transaction?');">🗑️ Delete</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <p class="errordisplay" style="display:none"></p>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.querySelector(".userinput").addEventListener("input", function () {
            const searchTerm = this.value.toLowerCase();
            const transactions = document.querySelectorAll(".transaction");

            transactions.forEach(function (item) {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = "";
            } else {
                item.style.display = "none";
                document.querySelector(".errordisplay").style.display = "flex";
                document.querySelector(".errordisplay").textContent = "No more Valid transaction Found";
            }
            });
    
            
        });
    </script>
</body>
</html>