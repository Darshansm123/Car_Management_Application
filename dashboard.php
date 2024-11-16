<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// Include database connection
include 'db.php';

// Fetch data for the chart
$query = "SELECT COUNT(*) as total, tags FROM cars WHERE user_id = ? GROUP BY tags";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Prepare data for Chart.js
$tags = [];
$count = [];
while ($row = $result->fetch_assoc()) {
    $tags[] = $row['tags'];
    $count[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Car Management</title>
    <link rel="stylesheet" href="css/styles1.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Sidebar Section -->
<div class="sidebar">
    <h2>Car Management</h2>
  
    <ul>
        <li><a href="dashboard.php" class="active">Dashboard</a></li>
        <li><a href="add_car.php">Add Car</a></li>
        <li><a href="home.php">My Car</a></li>
        <li><a href="login.php">Logout</a></li>
    </ul>
</div>

<!-- Main Content Section -->
<div class="main-content">
    <header>
        <h1><marquee>Welcome to Melodious Car Management Application!</Marquee></h1>
    </header>

    <div class="container">
        <!-- Quick Access Features -->
        <h2>Quick Access to Features</h2>
        <div class="dashboard-links">
            <div class="dashboard-item">
                <h3>Manage Your Cars</h3>
                <p>View, edit, and delete your cars.</p>
                <a href="home.php" class="button">View My Cars</a>
            </div>

            <div class="dashboard-item">
                <h3>Add New Car</h3>
                <p>Upload a new car with images, title, description, and tags.</p>
                <a href="add_car.php" class="button">Add New Car</a>
            </div>

            <div class="dashboard-item">
                <h3>Manage Account</h3>
                <p>Edit your account details and settings.</p>
                <a href="edit_profile.php" class="button">Edit Profile</a>
            </div>
        </div>

        <!-- Graph Section -->
        <h2>Car Representation by Tags</h2>
        <div class="chart-container">
            <canvas id="carChart"></canvas>
        </div>
    </div>
</div>

<script>
    // Chart.js initialization
    const ctx = document.getElementById('carChart').getContext('2d');
    const carChart = new Chart(ctx, {
        type: 'bar', // You can change this to 'pie' or 'doughnut'
        data: {
            labels: <?= json_encode($tags) ?>,
            datasets: [{
                label: 'Number of Cars',
                data: <?= json_encode($count) ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
