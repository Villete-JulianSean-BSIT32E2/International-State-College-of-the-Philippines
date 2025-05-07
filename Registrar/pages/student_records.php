<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT 
        a.name, 
        d.birth_cert_path, 
        d.form137_path, 
        d.tor_path, 
        d.good_moral_path, 
        d.honorable_dismissal_path
    FROM admission a
    LEFT JOIN student_documents d ON a.id = d.id
    ORDER BY a.name ASC
";

$result = $conn->query($sql);
?>

<div class="container mt-4">
    <h2>📁 Student Records</h2>
    <p>List of student records and uploaded documents:</p>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Birth Certificate</th>
                <th>Form 137</th>
                <th>TOR</th>
                <th>Good Moral</th>
                <th>Honorable Dismissal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= !empty($row['birth_cert_path']) ? '✅' : '❌' ?></td>
                        <td><?= !empty($row['form137_path']) ? '✅' : '❌' ?></td>
                        <td><?= !empty($row['tor_path']) ? '✅' : '❌' ?></td>
                        <td><?= !empty($row['good_moral_path']) ? '✅' : '❌' ?></td>
                        <td><?= !empty($row['honorable_dismissal_path']) ? '✅' : '❌' ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No student records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
