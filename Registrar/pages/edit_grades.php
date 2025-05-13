<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student ID from GET
$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
if (!$student_id) {
    die("Invalid student ID.");
}

// Fetch student name
$student = null;
$stmt = $conn->prepare("SELECT full_name FROM tbladmission_addstudent WHERE Admission_ID = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    die("Student not found.");
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = trim($_POST['subject']);
    $grade = floatval($_POST['grade']);
    $remarks = trim($_POST['remarks']);

    // Check if grade record already exists
    $check_stmt = $conn->prepare("SELECT * FROM student_grades WHERE student_id = ? AND subject = ?");
    $check_stmt->bind_param("is", $student_id, $subject);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update
        $update_stmt = $conn->prepare("UPDATE student_grades SET grade = ?, remarks = ? WHERE student_id = ? AND subject = ?");
        $update_stmt->bind_param("dsis", $grade, $remarks, $student_id, $subject);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        // Insert
        $insert_stmt = $conn->prepare("INSERT INTO student_grades (student_id, subject, grade, remarks) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("isds", $student_id, $subject, $grade, $remarks);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
    $check_stmt->close();
}

// Fetch all grades for this student
$grades = [];
$grade_stmt = $conn->prepare("SELECT * FROM student_grades WHERE student_id = ?");
$grade_stmt->bind_param("i", $student_id);
$grade_stmt->execute();
$grade_result = $grade_stmt->get_result();
while ($row = $grade_result->fetch_assoc()) {
    $grades[] = $row;
}
$grade_stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Grades - <?= htmlspecialchars($student['full_name']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }
        h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: white;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }
        form {
            margin-top: 30px;
            background-color: #fff;
            padding: 15px;
            border: 1px solid #ddd;
        }
        input[type="text"], input[type="number"] {
            padding: 6px;
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }
        button {
            padding: 8px 14px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .cancel {
            padding: 8px 14px;
            background-color: #6c757d;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>

<h2>Edit Grades for <?= htmlspecialchars($student['full_name']) ?></h2>

<table>
    <tr>
        <th>Subject</th>
        <th>Grade</th>
        <th>Remarks</th>
    </tr>
    <?php if (!empty($grades)): ?>
        <?php foreach ($grades as $g): ?>
            <tr>
                <td><?= htmlspecialchars($g['subject']) ?></td>
                <td><?= $g['grade'] ?></td>
                <td><?= htmlspecialchars($g['remarks']) ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="3">No grades found.</td></tr>
    <?php endif; ?>
</table>

<h3>Add or Update a Grade</h3>
<form method="POST">
    <label>Subject:</label>
    <input type="text" name="subject" required>

    <label>Grade:</label>
    <input type="number" name="grade" step="0.01" required>

    <label>Remarks:</label>
    <input type="text" name="remarks">

    <button type="submit">Save Grade</button>
    <a class="cancel" href="/International-State-College-of-the-Philippines/Registrar/registrar.php?page=grades_transcripts">Cancel</a>
</form>

</body>
</html>
