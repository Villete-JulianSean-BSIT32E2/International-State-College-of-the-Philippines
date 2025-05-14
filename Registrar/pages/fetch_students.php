<?php
include '../connect.php';

header('Content-Type: application/json');

$search = $_GET['q'] ?? '';
$data = [];

if ($search !== '') {
    $stmt = $conn->prepare("SELECT Admission_ID, full_name, course FROM tbladmission_addstudent WHERE full_name LIKE CONCAT(?, '%') LIMIT 10");
    $stmt->bind_param("s", $search);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

echo json_encode($data);
