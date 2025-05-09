<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all distinct courses
$course_query = $conn->query("SELECT DISTINCT course FROM student_documents WHERE course IS NOT NULL");
$courses = [];
while ($row = $course_query->fetch_assoc()) {
    $courses[] = $row['course'];
}

// Get selected course from filter
$selected_course = isset($_GET['course']) ? $_GET['course'] : '';

// Build query with optional course filtering
$sql = "
    SELECT a.id, a.name, d.course, d.status_std
    FROM admission a
    LEFT JOIN student_documents d ON a.id = d.id
";

if ($selected_course !== '') {
    $sql .= " WHERE d.course = '" . $conn->real_escape_string($selected_course) . "'";
}

$sql .= " ORDER BY a.id DESC";
$students = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrollment Management</title>
    <style>
        h2, p {
            text-align: center;
            color: #0b3d91;
        }

        .container {
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 80, 120, 0.1);
            width: 90%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        thead {
            background-color: #cce7f7;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #d0e8f5;
        }

        tr:hover {
            background-color: #f1faff;
        }

        a {
            color: #0b90d0;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
        }

        .filter-form {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 5px 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enrollment Management</h2>
    <p>Manage enrolled students and their statuses.</p>

    <!-- Course Filter -->
   <form method="GET" action="registrar.php" style="margin-bottom: 15px;">
    <input type="hidden" name="page" value="enrollment_management">
    <label><strong>Filter by Course:</strong></label>
    <select name="course" onchange="this.form.submit()" style="padding: 5px;">
        <option value="">-- All Courses --</option>
        <?php foreach ($courses as $course): ?>
            <option value="<?= htmlspecialchars($course) ?>" <?= $selected_course === $course ? 'selected' : '' ?>>
                <?= htmlspecialchars($course) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>


    <!-- Student Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students && $students->num_rows > 0): ?>
                <?php while ($student = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['course'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($student['status_std'] ?? 'N/A') ?></td>
                        <td>
                            <a href="pages/edit_enrollment.php?student_id=<?= $student['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="no-data">No students found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
