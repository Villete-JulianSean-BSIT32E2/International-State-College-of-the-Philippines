<?php
include 'connect.php';

$students = [];
$new_count = $old_count = $irregular_count = 0;

if ($conn && !$conn->connect_error) {
    // Get all students with their student type
    $result = $conn->query("
        SELECT a.Admission_ID, a.full_name, a.course, a.applying_grade, s.StudentType
        FROM tbladmission_addstudent a
        LEFT JOIN tbladmission_studenttype s ON a.Admission_ID = s.Admission_ID
        ORDER BY a.Admission_ID DESC
    ");

    if ($result) {
        $students = $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get counts for each student type
    $type_counts = $conn->query("
        SELECT 
            SUM(CASE WHEN StudentType = 'new' THEN 1 ELSE 0 END) as new_count,
            SUM(CASE WHEN StudentType = 'old' THEN 1 ELSE 0 END) as old_count,
            SUM(CASE WHEN StudentType = 'irregular' THEN 1 ELSE 0 END) as irregular_count
        FROM tbladmission_studenttype
    ");

    if ($type_counts && $row = $type_counts->fetch_assoc()) {
      $new_count = isset($row['new_count']) ? $row['new_count'] : 0;
      $old_count = isset($row['old_count']) ? $row['old_count'] : 0;
      $irregular_count = isset($row['irregular_count']) ? $row['irregular_count'] : 0;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admission Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    * { box-sizing: border-box; font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
    body { display: flex; background-color: #f6f8fb; min-height: 100vh; }
    .sidebar { width: 240px; background-color: #0b2a5b; color: white; height: 100vh; padding: 1rem; }
    .sidebar img { width: 80px; margin-bottom: 1rem; }
    .nav-item { padding: 12px; margin: 10px 0; border-radius: 8px; cursor: pointer; transition: background-color 0.3s; }
    .nav-item.active, .nav-item:hover { background-color: #1a3d7c; }
    .main-content { flex: 1; padding: 2rem; display: flex; flex-direction: column; align-items: center; }
    .overview {
      background-color: #0b2a5b;
      color: white;
      border-radius: 10px;
      padding: 2rem;
      display: flex;
      justify-content: space-around;
      width: 80%;
      margin-bottom: 2rem;
      cursor: pointer;
    }
    .overview div { text-align: center; padding: 10px; border-radius: 8px; }
    .overview div:hover { background-color: #1a3d7c; }
    .overview .count { font-size: 2rem; font-weight: bold; }
    .add-btn {
      background-color: #0b2a5b;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      margin-bottom: 1.5rem;
      transition: background-color 0.3s;
    }
    .add-btn:hover { background-color: #1a3d7c; }
    .table-container {
      width: 80%;
      overflow-x: auto;
      background: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table { width: 100%; border-collapse: collapse; }
    .table thead { background-color: #0b2a5b; color: white; }
    .table th, .table td {
      padding: 14px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }
    .table tbody tr:hover { background-color: #f0f6ff; }
    .action-btn {
      background-color: #0b2a5b;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
    }
    .action-btn:hover { background-color: #1a3d7c; }

    .dropdown {
        position: relative;
        display: inline-block;
    }
    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }
    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }
    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }
    .dropdown:hover .dropdown-content {
        display: block;
    }
    .sidebar a.nav-item {
  color: white;
  text-decoration: none;
  display: block;
}
.sidebar a.nav-item:hover,
.sidebar a.nav-item.active {
  background-color: #1a3d7c;
}

  </style>
</head>
<body>
  <div class="sidebar">
    <img src="img/LOGO.png" alt="College Logo" />
    <div class="nav-item active">Admission</div>
    <a href="main-dashboard.php" class="nav-item">Main Dashboard</a>


  </div>

  <div class="main-content">
    <div class="overview">
      <div onclick="filterTable('all')">
        <div>Total Applicants</div>
        <div class="count"><?= count($students) ?></div>
      </div>
      <div onclick="filterTable('new')">
        <div>New Students</div>
        <div class="count"><?= $new_count ?></div>
      </div>
      <div onclick="filterTable('old')">
        <div>Old Students</div>
        <div class="count"><?= $old_count ?></div>
      </div>
      <div onclick="filterTable('irregular')">
        <div>Irregular</div>
        <div class="count"><?= $irregular_count ?></div>
      </div>
    </div>

    <div style="display: flex; gap: 15px; margin-bottom: 1.5rem;">
    <a href="Admission-AddStudent.php">
        <button class="add-btn">+ Add Student</button>
    </a>
    <div class="dropdown">
        <button class="add-btn">📄 Get Report</button>
        <div class="dropdown-content">
            <a href="report.php?type=pdf">PDF Report</a>
            <a href="report.php?type=excel">Excel Report</a>
            <a href="report.php?type=html">HTML Report</a>
        </div>
    </div>
</div>
    <div class="table-container">
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Grade/Year Level</th>
            <th>Student Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $student): 
              $typeLabels = [
                  'new' => 'New Student',
                  'old' => 'Returning Student',
                  'irregular' => 'Irregular Student'
              ];
              $studentType = isset($student['StudentType']) ? $student['StudentType'] : 'new';
          ?>
            <tr data-status="<?= htmlspecialchars($studentType) ?>">
              <td><?= htmlspecialchars($student['full_name']) ?></td>
              <td><?= htmlspecialchars($student['course']) ?></td>
              <td><?= htmlspecialchars($student['applying_grade']) ?></td>
              <td><?= $typeLabels[$studentType] ?></td>
              <td>Pending</td>
              <td>
                <a href="Admission-View.php?id=<?= $student['Admission_ID'] ?>">
                  <button class="action-btn">View Details</button>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function filterTable(status) {
      const rows = document.querySelectorAll('tbody tr');
      rows.forEach(row => {
        row.style.display = (status === 'all' || row.getAttribute('data-status') === status) ? '' : 'none';
      });
    }
  </script>
</body>
</html>