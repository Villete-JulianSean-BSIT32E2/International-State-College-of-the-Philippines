<?php
$mysqli = new mysqli("localhost", "root", "", "iscpdb");

if ($mysqli->connect_error) {
    echo json_encode(["success" => false, "error" => "DB connection failed"]);
    exit;
}

$student_id = $_GET['student_id'];

$result = $mysqli->query("SELECT total_tuition, balance FROM tuition WHERE student_id = '$student_id' LIMIT 1");

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "success" => true,
        "total" => $row['total_tuition'],
        "balance" => $row['balance']
    ]);
} else {
    echo json_encode(["success" => false]);
}
?>
