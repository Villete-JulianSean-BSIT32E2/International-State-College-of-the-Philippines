<?php
include 'connect.php';

$type = isset($_GET['type']) ? strtolower($_GET['type']) : 'html';
if (!in_array($type, ['pdf', 'excel', 'html'])) {
    $type = 'html';
}

// Get student data
$query = "
    SELECT a.*, s.StudentType 
    FROM tbladmission_addstudent a
    LEFT JOIN tbladmission_studenttype s ON a.Admission_ID = s.Admission_ID
    ORDER BY a.Admission_ID DESC
";
$result = $conn->query($query);

// Excel Report (as real CSV)
if ($type === 'excel') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="students_'.date('Ymd').'.csv"');
    
    $output = fopen('php://output', 'w');
    
    // Header row
    fputcsv($output, [
        'ID', 
        'Full Name', 
        'Course', 
        'Year Level', 
        'Student Type',
        'Phone',
        'Email'
    ]);
    
    // Data rows
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['Admission_ID'],
            $row['full_name'],
            $row['course'],
            $row['applying_grade'],
            ucfirst($row['StudentType']),
            $row['phone'],
            $row['email']
        ]);
    }
    
    fclose($output);
    exit();
}

// PDF Report (as downloadable HTML)
elseif ($type === 'pdf') {
    header('Content-Type: application/html');
    header('Content-Disposition: attachment; filename="students_'.date('Ymd').'.html"');
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Student Report</title>
        <style>
            body { font-family: Arial; margin: 0; padding: 20px; }
            h1 { color: #0b2a5b; text-align: center; font-size: 24px; }
            .report-info { text-align: center; margin-bottom: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th { background-color: #0b2a5b; color: white; padding: 8px; text-align: left; }
            td { padding: 8px; border-bottom: 1px solid #ddd; }
            @page { size: A4; margin: 10mm; }
        </style>
    </head>
    <body>
        <h1>International State College of the Philippines</h1>
        <div class="report-info">
            Student Admission Report | Generated: <?= date('F j, Y') ?>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['Admission_ID']) ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['applying_grade']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($row['StudentType'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <script>
            // Auto-print when opened
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 500);
            };
        </script>
    </body>
    </html>
    <?php
    exit();
}

// HTML Report (default view)
else {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Student Report</title>
        <style>
            body { font-family: Arial; margin: 20px; }
            .header { display: flex; justify-content: space-between; align-items: center; }
            .export-options { margin: 20px 0; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
            th { background-color: #0b2a5b; color: white; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Student Admission Report</h1>
            <div class="export-options">
                <a href="report.php?type=pdf" class="btn">Download PDF</a>
                <a href="report.php?type=excel" class="btn">Download Excel</a>
            </div>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Type</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['Admission_ID'] ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['applying_grade']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($row['StudentType'])) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </body>
    </html>
    <?php
}
?>