<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare SQL with optional search filter
$sql = "
    SELECT 
        Admission_ID, 
        full_name, 
        course, 
        student_type
    FROM tbladmission_addstudent
";

if (!empty($search_term)) {
    $sql .= " WHERE full_name LIKE ?";
    $search_term = "%" . $search_term . "%";
}

$sql .= " ORDER BY Admission_ID DESC";

// Prepare and execute query
$stmt = $conn->prepare($sql);
if (!empty($search_term)) {
    $stmt->bind_param("s", $search_term);
}
$stmt->execute();
$result = $stmt->get_result();
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
        input[type="text"] {
            padding: 8px 15px;
            font-size: 14px;
            width: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 8px 15px;
            font-size: 14px;
            background-color: #0b90d0;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0a7bb9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Enrollment Management</h2>
    <p>Manage enrolled students and their statuses.</p>

    <!-- Search Form -->
    <form method="GET" action="registrar.php" class="filter-form">
        <input type="hidden" name="page" value="enrollment_management">
        <label><strong>Search by Name:</strong></label>
        <input type="text" name="search" placeholder="Enter student name..." value="<?= htmlspecialchars($search_term) ?>">
        <button type="submit">Search</button>
        <?php if (!empty($search_term)): ?>
            <a href="registrar.php?page=enrollment_management" style="margin-left: 10px;">Clear Search</a>
        <?php endif; ?>
    </form>

    <!-- Student Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Course</th>
                <th>Student Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($student = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['full_name']) ?></td>
                        <td><?= htmlspecialchars(isset($student['course']) ? $student['course'] : 'N/A') ?></td>
                        <td><?= htmlspecialchars(isset($student['student_type']) ? $student['student_type'] : 'N/A') ?></td>
                        <td><a href="pages/edit_enrollment.php?student_id=<?= $student['Admission_ID'] ?>">Edit</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" class="no-data">No students found<?= !empty($search_term) ? ' matching "' . htmlspecialchars($search_term) . '"' : '' ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>