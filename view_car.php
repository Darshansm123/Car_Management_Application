<?php 
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$car_id = $_GET['id'];

// Fetch the car details
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $car_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Car not found or unauthorized access!");
}

$car = $result->fetch_assoc();
$images = json_decode($car['image_paths'], true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>View Car</title>
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
    <div class="container">
        <h1><b><marquee><?= htmlspecialchars($car['title']) ?>&nbsp;Car Details</marquee><b></h1>
        <h2><?= htmlspecialchars($car['title']) ?></h2>
        <p><?= htmlspecialchars($car['description']) ?></p>
        <p><strong>Tags:</strong> <?= htmlspecialchars($car['tags']) ?></p>

        <h3>Images</h3>
        <div class="image-gallery">
            <?php foreach ($images as $image): ?>
                <img src="<?= $image ?>" alt="Car Image" class="car-image">
            <?php endforeach; ?>
        </div>

        <div class="action-links">
            <a href="edit_car.php?id=<?= $car['id'] ?>" class="button-link">Edit</a>
            <a href="delete_car.php?id=<?= $car['id'] ?>" class="button-link">Delete</a>
            <a href="home.php" class="button-link">Back to List</a>
        </div>
    </div>
</body>
</html>
