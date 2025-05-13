<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$student_id = intval($_GET['id']);

// Ensure the clearance row exists only if it doesn't already
$check = $conn->query("SELECT 1 FROM student_clearance WHERE student_id = $student_id");
if ($check->num_rows === 0) {
    $conn->query("INSERT INTO student_clearance (student_id) VALUES ($student_id)");
}

// Fetch student info
$student_stmt = $conn->prepare("SELECT full_name FROM tbladmission_addstudent WHERE Admission_ID = ?");
$student_stmt->bind_param("i", $student_id);
$student_stmt->execute();
$student_result = $student_stmt->get_result();
$student = $student_result->fetch_assoc();
$student_stmt->close();

// Fetch clearance info
$clearance_stmt = $conn->prepare("SELECT * FROM student_clearance WHERE student_id = ?");
$clearance_stmt->bind_param("i", $student_id);
$clearance_stmt->execute();
$clearance_result = $clearance_stmt->get_result();
$clearance_data = $clearance_result->fetch_assoc();
$clearance = is_array($clearance_data) ? $clearance_data : [
    'library_clearance' => 'Pending',
    'accounting_clearance' => 'Pending',
    'dept_head_clearance' => 'Pending',
    'final_clearance' => 'Pending'
];

$clearance_stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $library = $_POST['library_clearance'];
    $accounting = $_POST['accounting_clearance'];
    $dept = $_POST['dept_head_clearance'];
    $final = $_POST['final_clearance'];

    $stmt = $conn->prepare("
        UPDATE student_clearance 
        SET library_clearance = ?, accounting_clearance = ?, dept_head_clearance = ?, final_clearance = ?
        WHERE student_id = ?
    ");
    $stmt->bind_param("ssssi", $library, $accounting, $dept, $final, $student_id);
    $stmt->execute();
    $stmt->close();

    header("Location: registrar.php?page=clearance");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Clearance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eaf6fb;
            margin: 20px;
        }

        h2 {
            color: #2980b9;
        }

        form {
            max-width: 400px;
            background: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
            color: #2c3e50;
        }

        select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            margin-top: 20px;
            cursor: pointer;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h2>Edit Clearance for <?= htmlspecialchars($student['full_name']) ?></h2>

<form method="POST">
    <label>Library Clearance:</label>
    <select name="library_clearance" required>
        <option value="Pending" <?= $clearance['library_clearance'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Cleared" <?= $clearance['library_clearance'] === 'Cleared' ? 'selected' : '' ?>>Cleared</option>
    </select>

    <label>Accounting Clearance:</label>
    <select name="accounting_clearance" required>
        <option value="Pending" <?= $clearance['accounting_clearance'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Cleared" <?= $clearance['accounting_clearance'] === 'Cleared' ? 'selected' : '' ?>>Cleared</option>
    </select>

    <label>Department Head Clearance:</label>
    <select name="dept_head_clearance" required>
        <option value="Pending" <?= $clearance['dept_head_clearance'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Cleared" <?= $clearance['dept_head_clearance'] === 'Cleared' ? 'selected' : '' ?>>Cleared</option>
    </select>

    <label>Final Clearance:</label>
    <select name="final_clearance" required>
        <option value="Pending" <?= $clearance['final_clearance'] === 'Pending' ? 'selected' : '' ?>>Pending</option>
        <option value="Cleared" <?= $clearance['final_clearance'] === 'Cleared' ? 'selected' : '' ?>>Cleared</option>
    </select>

    <button type="submit">Save Changes</button>
</form>

<a class="back-link" href="registrar.php?page=clearance">← Back to Clearance</a>

</body>
</html>
