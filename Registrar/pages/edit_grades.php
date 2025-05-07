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
$result = $conn->query("SELECT name FROM admission WHERE id = $student_id");
if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    die("Student not found.");
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $conn->real_escape_string($_POST['subject']);
    $grade = floatval($_POST['grade']);
    $remarks = $conn->real_escape_string($_POST['remarks']);

    // Update existing grade or insert if not exists
    $check = $conn->query("SELECT * FROM student_grades WHERE student_id = $student_id AND subject = '$subject'");
    if ($check->num_rows > 0) {
        $conn->query("UPDATE student_grades SET grade = $grade, remarks = '$remarks' WHERE student_id = $student_id AND subject = '$subject'");
    } else {
        $conn->query("INSERT INTO student_grades (student_id, subject, grade, remarks) VALUES ($student_id, '$subject', $grade, '$remarks')");
    }
}

// Get student grades
$grades = [];
$result = $conn->query("SELECT * FROM student_grades WHERE student_id = $student_id");
while ($row = $result->fetch_assoc()) {
    $grades[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Grades - <?= htmlspecialchars($student['name']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f9f9f9;
        }
        h2 {
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
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h2>Edit Grades for <?= htmlspecialchars($student['name']) ?></h2>

<table>
    <tr>
        <th>Subject</th>
        <th>Grade</th>
        <th>Remarks</th>
    </tr>
    <?php if (count($grades) > 0): ?>
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
    <a href="/International-State-College-of-the-Philippines/Registrar/registrar.php?page=grades_transcripts">Cancel</a>
</form>

</body>
</html>
