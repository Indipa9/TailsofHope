<?php
session_start();

include 'config.php';

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Fetch user details from the database
$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // Handle case where user is not found in the database
    header('Location: login.php');
    exit();
}


// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $fullName = $_POST['full_name'];
    $bio = $_POST['bio'];

    // Handle profile picture upload
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == 0) {
        $profilePicture = file_get_contents($_FILES['profilePicture']['tmp_name']);
        $profilePicture = base64_encode($profilePicture);
    } else {
        // If no new profile picture is uploaded, retain the existing one
        $profilePicture = $user['profile_picture'];
    }

    // Update user profile data in the database using prepared statement
    $updateSql = "UPDATE users SET profile_picture = ?, email = ?, full_name = ?, bio = ? WHERE username = ?";
    
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sssss", $profilePicture, $email, $fullName, $bio, $username);
    
    if ($stmt->execute()) {
        echo "Profile updated successfully";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }
    $stmt->close();
}


    // Update user profile data in the database
    $updateSql = "UPDATE users SET email = '$email', full_name = '$fullName', bio = '$bio' WHERE username = '$username'";

    if ($conn->query($updateSql) === TRUE) {
        echo "Profile updated successfully";
    } else {
        echo "Error updating profile: " . $conn->error;
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile-styles.css">
</head>

<body>
    <div class="profile-container">
        <h2>Welcome, <?php echo $user['username']; ?>!</h2>

        <!-- Display user profile picture -->
        <?php if (!empty($user['profile_picture'])) : ?>
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" class="profile-picture">
        <?php endif; ?>

        <!-- Profile Form -->
        <form method="post" action="" enctype="multipart/form-data" class="profile-form">
            <label for="profilePicture">Profile Picture:</label>
            <input type="file" name="profilePicture" accept="image/*">

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>">

            <label for="fullName">Full Name:</label>
            <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>">

            <label for="bio">Bio:</label>
            <textarea name="bio"><?php echo $user['bio']; ?></textarea>

            <button type="submit">Update Profile</button>
        </form>

        <!-- Logout button -->
        <a href="logout.php" class="logout-button">Logout</a>
    </div>

    <!-- Bootstrap JS and Popper.js for modal functionality -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
