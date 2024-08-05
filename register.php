<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $telephone = $_POST['telephone'];
    $role = isset($_POST['role']) ? $_POST['role'] : '';

    // Hash the password
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Database connection details
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "crm_db";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists in the database
    $check_sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($check_sql);

    if ($result->num_rows > 0) {
        // Email address already exists, display error message
        echo "Error: This email address is already registered. Please use a different email address.";
    } else {
        // Insert user data into the database
        $sql = "INSERT INTO users (username, email, password, telephone, role) 
                VALUES ('$username', '$email', '$password', '$telephone', '$role')";

        if ($conn->query($sql) === TRUE) {
            // User registered successfully, send activation email
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'IndipaPerera5@gmail.com';
                $mail->Password = 'vdsi hcho qfgh hisw';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('IndipaPerera5@gmail.com', 'Indipa');
                $mail->addAddress($email, $username);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Account Activation';
                $mail->Body    = "Dear $username,<br><br>Your account has been successfully registered. Please click the following link to activate your account:<br><br><a href='http://localhost/login/activate.php?email=$email'>Activate Account</a><br><br>Thank you.";

                $mail->send();
                echo 'Activation email has been sent to your email address. Please check your inbox and spam folder.';
            } catch (Exception $e) {
                echo 'Failed to send activation email. Please try again later.';
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    // Close database connection
    $conn->close();
}
?>
