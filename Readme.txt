admin username="Indipa" Password="indipa123"
database file=crm_db.sql
Connecting to the database
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "crm_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

