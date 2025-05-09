<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all distinct courses
$course_query = $conn->query("SELECT DISTINCT course FROM student_documents WHERE course IS NOT NULL");
$courses = [];
while ($row = $course_query->fetch_assoc()) {
    $courses[] = $row['course'];
}

// Get selected course
$selected_course = isset($_GET['course']) ? $_GET['course'] : '';

// Build SQL with optional filtering
$sql = "
    SELECT 
        a.id,
        a.name, 
        d.birth_cert_path, 
        d.form137_path, 
        d.tor_path, 
        d.good_moral_path, 
        d.honorable_dismissal_path,
        d.course
    FROM admission a
    LEFT JOIN student_documents d ON a.id = d.id
";

if ($selected_course !== '') {
    $sql .= " WHERE d.course = '" . $conn->real_escape_string($selected_course) . "'";
}

$sql .= " ORDER BY a.name ASC";

$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h2>📁 Student Records</h2>

    <!-- Filter by Course -->
    <form method="GET" action="registrar.php" style="margin-bottom: 15px;">
    <input type="hidden" name="page" value="student_records">
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


    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Course</th>
                <th>Birth Certificate</th>
                <th>Form 137</th>
                <th>TOR</th>
                <th>Good Moral</th>
                <th>Honorable Dismissal</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['course'] ?? 'N/A') ?></td>
                        <td><span class="<?= !empty($row['birth_cert_path']) ? 'cleared' : 'pending' ?>">
                            <?= !empty($row['birth_cert_path']) ? 'Cleared' : 'Pending' ?></span></td>
                        <td><span class="<?= !empty($row['form137_path']) ? 'cleared' : 'pending' ?>">
                            <?= !empty($row['form137_path']) ? 'Cleared' : 'Pending' ?></span></td>
                        <td><span class="<?= !empty($row['tor_path']) ? 'cleared' : 'pending' ?>">
                            <?= !empty($row['tor_path']) ? 'Cleared' : 'Pending' ?></span></td>
                        <td><span class="<?= !empty($row['good_moral_path']) ? 'cleared' : 'pending' ?>">
                            <?= !empty($row['good_moral_path']) ? 'Cleared' : 'Pending' ?></span></td>
                        <td><span class="<?= !empty($row['honorable_dismissal_path']) ? 'cleared' : 'pending' ?>">
                            <?= !empty($row['honorable_dismissal_path']) ? 'Cleared' : 'Pending' ?></span></td>
                        <td>
                            <a href="registrar.php?page=edit_studentrecords&id=<?= $row['id'] ?>">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No student records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .cleared {
        color: green;
        font-weight: bold;
    }
    .pending {
        color: red;
        font-weight: bold;
    }

        a {
            color: #0b90d0;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
</style>
