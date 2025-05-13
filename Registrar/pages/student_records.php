<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if student_documents table exists, create if not
$table_check = $conn->query("SHOW TABLES LIKE 'student_documents'");
if ($table_check->num_rows == 0) {
    $conn->query("CREATE TABLE student_documents (
        id INT AUTO_INCREMENT PRIMARY KEY,
        Admission_ID INT NOT NULL UNIQUE,
        birth_cert TINYINT(1) DEFAULT 0,
        form137 TINYINT(1) DEFAULT 0,
        tor TINYINT(1) DEFAULT 0,
        good_moral TINYINT(1) DEFAULT 0,
        honorable_dismissal TINYINT(1) DEFAULT 0,
        FOREIGN KEY (Admission_ID) REFERENCES tbladmission_addstudent(Admission_ID)
    )");
}

// Get search term if exists
$search_term = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Get all students with document status
$sql = "SELECT 
            a.Admission_ID as id, 
            a.full_name as name, 
            a.course,
            d.birth_cert,
            d.form137,
            d.tor,
            d.good_moral,
            d.honorable_dismissal
        FROM tbladmission_addstudent a
        LEFT JOIN student_documents d ON a.Admission_ID = d.Admission_ID";

if (!empty($search_term)) {
    $sql .= " WHERE a.full_name LIKE '%$search_term%'";
}

$sql .= " ORDER BY a.full_name ASC";

$students_result = $conn->query($sql);
?>

<div class="container mt-4">
    <h2>📁 Student Records</h2>

    <!-- Search by Name -->
    <form method="GET" action="registrar.php" style="margin-bottom: 15px;">
        <input type="hidden" name="page" value="student_records">
        <label><strong>Search Students:</strong></label>
        <input type="text" name="search" placeholder="Enter student name" 
               value="<?php echo htmlspecialchars($search_term); ?>" style="padding: 5px; width: 250px;">
        <button type="submit" style="padding: 5px 15px; background: #0b2a5b; color: white; border: none;">Search</button>
        <?php if (!empty($search_term)): ?>
            <a href="registrar.php?page=student_records" style="margin-left: 10px;">Clear Search</a>
        <?php endif; ?>
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
            <?php if ($students_result && $students_result->num_rows > 0): ?>
                <?php while ($student = $students_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['course']); ?></td>
                        <td><span class="<?php echo (isset($student['birth_cert']) && $student['birth_cert']) ? 'cleared' : 'pending'; ?>">
    <?php echo (isset($student['birth_cert']) && $student['birth_cert']) ? 'Cleared' : 'Pending'; ?>
</span></td>
<td><span class="<?php echo (isset($student['form137']) && $student['form137']) ? 'cleared' : 'pending'; ?>">
    <?php echo (isset($student['form137']) && $student['form137']) ? 'Cleared' : 'Pending'; ?>
</span></td>
<td><span class="<?php echo (isset($student['tor']) && $student['tor']) ? 'cleared' : 'pending'; ?>">
    <?php echo (isset($student['tor']) && $student['tor']) ? 'Cleared' : 'Pending'; ?>
</span></td>
<td><span class="<?php echo (isset($student['good_moral']) && $student['good_moral']) ? 'cleared' : 'pending'; ?>">
    <?php echo (isset($student['good_moral']) && $student['good_moral']) ? 'Cleared' : 'Pending'; ?>
</span></td>
<td><span class="<?php echo (isset($student['honorable_dismissal']) && $student['honorable_dismissal']) ? 'cleared' : 'pending'; ?>">
    <?php echo (isset($student['honorable_dismissal']) && $student['honorable_dismissal']) ? 'Cleared' : 'Pending'; ?>
</span>
<td>
    <a href="registrar.php?page=edit_student_documents&id=<?= isset($student['id']) ? $student['id'] : '' ?>" 
       class="btn-edit" 
       style="background: #0b2a5b; color: white; padding: 3px 8px; text-decoration: none; border-radius: 3px;">
        Edit
    </a>
</td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">
                        <?php echo empty($search_term) ? 'No student records found.' : 'No students match your search.'; ?>
                    </td>
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
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }
    .table-light {
        background-color: #f8f9fa;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
    .text-center {
        text-align: center;
    }
    a {
        color: #0b90d0;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>