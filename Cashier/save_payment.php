<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = intval($_POST['student_id']);
$amount = floatval($_POST['amount']);

if ($student_id && $amount > 0) {
    $stmt = $conn->prepare("INSERT INTO tuition (student_id, amount) VALUES (?, ?)");
    $stmt->bind_param("id", $student_id, $amount);
    if ($stmt->execute()) {
        echo "Payment saved successfully. <a href='Payments.php'>Go back</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Invalid data.";
}

$conn->close();
?>
