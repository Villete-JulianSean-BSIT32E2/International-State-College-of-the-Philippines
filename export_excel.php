<?php
include 'connect.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=student_report.xls");

echo "<table border='1'>";
echo "<tr>
        <th>Student Name</th>
        <th>Course</th>
        <th>Status</th>
        <th>Email</th>
        <th>Phone No</th>
        <th>Date Enrolled</th>
      </tr>";

$result = $conn->query("
    SELECT a.name, a.email, a.phoneno, s.Course, s.status_std, s.sigdate
    FROM admission a
    JOIN student_documents s ON a.id = s.id
    ORDER BY a.name
");

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['name']) . "</td>
            <td>" . htmlspecialchars($row['Course']) . "</td>
            <td>" . htmlspecialchars($row['status_std']) . "</td>
            <td>" . htmlspecialchars($row['email']) . "</td>
            <td>" . htmlspecialchars($row['phoneno']) . "</td>
            <td>" . htmlspecialchars($row['sigdate']) . "</td>
          </tr>";
}
echo "</table>";
?>
