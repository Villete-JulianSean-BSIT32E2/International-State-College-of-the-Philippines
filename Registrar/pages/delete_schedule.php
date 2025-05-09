<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? null;
if ($id) {
    $conn->query("DELETE FROM class_schedules WHERE id = $id");
}

header("Location: /International-State-College-of-the-Philippines/Registrar/registrar.php?page=class_schedules");
exit();
