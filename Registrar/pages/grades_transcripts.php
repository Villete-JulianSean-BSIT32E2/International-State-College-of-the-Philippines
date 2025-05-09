<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$students = [];
$result = $conn->query("SELECT id, name FROM admission ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grades & Transcripts</title>
    <style>

        h2 {
            color: #333;
        }
        form {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
        }
        select {
            padding: 5px;
            font-size: 14px;
            margin-left: 10px;
        }
        button {
            padding: 6px 12px;
            font-size: 14px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 4px;
            margin-left: 10px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .actions a {
            padding: 8px 14px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 10px;
            display: inline-block;
        }
        .actions a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Grades & Transcripts</h2>
<p>Select a student to view or edit grades and transcripts.</p>

<form method="GET" action="pages/edit_grades.php">

    <label for="student_id">Select Student:</label>
    <select name="student_id" id="student_id" required>
        <option value="">-- Select Student --</option>
        <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>"><?= htmlspecialchars($student['name']) ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Go</button>
</form>

<div class="actions">
    <a href="pages/add_grades.php">➕ Add Student Grades</a>
</div>

</body>
</html>
