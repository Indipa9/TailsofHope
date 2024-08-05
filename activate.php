<?php
// Check if email parameter is set in the URL
if(isset($_GET['email'])) {
    // Retrieve email from URL
    $email = $_GET['email'];

    // Connect to your database (replace with your own database credentials)
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "crm_db";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update user's activation status in the database
    $sql = "UPDATE users SET activated = 1 WHERE email = '$email'";

    if ($conn->query($sql) === TRUE) {
        echo "Your account has been successfully activated.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    echo "Email parameter is missing.";
}
?>
