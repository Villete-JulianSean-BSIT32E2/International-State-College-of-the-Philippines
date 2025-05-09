<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total students
$total_students = $conn->query("SELECT COUNT(*) AS total FROM admission")->fetch_assoc()['total'];

// Students per course
$courses = $conn->query("
    SELECT course, COUNT(*) AS count 
    FROM student_documents 
    WHERE course IS NOT NULL 
    GROUP BY course
");

// Students with final clearance
$cleared = $conn->query("
    SELECT COUNT(*) AS cleared 
    FROM student_clearance 
    WHERE final_clearance = 'Cleared'
")->fetch_assoc()['cleared'];

// Students with all documents submitted
$complete_docs = $conn->query("
    SELECT COUNT(*) AS complete 
    FROM student_documents 
    WHERE birth_cert_path != '' AND form137_path != '' AND tor_path != '' 
    AND good_moral_path != '' AND honorable_dismissal_path != ''
")->fetch_assoc()['complete'];
?>

<div class="container mt-4">
    <h2>📊 Reports</h2>
    <p>Overview of student statistics and academic status.</p>

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Students</h5>
                    <p class="card-text fs-4"><?= $total_students ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Cleared Students in Clearance</h5>
                    <p class="card-text fs-4"><?= $cleared ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Complete Records</h5>
                    <p class="card-text fs-4"><?= $complete_docs ?></p>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>

    <h4 class="mt-4">Students by Course</h4>
    <table class="table table-bordered table-striped mt-2">
        <thead class="table-light">
            <tr>
                <th>Course</th>
                <th>Number of Students</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= $row['count'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <br>
    <!-- Chart Container -->
<div class="mt-5">
    <h4>📈 Course Enrollment Chart</h4>
    <canvas id="courseChart" height="120"></canvas>
</div>

</div>


<!-- Load Chart.js from CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('courseChart').getContext('2d');
    const courseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column(iterator_to_array($courses = $conn->query("
                SELECT course, COUNT(*) AS count 
                FROM student_documents 
                WHERE course IS NOT NULL 
                GROUP BY course
            ")), 'course')) ?>,
            datasets: [{
                label: 'Number of Students',
                data: <?= json_encode(array_column(iterator_to_array($courses), 'count')) ?>,
                backgroundColor: '#2b81bb'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Students' }
                },
                x: {
                    title: { display: true, text: 'Course' }
                }
            }
        }
    });
</script>





