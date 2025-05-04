<?php
include 'connect.php';

$result = $conn->query("
    SELECT a.name, a.email, a.phoneno, s.Course, s.status_std, s.sigdate
    FROM admission a
    JOIN student_documents s ON a.id = s.id
    ORDER BY a.name
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Admission Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2 { text-align: center; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .buttons {
            margin: 20px 0;
            text-align: right;
        }
        .buttons button {
            padding: 8px 16px;
            margin-left: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Student Admission Report</h2>

<div class="buttons" style="display: flex; justify-content: space-between;">
    <a href="admission.php">
        <button>🔙 Back to Dashboard</button>
    </a>
    <div>
        <button onclick="window.print()">🖨️ Print Report</button>
        <button onclick="exportToPDF()">📄 Export to PDF</button>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Student Name</th>
            <th>Course</th>
            <th>Status</th>
            <th>Email</th>
            <th>Phone No</th>
            <th>Date Enrolled</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['Course']) ?></td>
                <td><?= htmlspecialchars($row['status_std']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phoneno']) ?></td>
                <td><?= htmlspecialchars($row['sigdate']) ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function exportToPDF() {
    const element = document.body;
    html2pdf().from(element).save('student_report.pdf');
}
</script>

</body>
</html>
