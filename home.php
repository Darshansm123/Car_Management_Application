<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';

$user_id = $_SESSION['user_id'];
$keyword = $_GET['search'] ?? '';

// Pagination variables
$limit = 5; // Number of cars per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and paginate cars
$query = "SELECT * FROM cars WHERE user_id=? AND (title LIKE ? OR description LIKE ? OR tags LIKE ?) LIMIT ? OFFSET ?";
$stmt = $conn->prepare($query);
$search = "%$keyword%";
$stmt->bind_param("isssii", $user_id, $search, $search, $search, $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();

// Get total number of cars for pagination
$count_query = "SELECT COUNT(*) as total FROM cars WHERE user_id=? AND (title LIKE ? OR description LIKE ? OR tags LIKE ?)";
$stmt = $conn->prepare($count_query);
$stmt->bind_param("isss", $user_id, $search, $search, $search);
$stmt->execute();
$count_result = $stmt->get_result();
$total_cars = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_cars / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>My Cars</title>
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



/* Main Content Styling */
.main-content {
    margin-left: 260px; /* Offset by the width of the sidebar */
    padding: 20px;
    background-color: #f4f4f9;
    min-height: 100vh; /* Ensure full height content */
}

        .add-car-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .add-car-button:hover {
            background-color: #45a049;
        }

        .add-car-container {
            display: flex;
            justify-content: flex-start; /* Aligns the button to the left */
        }
    </style>
</head>
<body>

<!-- Sidebar Section -->
<?php include 'sidebar.php'; ?>

<!-- Main Content Section -->
<div class="main-content">
    <h2>My Cars</h2>
    <div class="add-car-container">
        <a href="add_car.php" class="add-car-button">Add New Car</a>
    </div>
    <form method="get">
        <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($keyword) ?>">
        <button type="submit">Search</button>
    </form>
    <ul>
        <?php while ($car = $result->fetch_assoc()): ?>
            <li>
                <h3><?= htmlspecialchars($car['title']) ?></h3>
                <a href="view_car.php?id=<?= $car['id'] ?>">View</a>
                <a href="edit_car.php?id=<?= $car['id'] ?>">Edit</a>
                <a href="delete_car.php?id=<?= $car['id'] ?>">Delete</a>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- Pagination Links -->
    <div>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="home.php?page=<?= $i ?>&search=<?= htmlspecialchars($keyword) ?>">Page-<?= $i ?></a>
        <?php endfor; ?>
    </div>
</div>

</body>
</html>
