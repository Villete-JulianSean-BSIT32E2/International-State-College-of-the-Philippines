<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
    exit();
}

$student_id = $_POST['student_id'] ?? '';
$new_password = $_POST['new_password'] ?? '';

if (!$student_id || !$new_password) {
    echo json_encode(['status' => 'error', 'message' => 'Missing student ID or password']);
    exit();
}

$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Optional: hash the password for security (recommended)
// $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$hashed_password = $new_password; // For now plain text; replace with hash in production

// Check if student_id exists
$stmt = $conn->prepare("SELECT student_id FROM studentlogin WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Student ID not found']);
    $stmt->close();
    $conn->close();
    exit();
}
$stmt->close();

// Update password
$update_stmt = $conn->prepare("UPDATE studentlogin SET password = ? WHERE student_id = ?");
$update_stmt->bind_param("ss", $hashed_password, $student_id);

if ($update_stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
}

$update_stmt->close();
$conn->close();
?>
