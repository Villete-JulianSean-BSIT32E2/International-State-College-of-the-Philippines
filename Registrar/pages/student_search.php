<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResults = [];

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';


    $sql = "SELECT 
                admission.id, admission.name, admission.email, admission.gender,
                student_documents.course, student_documents.applying_grade
            FROM admission
            LEFT JOIN student_documents ON admission.id = student_documents.id
            WHERE admission.name LIKE '%$search%' OR admission.email LIKE '%$search%'
            LIMIT 20";

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        // Fill empty course/grade with 'N/A' if not present
        $row['course'] = isset($row['course']) ? $row['course'] : 'N/A';
        $row['applying_grade'] = isset($row['applying_grade']) ? $row['applying_grade'] : 'N/A';
        

        $searchResults[] = $row;
    }
}
?>


<div class="container">
    <h3 class="mb-4">🔍 Student Search</h3>

    <form method="GET" action="registrar.php" class="mb-4 d-flex gap-2">
        <input type="hidden" name="page" value="student_search">
        <input type="text" name="q" class="form-control" placeholder="Search by name or email" value="<?= htmlspecialchars(isset($_GET['q']) ? $_GET['q'] : '') ?>">

        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if (!empty($_GET['q'])): ?>
        <h5 class="mb-3">Results for "<?= htmlspecialchars($_GET['q']) ?>"</h5>
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
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td><?= htmlspecialchars($student['email']) ?></td>
                            <td><?= htmlspecialchars($student['gender']) ?></td>
                            <td><?= htmlspecialchars($student['course']) ?></td>
                            <td><?= htmlspecialchars($student['applying_grade']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</div>
