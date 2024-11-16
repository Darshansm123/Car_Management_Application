<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $user_id = $_SESSION['user_id'];
    $image_paths = [];

    // Server-side check for maximum 10 images
    if (count($_FILES['images']['tmp_name']) > 10) {
        echo "<script>alert('You can only upload up to 10 images.'); window.location.href = 'add_car.php';</script>";
        exit;
    }

    // Handle file uploads
    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        $fileName = basename($_FILES['images']['name'][$index]);
        $filePath = 'uploads/' . $fileName;

        // Move the uploaded file to the 'uploads' directory
        if (move_uploaded_file($tmpName, $filePath)) {
            $image_paths[] = $filePath;
        }
    }

    // Convert image paths to JSON format
    $image_paths_json = json_encode($image_paths);

    // Insert the car details into the database
    $stmt = $conn->prepare("INSERT INTO cars (user_id, title, description, tags, image_paths) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $title, $description, $tags, $image_paths_json);
    $stmt->execute();
    header("Location: add_car.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="css/styles.css">
    <title>Add Car</title>
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
        <h2>Add New Car</h2>
        <input type="text" name="title" placeholder="Title" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="text" name="tags" placeholder="Tags (comma-separated)">
        
        <!-- Image upload field with a maximum of 10 files -->
        <input type="file" name="images[]" multiple id="image-upload" accept="image/*"><br>

        <button type="submit">Add Car</button> 
        <p><a href="home.php" class="button-link"><b>Back to Car list</b></a></p> 
    </form>

    <!-- JavaScript to enforce a maximum of 10 images on the client side -->
    <script>
        const imageUpload = document.getElementById('image-upload');

        imageUpload.addEventListener('change', function() {
            if (this.files.length > 10) {
                alert('You can only upload up to 10 images.');
                this.value = ''; // Clear the input if the limit is exceeded
            }
        });
    </script>
</body>
</html>
