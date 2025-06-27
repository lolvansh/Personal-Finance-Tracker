<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

$servername = "localhost";
$username = "root"; // default XAMPP usernamewo
$password = "Cyanide@1010";     // default XAMPP password is empty
$database = "FinanceTracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(" Connection failed: " . $conn->connect_error);
}


$category = $_POST['description'];
$amount = $_POST['Amount'];
$date = date('Y-m-d H:i:s');

$sql = "INSERT INTO income (category,amount,date, user_id) values(?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt ->bind_param("sdsi", $category, $amount, $date, $user_id);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}


if($stmt->execute()){
    echo '<div style="
    margin: 40px auto;
    width: fit-content;
    padding: 15px 25px;
    background-color: #d4edda;
    color: #155724;
    font-size: 18px;
    border: 1px solid #c3e6cb;
    border-radius: 8px;
    font-family: sans-serif;
    text-align: center;
">
    ✅ Income saved successfully! Redirecting to dashboard...
    </div>';


    echo "<script>
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 1000); // 2 seconds delay
        </script>";
}else{
    echo "Error";
}

$stmt->close();
$conn->close();
?>