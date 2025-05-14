<?php
include '../connect.php';

$q = $_GET['q'] ?? '';
$data = [];

if (!empty($q)) {
    $stmt = $conn->prepare("SELECT Admission_ID, full_name, course FROM tbladmission_addstudent WHERE full_name LIKE ?");
    $like = "%$q%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $res = $stmt->get_result();

    while ($row = $res->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
