<?php
session_start();

// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit();
}

// Establish database connection
include 'config.php'; // Assuming this file contains database connection details
// Assuming your database connection is already established and stored in the variable $conn

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare a SQL statement with placeholders
    $sql = "INSERT INTO pets (user_id, post_id, pet_type, name, breed, age, description, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters to the statement
    $stmt->bind_param("iisssiss", $user_id, $post_id, $pet_type, $name, $breed, $age, $description, $image);
    
    // Set parameters and execute the statement
    $user_id = $_SESSION['user_id']; // Retrieve user ID from session
    $post_id = $_POST['post_id']; // Assuming you pass the post_id from the form
    $pet_type = $_POST['pet_type'];
    $name = $_POST['name'];
    $breed = $_POST['breed'];
    $age = $_POST['age'];
    $description = $_POST['description'];
    $image = "uploads/" . $_FILES['image']['name']; // Assuming you're storing images in an 'uploads' directory
    
    // Move uploaded file to the desired location
    move_uploaded_file($_FILES['image']['tmp_name'], $image);
    
    // Execute the statement
    $stmt->execute();
    
    // Close the statement
    $stmt->close();
    
    // Redirect to a success page or display a success message
    header("Location: adopt.php");
    exit();
}
?>
