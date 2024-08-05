<?php
session_start();
include 'config.php';

if(isset($_SESSION['user_id'])) {
    header("Location: adopt.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        // Check if the account is activated
        if ($row['activated'] == 1) {
            // Verify the password using password_verify
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];

                if ($_SESSION['role'] == 'admin') {
                    header("Location: admin2.php");
                    exit();
                } else {
                    header("Location: adopt.php");
                    exit();
                }
            } else {
                $error = "Invalid username or password";
            }
        } else {
            $error = "Your account is not activated yet. Please check your email for activation instructions.";
        }
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- stylesheet -->
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>

<body>
    <section>
        <form id="loginForm" method="post" action="">
            <div class="inputbox">
                <ion-icon name="username"></ion-icon>
                <input type="text" name="username" id="username" required>
                <label for="username">Username</label>
            </div>
            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input type="password" name="password" id="password" required>
                <label for="password">Password</label>
            </div>
            <div class="forget">
                <label for="rememberMe"><input type="checkbox" id="rememberMe">Remember Me</label>
                <a href="forget_password.php">Forget Password</a>
            </div>
            <button type="submit">Log in</button>
            <div class="register">
                <p>Don't have an account <a href="Registration.html">Register</a></p>
            </div>
        </form>
    </section>

    <script src="assets/js/jquery.min.js"></script>
    <script src="https://unpkg.com/scrollreveal"></script>
    <script src="https://cdn.jsdelivr.net/gh/cferdinandi/gumshoe/dist/gumshoe.polyfills.min.js"></script>
</body>

</html>
