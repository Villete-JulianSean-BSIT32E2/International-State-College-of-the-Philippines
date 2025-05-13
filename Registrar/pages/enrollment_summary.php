<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total students from tbladmission_addstudent
$total_students = $conn->query("SELECT COUNT(*) AS total FROM tbladmission_addstudent")->fetch_assoc()['total'];

// Get course summary from tbladmission_addstudent
$course_summary = $conn->query("
    SELECT course, COUNT(*) AS total 
    FROM tbladmission_addstudent 
    WHERE course IS NOT NULL AND course != ''
    GROUP BY course
");

// Get status summary (using StudentType from tbladmission_studenttype)
$status_summary = $conn->query("
    SELECT s.StudentType AS status_std, COUNT(*) AS total
    FROM tbladmission_addstudent a
    JOIN tbladmission_studenttype s ON a.Admission_ID = s.Admission_ID
    GROUP BY s.StudentType
");
?>

<div class="container mt-4">
    <h2>📝 Enrollment Summary</h2>
    <p>Overview of enrollment statistics.</p>

    <div class="card p-3 my-3">
        <h4>Total Students: <?php echo $total_students; ?></h4>
    </div>

    <div class="card p-3 my-3">
        <h5>📚 Students by Course</h5>
        <ul>
            <?php while ($row = $course_summary->fetch_assoc()): ?>
                <li><strong><?php echo htmlspecialchars($row['course']); ?>:</strong> <?php echo $row['total']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="card p-3 my-3">
        <h5>📌 Students by Status</h5>
        <ul>
            <?php while ($row = $status_summary->fetch_assoc()): ?>
                <li><strong><?php echo htmlspecialchars(ucfirst($row['status_std'])); ?>:</strong> <?php echo $row['total']; ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>