<?php
// Include PHPMailer and other necessary files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Include database configuration
require 'config.php';

// Function to send reset password email
function sendResetEmail($email, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0; // Enable verbose debug output if needed
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Specify your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'IndipaPerera5@gmail.com'; // Your SMTP username
        $mail->Password = 'vdsi hcho qfgh hisw'; // Your SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('IndipaPerera5@gmail.com', 'Your Name');
        $mail->addAddress($email); // Add recipient's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        $mail->Body = "Click the link to reset your password: <a href='http://localhost/login/reset_password.php?token=$token'>Reset Password</a>";

        // Send email
        $mail->send();
        // Redirect to password reset sent page
        header("Location: password_reset_sent.php");
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = $_POST['email'];

    // Check if the email exists in the database
    $checkEmailSql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmailSql);

    if ($result && $result->num_rows > 0) {
        // Email exists, generate token and send reset email
        // Generate a reset token
$token = bin2hex(random_bytes(32));

// Store the token in the 'reset_token' column of the 'users' table
$updateTokenSql = "UPDATE users SET reset_token = '$token' WHERE email = '$email'";

// Set the timezone to Sri Lanka
date_default_timezone_set('Asia/Colombo');

// Generate a timestamp for the created_at column
$createdAt = date('Y-m-d H:i:s'); // Current timestamp in MySQL format (YYYY-MM-DD HH:MM:SS)

// Update the user record with the generated token and timestamp
$updateTokenSql = "UPDATE users SET reset_token = '$token', created_at = '$createdAt' WHERE email = '$email'";

// Execute the query to update the token in the database
if ($conn->query($updateTokenSql) === TRUE) {
    // Send reset email with the token
    sendResetEmail($email, $token);
} else {
    // Handle error if the token could not be stored in the database
    echo "Error storing reset token in the database: " . $conn->error;
}


        if ($conn->query($updateTokenSql) === TRUE) {
            // Send reset email
            sendResetEmail($email, $token);
        } else {
            echo "Error updating reset token: " . $conn->error;
        }
    } else {
        echo "Email not found in the database";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget paassword</title>
    <!-- stylesheet -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
<section>

    <form id="loginForm" action="" method="post">
        <h1>Reset Password</h1>
        <div class="inputbox">
            <ion-icon name="mail-outline"></ion-icon>
            <input type="email" name="email" id="emailInput" required>
            <label for="emailInput">Enter your Email</label>
        </div>
        
        <div class="forget">
            <label for="rememberMe"><input type="checkbox" id="rememberMe">Remember Me</label>
            
        </div>
        <button type="submit" >Send Email</button>
        <div class="register">
            <p>Don't have an account <a href="Registration.html">Register</a></p>
        </div>
    </form>
</section>

<script>
    function login() {

        const email = document.getElementById('emailInput').value;
        const password = document.getElementById('passwordInput').value;


        if (email === 'ucsc@gmail.com' && password === 'ucsc@123') {

            window.location.href = "dashboard.html";
        } else {

            alert('Invalid email or password. Please try again.');
        }
    }
</script>
<script src="assets/js/jquery.min.js"></script>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/gh/cferdinandi/gumshoe/dist/gumshoe.polyfills.min.js"></script>
<script src="assets/js/main.js"></script>

</html>


</body>