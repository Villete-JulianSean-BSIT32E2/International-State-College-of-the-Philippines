<?php
session_start();
$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT student_id FROM studentlogin WHERE user = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $student_id = $row['student_id'];
    $_SESSION['student_id'] = $student_id;

    $info_sql = "SELECT fullname, course, current_sem, school_year, section, student_id FROM studentinformation WHERE student_id = ?";
    $info_stmt = $conn->prepare($info_sql);
    $info_stmt->bind_param("i", $student_id);
    $info_stmt->execute();
    $info_result = $info_stmt->get_result();

    // Grades
    $grades = [];
    $grades_sql = "SELECT subject, grade FROM student_grades WHERE student_id = ?";
    $grades_stmt = $conn->prepare($grades_sql);
    $grades_stmt->bind_param("i", $student_id);
    $grades_stmt->execute();
    $grades_result = $grades_stmt->get_result();
    while ($grade_row = $grades_result->fetch_assoc()) {
        $grades[] = $grade_row;
    }

    // Schedule
    $schedule = [];
    $schedule_sql = "SELECT subject, start_time, end_time FROM student_subjects WHERE student_id = ?";
    $schedule_stmt = $conn->prepare($schedule_sql);
    $schedule_stmt->bind_param("i", $student_id);
    $schedule_stmt->execute();
    $schedule_result = $schedule_stmt->get_result();
    while ($sched_row = $schedule_result->fetch_assoc()) {
        $schedule[] = $sched_row;
    }

    // Payments
    $payments = [];
    $payment_sql = "SELECT payment_date, amount_paid, status FROM payments WHERE student_id = ?";
    $payment_stmt = $conn->prepare($payment_sql);
    $payment_stmt->bind_param("i", $student_id);
    $payment_stmt->execute();
    $payment_result = $payment_stmt->get_result();
    while ($payment_row = $payment_result->fetch_assoc()) {
        $payments[] = $payment_row;
    }

    // Attendance
    $attendance = [];
    $attendance_sql = "SELECT TimeIn, TimeOut, Status FROM attendance WHERE Admission_ID = ?";
    $attendance_stmt = $conn->prepare($attendance_sql);
    $attendance_stmt->bind_param("i", $student_id);
    $attendance_stmt->execute();
    $attendance_result = $attendance_stmt->get_result();
    while ($att_row = $attendance_result->fetch_assoc()) {
        $attendance[] = $att_row;
    }

    if ($info_result->num_rows === 1) {
        $student_info = $info_result->fetch_assoc();
        echo json_encode([
            'status' => 'success',
            'fullname' => $student_info['fullname'],
            'course' => $student_info['course'],
            'current_sem' => $student_info['current_sem'],
            'school_year' => $student_info['school_year'],
            'section' => $student_info['section'],
            'student_id' => $student_info['student_id'],
            'grades' => $grades,
            'schedule' => $schedule,
            'attendance' => $attendance,
            'payments' => $payments
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Student information not found.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect credentials.']);
}

$stmt->close();
$conn->close();
?>
