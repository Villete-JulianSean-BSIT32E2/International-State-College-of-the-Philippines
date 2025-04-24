<?php
include 'connect.php';

$students = [];
$new_count = $transferee_count = $irregular_count = $old_count = 0;

if ($conn && !$conn->connect_error) {
  // Join admission and student_documents by id
  $result = $conn->query("
    SELECT a.name, d.Course, d.applying_grade
    FROM admission a
    INNER JOIN student_documents d ON a.id = d.id
    ORDER BY a.id DESC
  ");

  if ($result) {
    $students = $result->fetch_all(MYSQLI_ASSOC);
  }

  // Count for each category
  $tables = [
    'new' => &$new_count,
    'transferee' => &$transferee_count,
    'irregular' => &$irregular_count,
    'old' => &$old_count
  ];

  foreach ($tables as $table => &$count) {
    $res = $conn->query("SELECT COUNT(*) as total FROM `$table`");
    if ($res && $row = $res->fetch_assoc()) {
      $count = $row['total'] ?? 0;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Student Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      background-color: #f6f8fb;
    }

    .sidebar {
      width: 240px;
      background-color: #13334D;
      color: white;
      height: 100vh;
      padding: 1rem;
    }

    .sidebar img {
      width: 80px;
      margin-bottom: 1rem;
    }

    .nav-item {
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      cursor: pointer;
    }

    .nav-item.active,
    .nav-item:hover {
      background-color: #3e6df3;
    }

    .main-content {
      flex: 1;
      padding: 2rem;
    }

    .overview {
      background-color: #13235b;
      color: white;
      border-radius: 10px;
      padding: 2rem;
      display: flex;
      justify-content: space-around;
      margin-bottom: 2rem;
    }

    .overview div {
      text-align: center;
    }

    .overview .count {
      font-size: 2rem;
      font-weight: bold;
    }

    .add-btn {
      background-color: #3e6df3;
      border: none;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      cursor: pointer;
      float: right;
      margin-bottom: 1rem;
    }

    .table {
      width: 100%;
      border-collapse: collapse;
    }

    .table thead {
      background-color: #BCBCBC;
    }

    .table th,
    .table td {
      padding: 12px;
      text-align: left;
    }

    .table tbody tr:nth-child(even) {
      background-color: #f2f8ff;
    }

    .table td input {
      border: none;
      background: transparent;
      font-weight: bold;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="sidebar">
    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Seal_of_the_International_State_College_of_the_Philippines.svg/800px-Seal_of_the_International_State_College_of_the_Philippines.svg.png" alt="Logo" />
    <div class="nav-item active">Admission</div>
    <div class="nav-item">Registrar</div>
    <div class="nav-item">Cashier</div>
    <div class="nav-item">Settings and Profile</div>
    <div class="nav-item">Exams</div>
  </div>

  <div class="main-content">
    <div class="overview">
      <div><div>Total Students</div><div class="count"><?= count($students) ?></div></div>
      <div><div>Transferee</div><div class="count"><?= $transferee_count ?></div></div>
      <div><div>New Students</div><div class="count"><?= $new_count ?></div></div>
      <div><div>Irregular</div><div class="count"><?= $irregular_count ?></div></div>
      <div><div>Old Students</div><div class="count"><?= $old_count ?></div></div>
    </div>

    <a href="addstudent.html">
      <button class="add-btn">+ Add Student</button>
    </a>

    <!-- Combined Table -->
    <table class="table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Course</th>
          <th>Year Level</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($students as $student): ?>
        <tr>
          <td><input type="text" value="<?= htmlspecialchars($student['name']) ?>" readonly></td>
          <td><input type="text" value="<?= htmlspecialchars($student['Course']) ?>" readonly></td>
          <td><input type="text" value="<?= htmlspecialchars($student['applying_grade']) ?>" readonly></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
