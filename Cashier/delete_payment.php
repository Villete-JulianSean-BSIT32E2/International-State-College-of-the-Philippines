<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$conn->query("DELETE FROM payments WHERE id = $id");

header("Location: Payments.php");
exit();
?>
