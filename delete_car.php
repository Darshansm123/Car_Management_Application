<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$car_id = $_GET['id'];

// Check if the car belongs to the logged-in user
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $car_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Car not found or unauthorized access!");
}

// Delete the car
$stmt = $conn->prepare("DELETE FROM cars WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $car_id, $user_id);
$stmt->execute();

header("Location: home.php");
exit;
?>
