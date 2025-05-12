<?php
include '../connect.php';

if (isset($_GET['student'])) {
    $student = $_GET['student'];

    // Secure your input
    $student = mysqli_real_escape_string($conn, $student);

    $query = "SELECT balance, total_fee FROM tuition WHERE student_name = '$student' LIMIT 1";
    $result = mysqli_query($conn, $query);

    $response = ['balance' => '', 'total_fee' => ''];

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response['balance'] = $row['balance'];
        $response['total_fee'] = $row['total_fee'];
    }

    echo json_encode($response);
}
?>
