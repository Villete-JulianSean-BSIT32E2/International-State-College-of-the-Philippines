<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$sql = "DELETE FROM tuition WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    header("Location: tuition.php?deleted=1");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$conn->close();
?>
