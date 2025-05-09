<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get all distinct courses from student_documents
$course_query = $conn->query("SELECT DISTINCT course FROM student_documents WHERE course IS NOT NULL AND course != ''");
$courses = [];
while ($row = $course_query->fetch_assoc()) {
    $courses[] = $row['course'];
}

// Get selected course
$selected_course = isset($_GET['course']) ? $_GET['course'] : '';

// Build SQL with optional filtering
$sql = "
    SELECT a.id, a.name, c.library_clearance, c.accounting_clearance, 
           c.dept_head_clearance, c.final_clearance, d.course
    FROM admission a
    LEFT JOIN student_clearance c ON a.id = c.student_id
    LEFT JOIN student_documents d ON a.id = d.id
";

if ($selected_course !== '') {
    $sql .= " WHERE d.course = '" . $conn->real_escape_string($selected_course) . "'";
}

$students = $conn->query($sql);
?>


<style>
    
        a {
            color: #0b90d0;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
</style>

<h2>Student Clearance</h2>

<!-- 🔍 Filter by Course -->
<form method="GET" action="registrar.php" style="margin-bottom: 15px;">
    <input type="hidden" name="page" value="clearance">
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

<!-- 📋 Clearance Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #d9edf7;">
        <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Library</th>
            <th>Accounting</th>
            <th>Dept. Head</th>
            <th>Final</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($students->num_rows === 0): ?>
            <tr><td colspan="7" style="text-align: center;">No students found.</td></tr>
        <?php else: ?>
            <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['course'] ?? 'N/A') ?></td>
                <td><span class="<?= $row['library_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
                    <?= $row['library_clearance'] ?? 'Pending' ?></span></td>
                <td><span class="<?= $row['accounting_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
                    <?= $row['accounting_clearance'] ?? 'Pending' ?></span></td>
                <td><span class="<?= $row['dept_head_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
                    <?= $row['dept_head_clearance'] ?? 'Pending' ?></span></td>
                <td><span class="<?= $row['final_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
                    <?= $row['final_clearance'] ?? 'Pending' ?></span></td>
                <td><a href="registrar.php?page=edit_clearance&id=<?= $row['id'] ?>">Update</a></td>
            </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- ✅ Status Styles -->
<style>
    .cleared {
        color: green;
        font-weight: bold;
    }
    .pending {
        color: red;
        font-weight: bold;
    }
</style>
