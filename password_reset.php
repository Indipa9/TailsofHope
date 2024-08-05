<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $checkTokenSql = "SELECT * FROM users WHERE reset_token = '$token'";
    $result = $conn->query($checkTokenSql);

    if ($result->num_rows > 0) {
        // Token exists, show password reset form
        ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Reset</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="password-reset-container">
                <h2>Password Reset</h2>
                <form method="post" action="reset_password.php">
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
        // Token doesn't exist, show error message
        echo "Invalid token.";
    }
} else {
    // Invalid request method or token not set
    echo "Invalid request.";
}
?>
