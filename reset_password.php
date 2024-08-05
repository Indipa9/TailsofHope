<?php
// reset_password.php
require 'config.php';
// Include necessary files and configurations

// Check if the token is provided in the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query the database to check if the token is valid and not expired
    $checkTokenSql = "SELECT * FROM users WHERE reset_token = '$token' AND created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";
    $result = $conn->query($checkTokenSql);

    if ($result->num_rows > 0) {
        // Token is valid and not expired
        // Display a form for the user to enter a new password
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Reset Password</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="reset-password-container">
                <h2>Reset Password</h2>
                <form method="post" action="process_reset_password.php">
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    <label for="password">Enter your new password:</label>
                    <input type="password" name="password" required>
                    <label for="confirm_password">Confirm your new password:</label>
                    <input type="password" name="confirm_password" required>
                    <button type="submit">Reset Password</button>
                </form>
            </div>
        </body>
        </html>

        <?php
    } else {
        // Token is invalid or expired
        echo "Invalid or expired token. Please try again.";
    }
} else {
    // Token is not provided in the URL
    echo "Token is missing. Please try again.";
}
?>
