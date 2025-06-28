<?php 
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }
$user_id = $_SESSION['user_id'];


if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $host = "localhost";
    $username = "root";
    $password = "Cyanide@1010";
    $database = "FinanceTracker";

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    $category = $_POST['category'];
    $budget_type = $_POST['budget_type'];
    $amount = $_POST['amount'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $stmt = $conn->prepare("INSERT INTO budgets (user_id, category, budget_type, amount, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdss", $user_id, $category, $budget_type, $amount, $start_date, $end_date);
    $stmt->execute();

    $stmt->close();
    $conn->close();
    header("Location: budget.php");
    exit;
    
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

   <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color:rgb(216, 255, 204);
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .budget-form {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      width: 90%;
      max-width: 400px;
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .budget-form label {
      font-weight: 600;
      display: flex;
      flex-direction: column;
      color: #333;
    }

    .budget-form input,
    .budget-form select {
      padding: 10px;
      margin-top: 5px;
      border-radius: 6px;
      border: 1px solid #ccc;
      font-size: 14px;
    }

    .budget-form button {
      margin-top: 10px;
      padding: 12px;
      background-color: #28a745;
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .budget-form button:hover {
      background-color: #218838;
    }
  </style>



</head>
<body>

  <form action="" method="POST" class="budget-form">
    <label>Category:
      <select name="category" required>
        <option value="">--Select Category--</option>
        <option value="Grocery">Grocery</option>
        <option value="Transport">Transport</option>
        <option value="Shopping">Shopping</option>
        <option value="Medical">Medical</option>
        <option value="Entertainment">Entertainment</option>
      </select>
    </label>

    <label>Type:
      <select name="budget_type" required>
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
      </select>
    </label>

    <label>Budget Amount:
      <input type="number" name="amount" step="0.01" required>
    </label>

    <label>Start Date:
      <input type="date" name="start_date" required>
    </label>

    <label>End Date:
      <input type="date" name="end_date" required>
    </label>

    <button type="submit">Save Budget</button>
  </form>

</body>
</html>