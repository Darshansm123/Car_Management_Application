<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php");
            exit;
        }
    }
    $error = "Invalid login credentials!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>Login</title>
    <style>
       body {
            background-image: url("images/book_car2.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            display:;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        </style>
</head>
<body>
    <form method="post">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        
        <!-- Password with pattern matching -->
        <input 
            type="password" 
            name="password" 
            placeholder="Password" 
            required 
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
            title="Password must be at least 8 characters, include at least one uppercase letter, one lowercase letter, and one number."
        >
        
        <button type="submit"><b>Login</b></button>
        <p><a href="register.php" class="button-link"><b>Register Here</b></a></p>
    </form>
</body>
</html>
