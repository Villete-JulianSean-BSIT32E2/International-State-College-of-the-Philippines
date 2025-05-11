<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResults = array(); // Changed to array() for PHP 5.x compatibility

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search = $conn->real_escape_string($_GET['q']);

    $sql = "SELECT 
                a.Admission_ID as id, 
                a.full_name as name, 
                a.email, 
                a.gender,
                a.course, 
                a.applying_grade
            FROM tbladmission_addstudent a
            WHERE a.full_name LIKE '%$search%' OR a.email LIKE '%$search%'
            LIMIT 20";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Fill empty course/grade with 'N/A' if not present
            $row['course'] = !empty($row['course']) ? $row['course'] : 'N/A';
            $row['applying_grade'] = !empty($row['applying_grade']) ? $row['applying_grade'] : 'N/A';
            
            $searchResults[] = $row;
        }
    }
}
?>

<div class="container">
    <h3 class="mb-4">🔍 Student Search</h3>

    <form method="GET" action="registrar.php" class="mb-4 d-flex gap-2">
        <input type="hidden" name="page" value="student_search">
        <input type="text" name="q" class="form-control" placeholder="Search by name or email" 
               value="<?php echo htmlspecialchars(isset($_GET['q']) ? $_GET['q'] : ''); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if (isset($_GET['q']) && !empty($_GET['q'])): ?>
        <h5 class="mb-3">Results for "<?php echo htmlspecialchars($_GET['q']); ?>"</h5>
        <?php if (empty($searchResults)): ?>
            <p>No students found.</p>
        <?php else: ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Course</th>
                        <th>Year Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($searchResults as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td><?php echo htmlspecialchars($student['gender']); ?></td>
                            <td><?php echo htmlspecialchars($student['course']); ?></td>
                            <td><?php echo htmlspecialchars($student['applying_grade']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>