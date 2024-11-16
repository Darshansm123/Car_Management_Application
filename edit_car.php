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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $new_image_paths = [];

    // Handle image uploads
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        if ($tmpName) {
            $fileName = basename($_FILES['images']['name'][$index]);
            $filePath = 'uploads/' . $fileName;
            if (move_uploaded_file($tmpName, $filePath)) {
                $new_image_paths[] = $filePath;
            }
        }
    }

    // Combine old and new images
    $final_image_paths = array_merge($images, $new_image_paths);
    $final_image_paths_json = json_encode($final_image_paths);

    $stmt = $conn->prepare("UPDATE cars SET title = ?, description = ?, tags = ?, image_paths = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ssssii", $title, $description, $tags, $final_image_paths_json, $car_id, $user_id);
    $stmt->execute();

    header("Location: view_car.php?id=$car_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>Edit Car</title>
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
    <form method="post" enctype="multipart/form-data">
        <h2>Edit Car</h2>
        <input type="text" name="title" placeholder="Title" value="<?= htmlspecialchars($car['title']) ?>" required>
        <textarea name="description" placeholder="Description"><?= htmlspecialchars($car['description']) ?></textarea>
        <input type="text" name="tags" placeholder="Tags (comma-separated)" value="<?= htmlspecialchars($car['tags']) ?>">
        <h3>Existing Images</h3>
        <div>
            <?php foreach ($images as $image): ?>
                <img src="<?= $image ?>" alt="Car Image" style="width: 100px; height: auto;">
            <?php endforeach; ?>
        </div>
        <h3>Upload Up to 10 New Images</h3>
        <input type="file" name="images[]" multiple>
        <button type="submit">Update Car</button>
    </form>
</body>
</html>
