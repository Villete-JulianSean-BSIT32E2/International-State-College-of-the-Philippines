<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch students
$students = $conn->query("
   SELECT a.id, a.name, d.course, d.status_std
   FROM admission a
   LEFT JOIN student_documents d ON a.id = d.id
   ORDER BY a.id DESC
");
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
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 80, 120, 0.1);
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
    </style>
</head>
<body>

<div class="container">
    <h2>Enrollment Management</h2>
    <p>Manage enrolled students and their statuses.</p>

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
            <?php if ($students->num_rows > 0): ?>
                <?php while ($student = $students->fetch_assoc()): ?>
                    <tr>
                        <td><?= $student['id'] ?></td>
                        <td><?= htmlspecialchars($student['name']) ?></td>
                        <td><?= htmlspecialchars($student['course']) ?></td>
                        <td><?= htmlspecialchars($student['status_std']) ?></td>
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
