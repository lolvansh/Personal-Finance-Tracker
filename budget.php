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

$weeklyBudgets = $conn->query("SELECT * FROM budgets WHERE user_id = $user_id AND budget_type = 'weekly'");
$monthlyBudgets = $conn->query("SELECT * FROM budgets WHERE user_id = $user_id AND budget_type = 'monthly'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
        <li class="items"><img src="./assests/nav/dashboard.svg" width="40px"><a href="index.php">Overview</a></li>
        <li class="items"><img src="./assests/nav/stats.svg" width="40px"><a href="stats.php">Statistics</a></li>
        <li class="items"><img src="./assests/nav/transaction.svg" width="40px"><a href="transaction.php">Transactions</a></li>
        <li class="items"><img src="./assests/nav/report.svg" width="40px"><a href="budget.php" class="active">Budget</a></li>
        <li class="items"><img src="./assests/log-out.svg" width="40px"><a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a></li>
      </ul>
    </nav>
  </div>

  <div class="page-overview">
    <div class="top-heading">
      <div class="page-name">Budgets</div>
      <div class="heading-add"><a href="addBudget.php"><button class="add-form">ADD</button></a></div>
    </div>

    <div class="budget-container">
      <!-- Weekly Budgets -->
      <?php while ($row = $weeklyBudgets->fetch_assoc()): ?>
      <?php
        $budget_amount = $row['amount'];
        $category = $conn->real_escape_string($row['category']);
        $spent_result = $conn->query("SELECT SUM(amount) AS spent FROM expense WHERE user_id = $user_id AND category = '$category' AND date BETWEEN '{$row['start_date']}' AND '{$row['end_date']}'");
        $spent = $spent_result->fetch_assoc()['spent'] ?? 0;
        $percentage = $budget_amount > 0 ? round(($spent / $budget_amount) * 100) : 0;
        $residual = max(0, $budget_amount - $spent);
      ?>
      <div class="weekly-display">
        <div class="middle">
          <div class="heading">Weekly Budget</div>
          <div class="category"><?= htmlspecialchars($row['category']) ?></div>
          <div class="progress-container">
            <div class="budget-image"><img src="./assests/transactions/train.svg" width="50px"></div>
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
          <div class="bottom-buttons">
            <div class="left-amount">Residual Amount: <?= number_format($residual, 2) ?></div>
            <div class="buttons">
              <div class="add-budget"><button class="edit-button">Edit</button></div>
              <div class="add-budget"><button class="del-button">DEL</button></div>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>

      <!-- Monthly Budgets -->
      <?php while ($row = $monthlyBudgets->fetch_assoc()): ?>
      <?php
        $budget_amount = $row['amount'];
        $category = $conn->real_escape_string($row['category']);
        $spent_result = $conn->query("SELECT SUM(amount) AS spent FROM expense WHERE user_id = $user_id AND category = '$category' AND date BETWEEN '{$row['start_date']}' AND '{$row['end_date']}'");
        $spent = $spent_result->fetch_assoc()['spent'] ?? 0;
        $percentage = $budget_amount > 0 ? round(($spent / $budget_amount) * 100) : 0;
        $residual = max(0, $budget_amount - $spent);
      ?>
      <div class="monthly-display">
        <div class="middle">
          <div class="heading">Monthly Budget</div>
          <div class="category"><?= htmlspecialchars($row['category']) ?></div>
          <div class="progress-container">
            <div class="budget-image"><img src="./assests/transactions/train.svg" width="50px"></div>
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
          <div class="bottom-buttons">
            <div class="left-amount">Residual Amount: <?= number_format($residual, 2) ?></div>
            <div class="buttons">
              <div class="add-budget"><button class="edit-button">Edit</button></div>
              <div class="add-budget"><button class="del-button">DEL</button></div>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; ?>

    </div>
  </div>
</div>

</body>
</html>
