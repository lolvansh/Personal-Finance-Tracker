<?php 
session_start();
require 'db.php';

$errors = [];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm"];

    if(empty($username) || empty($email) || empty($password) || empty($confirm)){
        $error[] = "All Fields are required";
    }


    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if($password !== $confirm){
        $error[] = "passwords do not match.";
    }

    if(empty($error)){
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $username);
        $stmt ->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "Username or Email already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $hash);
            if ($insert->execute()) {
                $_SESSION['user_id'] = $insert->insert_id;
                header("Location: index.php"); // redirect to dashboard
                exit;
            } else {
                $errors[] = "Something went wrong.";
            }
        }
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Finance Tracker</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
  <div class="auth-container">
    <h2>Create Your Account</h2>

    <?php foreach ($errors as $error): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endforeach; ?>

    <form method="post" action="">
      <label>Username</label>
      <input type="text" name="username" class="auth-input" required>

      <label>Email</label>
      <input type="email" name="email" class="auth-input" required>

      <label>Password</label>
      <input type="password" name="password" class="auth-input" required>

      <label>Confirm Password</label>
      <input type="password" name="confirm" class="auth-input" required>

      <button type="submit" class="auth-btn">Register</button>
    </form>

    <p class="auth-footer">Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>