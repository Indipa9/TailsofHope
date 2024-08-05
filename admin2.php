<?php
// Include the config file
include 'config.php';

// Check if the user is an admin, otherwise redirect to login page
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "crm_db";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the "Add User" form is submitted
if (isset($_POST['add_user'])) {
    $new_username = mysqli_real_escape_string($conn, $_POST['new_username']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the password
    $new_role = mysqli_real_escape_string($conn, $_POST['new_role']);

    // Your SQL query to insert a new user
    $insert_query = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', '$new_role')";

    if ($conn->query($insert_query) === TRUE) {
        echo "New user added successfully";
    } else {
        echo "Error: " . $insert_query . "<br>" . $conn->error;
    }
}

// Handle search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM users WHERE username LIKE '%$search%' OR role LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM users";
}

$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin-styles.css">
</head>

<body>
    <div class="admin-container">
        <h2>Admin Panel</h2>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search for users...">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- List of Users -->
        <div class="admin-section">
            <h3>Users</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo $user['username']; ?></td>
                        <td><?php echo $user['role']; ?></td>
                        <td>
                            <?php if ($_SESSION['username'] !== $user['username']) : ?>
                                <!-- Edit User Form -->
                                <form method="post" action="">
                                    <input type="hidden" name="edit_user_id" value="<?php echo $user['id']; ?>">
                                    <input type="text" name="edited_username" value="<?php echo $user['username']; ?>" required>
                                    <input type="password" name="edited_password" placeholder="New Password">
                                    <select name="edited_role" required>
                                        <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="submit" name="edit_user">Edit</button>
                                </form>

                                <!-- Delete User Form -->
                                <form method="post" action="" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                                    <button type="submit" name="delete_user">Delete</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <!-- Form for Adding New User -->
        <div class="admin-section">
            <h3>Add New User</h3>
            <form method="post" action="">
                <input type="text" name="new_username" placeholder="Username" required>
                <input type="password" name="new_password" placeholder="Password" required>
                <select name="new_role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="add_user">Add User</button>
            </form>
        </div>
    </div>

    <?php
    // Delete User logic
    if (isset($_POST['delete_user'])) {
        $user_id_to_delete = $_POST['delete_user_id'];
        $delete_query = "DELETE FROM users WHERE id = $user_id_to_delete";
        if ($conn->query($delete_query) === TRUE) {
            echo "<script>alert('User deleted successfully');</script>";
            echo "<script>window.location.href = 'admin2.php';</script>";
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    }
    ?>

</body>

</html>
