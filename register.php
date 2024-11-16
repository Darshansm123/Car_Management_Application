<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $phone, $password);
    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        $error = "Registration failed!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>Register</title>
    <style>
       body {
            background-image: url("images/book_car1.jpg");
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
        <h2>Register</h2>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
        <input type="text" name="username" placeholder="Username" required>
        <input 
            type="text" 
            name="email" 
            placeholder="Email" 
            required 
            pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
            title="Enter a valid email address, e.g., user@example.com"
        >
        <input 
            type="text" 
            name="phone" 
            placeholder="Phone Number" 
            required 
            pattern="[0-9]{10}"
            title="Enter a valid 10-digit phone number, e.g., 1234567890"
        >
        <input 
            type="password" 
            name="password" 
            placeholder="Password" 
            required 
            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
            title="Password must be at least 8 characters, include at least one uppercase letter, one lowercase letter, and one number."
        >
        <button type="submit"><b>Register</b></button>
        <p><a href="login.php" class="button-link"><b>Login Here</b></a></p>
    </form>
</body>
</html>
