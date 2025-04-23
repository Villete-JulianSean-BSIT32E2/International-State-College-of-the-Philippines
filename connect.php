<?php
// connect.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Connect only if not already connected
if (!isset($conn) || !$conn instanceof mysqli) {
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>




$conn->close();
?>
