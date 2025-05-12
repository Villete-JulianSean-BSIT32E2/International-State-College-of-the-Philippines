<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get search term if exists
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch students with optional search
$sql = "SELECT Admission_ID, full_name, course FROM tbladmission_addstudent";

if (!empty($search_term)) {
    $sql .= " WHERE full_name LIKE ?";
    $search_term_param = "%$search_term%";
}

$sql .= " ORDER BY full_name ASC";

$stmt = $conn->prepare($sql);
if (!empty($search_term)) {
    $stmt->bind_param("s", $search_term_param);
}
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grades & Transcripts</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
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
        .btn-edit, .btn-view {
            padding: 6px 12px;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
            margin: 0 3px;
            display: inline-block;
        }
        .btn-edit {
            background-color: #28a745;
        }
        .btn-edit:hover {
            background-color: #218838;
        }
        .btn-view {
            background-color: #17a2b8;
        }
        .btn-view:hover {
            background-color: #138496;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #888;
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .actions a {
            padding: 8px 14px;
            background-color: #0b90d0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .actions a:hover {
            background-color: #0a7bb9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Grades & Transcripts</h2>
    <p>Manage student grades and transcripts.</p>

    <!-- Search Form -->
    <form method="GET" action="registrar.php" class="filter-form">
        <input type="hidden" name="page" value="grades_transcripts">
        <label><strong>Search by Name:</strong></label>
        <input type="text" name="search" placeholder="Enter student name..." value="<?= htmlspecialchars($search_term) ?>">
        <button type="submit">Search</button>
        <?php if (!empty($search_term)): ?>
            <a href="registrar.php?page=grades_transcripts" style="margin-left: 10px;">Clear Search</a>
        <?php endif; ?>
    </form>

    <!-- Student Table -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Course</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)): ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?= htmlspecialchars($student['full_name']) ?></td>
                        <td><?= htmlspecialchars(isset($student['course']) ? $student['course'] : 'N/A') ?></td>
                        <td>
                            <a href="pages/edit_grades.php?student_id=<?= $student['Admission_ID'] ?>" class="btn-edit">Edit</a>
                            <a href="pages/view_transcript.php?student_id=<?= $student['Admission_ID'] ?>" class="btn-view">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" class="no-data">No students found<?= !empty($search_term) ? ' matching "' . htmlspecialchars($search_term) . '"' : '' ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
