<?php
session_start();

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
include 'config.php';

// Fetch user's rescued pets
$user_id = $_SESSION['user_id'];
//$sql_rescued_pets = "SELECT * FROM rescued_pets WHERE user_id = $user_id ORDER BY rescue_date DESC LIMIT 5";
//$result_rescued_pets = $conn->query($sql_rescued_pets);

// Fetch user's adoption applications
$sql_adoption_apps = "SELECT * FROM adoption_applications WHERE id = $user_id ORDER BY created_at DESC LIMIT 5";
$result_adoption_apps = $conn->query($sql_adoption_apps);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Rescue Dashboard</title>
    <!-- External CSS libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 bg-dark sidebar">
                <div class="sidebar-sticky">
                    <h2 class="text-white mt-4">Welcome, <?php echo $_SESSION['username']; ?>!</h2>
                    <ul class="nav flex-column mt-4">
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="fas fa-home"></i> Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-paw"></i> Manage Rescued Pets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="./ADOPTIONregistration/adoption_application.php"><i class="fas fa-user-friends"></i> Adoption Applications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-9 px-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Pet Rescue Dashboard</h1>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h4 class="my-0 font-weight-normal">Your Rescued Pets</h4>
                            </div>
                            <div class="card-body">
                                <!-- Display the user's rescued pets -->
                               <!-- <?php while ($row = $result_rescued_pets->fetch_assoc()) { ?>
                                    <p><?php echo $row['pet_name']; ?></p>
                                    <!-- Display more details about the rescued pet -->
                                <?php } ?> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header">
                                <h4 class="my-0 font-weight-normal">Your Adoption Applications</h4>
                            </div>
                            <div class="card-body">
                                <!-- Display the user's adoption applications -->
                                <?php while ($row = $result_adoption_apps->fetch_assoc()) { ?>
                                    <p><?php echo $row['pet_name']; ?></p>
                                    <!-- Display more details about the adoption application -->
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- External JS libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
