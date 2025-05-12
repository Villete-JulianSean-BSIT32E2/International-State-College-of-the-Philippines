<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = intval($_GET['student_id']);
$sql = "SELECT SUM(amount) AS balance FROM tuition WHERE student_id = $student_id";
$result = $conn->query($sql);

$data = ['balance' => 0];
if ($row = $result->fetch_assoc()) {
    $data['balance'] = $row['balance'];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
