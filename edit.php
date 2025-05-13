<?php
include 'connect.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("No Admission ID specified.");
}

$admission_id = intval($_GET['id']);
$student = [];

// Fetch student data
$sql = "
    SELECT a.full_name, a.course, a.applying_grade, a.status, s.StudentType
    FROM tbladmission_addstudent a
    LEFT JOIN tbladmission_studenttype s ON a.Admission_ID = s.Admission_ID
    WHERE a.Admission_ID = $admission_id
";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    die("Student not found.");
}

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $course = $conn->real_escape_string($_POST['course']);
    $applying_grade = $conn->real_escape_string($_POST['applying_grade']);
    $student_type = $conn->real_escape_string($_POST['student_type']);

    // Update tbladmission_addstudent
    $update1 = $conn->query("
        UPDATE tbladmission_addstudent 
        SET full_name='$full_name', course='$course', applying_grade='$applying_grade' 
        WHERE Admission_ID = $admission_id
    ");

    // Update tbladmission_studenttype
    $exists = $conn->query("SELECT * FROM tbladmission_studenttype WHERE Admission_ID = $admission_id");
    if ($exists->num_rows > 0) {
        $update2 = $conn->query("
            UPDATE tbladmission_studenttype 
            SET StudentType='$student_type' 
            WHERE Admission_ID = $admission_id
        ");
    } else {
        $update2 = $conn->query("
            INSERT INTO tbladmission_studenttype (Admission_ID, StudentType)
            VALUES ($admission_id, '$student_type')
        ");
    }

    if ($update1 && $update2) {
        echo "<script>alert('Student record updated successfully.'); window.location.href='admission.php';</script>";
        exit;
    } else {
        echo "<script>alert('Update failed.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Student</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        background: #f6f8fb;
        padding: 2rem;
    }
    .form-container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 1.5rem;
    }
    label {
        display: block;
        margin-top: 1rem;
        font-weight: bold;
    }
    input, select {
        width: 100%;
        padding: 0.5rem;
        margin-top: 0.5rem;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    button {
        margin-top: 2rem;
        background-color: #0b2a5b;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
    }
    button:hover {
        background-color: #1a3d7c;
    }
    a.back-link {
        display: inline-block;
        margin-top: 1rem;
        color: #0b2a5b;
        text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Edit Student Record</h2>
    <form method="POST">
      <label for="full_name">Full Name</label>
      <input type="text" id="full_name" name="full_name" required value="<?= htmlspecialchars($student['full_name']) ?>">

      <label for="course">Course</label>
      <input type="text" id="course" name="course" required value="<?= htmlspecialchars($student['course']) ?>">

      <label for="applying_grade">Grade / Year Level</label>
      <input type="text" id="applying_grade" name="applying_grade" required value="<?= htmlspecialchars($student['applying_grade']) ?>">

      <label for="student_type">Student Type</label>
      <select id="student_type" name="student_type" required>
        <option value="new" <?= $student['StudentType'] == 'new' ? 'selected' : '' ?>>New Student</option>
        <option value="old" <?= $student['StudentType'] == 'old' ? 'selected' : '' ?>>Returning Student</option>
        <option value="irregular" <?= $student['StudentType'] == 'irregular' ? 'selected' : '' ?>>Irregular Student</option>
      </select>

      <button type="submit">Update Student</button>
    </form>
    <a class="back-link" href="admission.php">← Back to Admission Dashboard</a>
  </div>
</body>
</html>
