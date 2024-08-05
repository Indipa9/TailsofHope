<?php
// Include necessary files and configurations

// Extract the token from the form submission
$token = $_POST['token'];

// Extract the password from the form submission
$password = $_POST['password'];

// Hash the password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Database connection and configuration
require 'config.php';

// Begin a transaction
$conn->begin_transaction();

try {
    // Create a temporary table to store the user IDs to update
    $tempTableSql = "CREATE TEMPORARY TABLE temp_users (id INT)";
    $conn->query($tempTableSql);

    // Insert the user IDs into the temporary table
    $insertTempTableSql = "INSERT INTO temp_users (id)
                           SELECT id FROM users WHERE reset_token = ?";
    $stmtInsert = $conn->prepare($insertTempTableSql);
    $stmtInsert->bind_param("s", $token);
    $stmtInsert->execute();

    // Update the user's password and clear the reset token
    $updatePasswordSql = "UPDATE users
                          SET password = ?, reset_token = NULL
                          WHERE id IN (SELECT id FROM temp_users)";
    $stmtUpdate = $conn->prepare($updatePasswordSql);
    $stmtUpdate->bind_param("s", $hashedPassword);
    $stmtUpdate->execute();

    // Commit the transaction
    $conn->commit();

    // Password updated successfully
    echo "Password reset successfully. You can now <a href='login.php'>login</a> with your new password.";
} catch (Exception $e) {
    // Rollback the transaction on error
    $conn->rollback();
    echo "Error resetting password: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>
