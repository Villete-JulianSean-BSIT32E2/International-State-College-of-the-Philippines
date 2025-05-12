<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

$search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

$sql = "SELECT Admission_ID, full_name 
        FROM tbladmission_addstudent 
        WHERE full_name LIKE '%$search%' 
        ORDER BY full_name ASC 
        LIMIT 10";

$result = $conn->query($sql);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        'id' => $row['Admission_ID'],
        'name' => $row['full_name']
    ];
}

echo json_encode(['success' => true, 'students' => $students]);
?>
