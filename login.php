<?php
session_start();
require 'db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if user exists
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: index.php");
            exit;
        } else {
            echo "<p style='color:red'>Incorrect password</p>";
        }
    } else {
        echo "<p style='color:red'>No user found</p>";
    }
}
?>


<html
<head>
  <title>Login</title>
  <link rel="stylesheet" href="auth2.css"> 
</head>
<body>
  <form action="" method="POST" class="form">
    <h2>Login</h2>
    
    <label>Email:</label>
    <input type="email" name="email" required>

    <label>Password:</label>
    <input type="password" name="password" required>

    <button type="submit" name="login">Login</button>

    <p>Don't have an account? <a href="registration.php">Register</a></p>
  </form>
</body>
</html>