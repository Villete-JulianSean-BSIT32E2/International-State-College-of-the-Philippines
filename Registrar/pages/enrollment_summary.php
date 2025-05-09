<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$total_students = $conn->query("SELECT COUNT(*) AS total FROM admission")->fetch_assoc()['total'];

$course_summary = $conn->query("
    SELECT course, COUNT(*) AS total 
    FROM student_documents 
    WHERE course IS NOT NULL 
    GROUP BY course
");

$status_summary = $conn->query("
    SELECT status_std, COUNT(*) AS total 
    FROM student_documents 
    WHERE status_std IS NOT NULL 
    GROUP BY status_std
");
?>

<div class="container mt-4">
    <h2>📝 Enrollment Summary</h2>
    <p>Overview of enrollment statistics.</p>

    <div class="card p-3 my-3">
        <h4>Total Students: <?= $total_students ?></h4>
    </div>

    <div class="card p-3 my-3">
        <h5>📚 Students by Course</h5>
        <ul>
            <?php while ($row = $course_summary->fetch_assoc()): ?>
                <li><strong><?= htmlspecialchars($row['course']) ?>:</strong> <?= $row['total'] ?></li>
            <?php endwhile; ?>
        </ul>
    </div>

    <div class="card p-3 my-3">
        <h5>📌 Students by Status</h5>
        <ul>
            <?php while ($row = $status_summary->fetch_assoc()): ?>
                <li><strong><?= htmlspecialchars($row['status_std']) ?>:</strong> <?= $row['total'] ?></li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>
