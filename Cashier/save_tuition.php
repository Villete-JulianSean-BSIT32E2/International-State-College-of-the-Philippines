<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize form data
$student_id = $_POST['student_id'];
$course = $conn->real_escape_string($_POST['course']);
$year_level = $conn->real_escape_string($_POST['year_level']);
$tuition = (float) $_POST['tuition'];
$monthly = (float) $_POST['monthly'];
$misc_fee = (float) $_POST['misc_fee'];
$lab_fee = (float) $_POST['lab_fee'];
$payment_method = $conn->real_escape_string($_POST['payment_method']);

// Calculate total_tuition and total_fee (tuition + misc + lab)
$total_tuition = $tuition + $misc_fee + $lab_fee;
$total_fee = $total_tuition; // or calculate differently if needed
$balance = $total_fee; // Initial balance equals total

// Insert data into tuition table
$sql = "INSERT INTO tuition (student_id, course, year_level, tuition, monthly, misc_fee, lab_fee, total_tuition, total_fee, balance, payment_method)
        VALUES ('$student_id', '$course', '$year_level', '$tuition', '$monthly', '$misc_fee', '$lab_fee', '$total_tuition', '$total_fee', '$balance', '$payment_method')";

if ($conn->query($sql) === TRUE) {
    header("Location: tuition.php?success=1");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
