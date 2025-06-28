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
                        <a href="index.php">Overview</a></li>
                    <li class="items">
                        <img src="./assests/nav/stats.svg" alt="transaction.svg" class="nav-image" width="40px">
                        <a href="stats.php">Statistics</a></li>
                    <li class="items">
                        <img src="./assests/nav/transaction.svg" alt="stats.svg" class="nav-image" width="40px">
                        <a href="transaction.php">Transactions</a></li>
                    <li class="items">
                        <img src="./assests/nav/report.svg" alt="report.svg" class="nav-image" width="40px">
                        <a href="budget.php" class="active">Budget</a></li>
                    <li class="items">
                        <img src="./assests/log-out.svg" alt="logout.svg" class="nav-image" width="40px">
                        <a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
        <!-- dashboard -->
        <div class="page-overview ">
            <!-- middle part -->
             <div class="top-heading">
                <div class="page-name">Budgets</div>
                <div class="heading-add">
                    <button class="add-form">ADD</Button>
                </div>
                
             </div>

            <div class="budget-container">
                <div class="weekly-display">
                    <div class="middle">
                        <div class="heading">Weekly Budget</div>
                        <div class="category">Entertainment</div>
                        <div class="progress-container">
                            <div class="budget-image">
                                <img src="./assests/transactions/train.svg" width="50px">
                            </div>
                            <div class="progress-bar-detials">
                                <div class="progress-details-top">
                                    <div class="starting-date">6/01/2025</div>
                                    <div class="progress-percentage">26%</div>
                                    <div class="ending-date">10/01/2025</div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 26%"></div>
                                </div>
                                <div class="progress-details-bottom">
                                    <div class="starting-amount">0.00</div>
                                    <div class="spent-amount">200</div>
                                    <div class="ending-amount">400</div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-buttons">
                            <div class="left-amount">Residual Amount: 200</div>
                            <div class="buttons">
                                <div class="add-budget"><button class="add">Edit</button></div>
                                <div class="add-budget"><button class="del">DEL</button></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="monthly-display">
                    <div class="middle">
                        <div class="heading">Monthly Budget</div>
                        <div class="category">Entertainment</div>
                        <div class="progress-container">
                            <div class="budget-image">
                                <img src="./assests/transactions/train.svg" width="50px">
                            </div>
                            <div class="progress-bar-detials">
                                <div class="progress-details-top">
                                    <div class="starting-date">6/01/2025</div>
                                    <div class="progress-percentage">26%</div>
                                    <div class="ending-date">10/01/2025</div>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: 26%"></div>
                                </div>
                                <div class="progress-details-bottom">
                                    <div class="starting-amount">0.00</div>
                                    <div class="spent-amount">200</div>
                                    <div class="ending-amount">400</div>
                                </div>
                            </div>
                        </div>
                        <div class="bottom-buttons">
                            <div class="left-amount">Residual Amount: 200</div>
                            <div class="buttons">
                                <div class="add-budget"><button class="add">Edit</button></div>
                                <div class="add-budget"><button class="del">DEL</button></div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>

        </div>
    </div>
</body>
</html>