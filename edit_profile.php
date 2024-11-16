<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch current user details
$query = "SELECT username, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (!empty($password) && $password !== $password_confirm) {
        $error = "Passwords do not match.";
    } else {
        // Update profile details
        $update_query = "UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $username, $email, $phone, $user_id);

        if ($stmt->execute()) {
            // Update password if provided
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $password_query = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($password_query);
                $stmt->bind_param("si", $hashed_password, $user_id);
                $stmt->execute();
            }
            $success = "Profile updated successfully.";
        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Edit Profile</title>
    <style>
        /* Sidebar CSS */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 180px;
    background-color: #2c3e50; /* Dark background for sidebar */
    color: red;
    padding-top: 6px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2); /* Add a slight shadow for depth */
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 15px;
    font-size: 1.5em;
    color: #ecf0f1;
    font-weight: bold;
}

.sidebar ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar ul li {
    margin: 20px 0;
}

.sidebar ul li a {
    display: block;
    color: Blue;
    text-decoration: none;
    padding: 1px 15px;
    border-radius: 1px;
    transition: all 0.3s;
    font-size: 1.1em;
}
</style>
</head>
<body>

<?php include 'sidebar.php'; ?>
    <div class="main-content">
        
       

        <form method="post">
        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p style="color: green;"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <h1>Edit Profile</h1>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

            <label for="email">Email:</label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>

            <label for="password">New Password (Optional):</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirm">Confirm New Password:</label>
            <input type="password" id="password_confirm" name="password_confirm" required>

            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>
