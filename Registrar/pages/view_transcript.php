<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$student_name = "";
$grades = [];

if ($student_id > 0) {
    // Get student name from tbladmission_addstudent table
    $stmt = $conn->prepare("SELECT full_name FROM tbladmission_addstudent WHERE Admission_ID = ?");
    if ($stmt === false) {
        die("Error preparing query: " . $conn->error);
    }
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->bind_result($student_name);
    
    if ($stmt->fetch()) {
        // Successfully fetched student name
    } else {
        echo "No student found with this ID.";
    }
    $stmt->close();

    // Get student grades from student_grades table
    $stmt = $conn->prepare("SELECT subject, grade, remarks FROM student_grades WHERE student_id = ? ORDER BY subject ASC");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $grades[] = $row;
    }
    $stmt->close();
} else {
    die("Invalid or missing student ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transcript of Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef7ff;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #007bff;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
        }

        a:hover {
            background-color: #0056b3;
        }

        .no-records {
            text-align: center;
            margin-top: 30px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Transcript of Records</h2>
    <h3>Student Name: <?= htmlspecialchars($student_name) ?></h3>

    <?php if (count($grades) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?= htmlspecialchars($grade['subject']) ?></td>
                        <td><?= htmlspecialchars($grade['grade']) ?></td>
                        <td><?= htmlspecialchars($grade['remarks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-records">No grades found for this student.</p>
    <?php endif; ?>

    <a href="/International-State-College-of-the-Philippines/Registrar/registrar.php?page=grades_transcripts">Back</a>
</div>

</body>
</html>
