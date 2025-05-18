<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

$today = date('Y-m-d');

$stmt = $conn->prepare("
    SELECT Status, COUNT(*) AS count
    FROM attendance
    WHERE Date = ?
    GROUP BY Status
");
$stmt->bind_param('s', $today);
$stmt->execute();
$result = $stmt->get_result();

$data = ['present' => 0, 'late' => 0, 'absent' => 0];

while ($row = $result->fetch_assoc()) {
    switch ((int)$row['Status']) {
        case 0: $data['absent'] = (int)$row['count']; break;
        case 1: $data['present'] = (int)$row['count']; break;
        case 2: $data['late'] = (int)$row['count']; break;
    }
}

$data['date'] = $today;

echo json_encode($data);
$conn->close();
