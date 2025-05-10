<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$students = $conn->query("SELECT id, name FROM admission ORDER BY name ASC");
$courses = $conn->query("SELECT name FROM course ORDER BY name ASC");
$tuitionRecords = $conn->query("SELECT t.*, a.name FROM tuition t JOIN admission a ON t.student_id = a.id ORDER BY t.id DESC");
?>

<?php if (isset($_GET['success'])): ?>
  <p style="color: green;">Tuition record added successfully!</p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tuition</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f5f5f5;
    }

    /* Make the entire page a horizontal flex container */
    .layout {
      display: flex;
      min-height: 100vh; /* full height of viewport */
    }

    .sidebar {
      width: 200px;
      background: #112D4E;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }


    .nav-item {
      margin: 10px 0;
      padding: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-radius: 4px;
      transition: background 0.3s;
    }

    .nav-item:hover {
      background: #4267b2;
      cursor: pointer;
    }

    .main-content {
      flex: 1;
      background: white;
      padding: 30px;
      overflow-y: auto;
    }



    .active {
      background: #4267b2;
    }

    .topbar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
    }



    .cards {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }

    .card {
      flex: 1;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .card:nth-child(1) { background-color: #ffe5e5; }
    .card:nth-child(2) { background-color: #f0e5ff; }
    .card:nth-child(3) { background-color: #e5fff0; }
    .card:nth-child(4) { background-color: #e5f0ff; }

    table {
      width: 100%;
      background-color: white;
      border-collapse: collapse;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #f0f0f0;
    }

    td a {
      color: #7a42f4;
      text-decoration: none;
    }

    td a:hover {
      text-decoration: underline;
    }

    .button-row {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 10px;
    }

    .button-row button {
      background-color: #4267b2;
      width: 160px;
      height: 30px;
      border-radius: 10px;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="layout"> <!-- 🔁 Flex container -->
    
    <!-- Sidebar -->
    <div class="sidebar">
      <div>
        <div style="padding: 20px; text-align: center;">
          <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
        </div>

        <div style="padding: 10px;">
          <div class="nav-item">
            <i class="fa fa-tachometer"></i> <span>Dashboard</span>
          </div>
          <div class="nav-item active">
            <a href="tuition.php" style="text-decoration: none; color: white;">
              <i class="fas fa-peso-sign"></i> <span>Tuition</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="Payments.php" style="text-decoration: none; color: white;">
              <i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="recievable.html" style="text-decoration: none; color: white;">
              <i class="fas fa-file-invoice"></i> <span>Receivables</span>
            </a>
          </div>
          <div class="nav-item">
            <a href="#" style="text-decoration: none; color: white;">
              <i class="fas fa-file-alt"></i> <span>Statement of Account</span>
            </a>
          </div>
          <div class="nav-item">
            <i class="fas fa-list"></i> <span>Summary</span>
          </div>
        </div>
      </div>

      <!-- Bottom Feature -->
      <div style="padding: 20px; border-top: 1px solid #ffffff30;">
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
          <a href="homepage.html" style="text-decoration: none; color: white;">
            <span>Back</span>
          </a>
          <span style="background: #FFD700; color: black; padding: 2px 5px; border-radius: 12px; font-size: 10px;">NEW</span>
        </div>
      </div>
    </div>


      <div class="main-content">
    <h2>Tuition</h2>

    <form method="POST" action="save_tuition.php">
      <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <select name="student_id" required style="flex: 1;">
          <option value="">Select Student</option>
          <?php while ($row = $students->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>

        <input type="text" name="tuition" placeholder="Tuition" style="flex: 1;" required>

        <select name="year_level" style="flex: 1;" required>
          <option value="">Year Level</option>
          <option value="1st Year">1st Year</option>
          <option value="2nd Year">2nd Year</option>
          <option value="3rd Year">3rd Year</option>
          <option value="4th Year">4th Year</option>
        </select>

        <input type="text" name="monthly" placeholder="Monthly" style="flex: 1;" required>

        
        <input type="text" name="course" placeholder="Course" style="flex: 1;" required>

        <select name="payment_method" style="flex: 1;" required>
          <option value="">Payment</option>
          <option value="Cash">Cash</option>
          <option value="Installment">Installment</option>
        </select>

        <input type="text" name="misc_fee" placeholder="MISC Fee" style="flex: 1;" required>
        <input type="text" name="lab_fee" placeholder="Lab Fee" style="flex: 1;" required>
        <input type="text" name="total_fee" placeholder="Total Fee" style="flex: 1;" required>
      </div>

      <div class="button-row">
        <button type="submit">ADD TUITION</button>
      </div>

      <h3>Tuition Records</h3>
<table>
  <thead>
    <tr>
    <th>Student Name</th>
<th>Course</th>
<th>Year Level</th>
<th>Tuition</th>
<th>Monthly</th>
<th>Misc Fee</th>
<th>Lab Fee</th>
<th>Total Fee</th>
<th>Balance</th>
<th>Payment</th>
<th>Action</th>

    </tr>
  </thead>
  <tbody>
  <?php while ($row = $tuitionRecords->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['course']) ?></td>
      <td><?= htmlspecialchars($row['year_level']) ?></td>
      <td><?= htmlspecialchars($row['tuition']) ?></td>
      <td><?= htmlspecialchars($row['monthly']) ?></td>
      <td><?= htmlspecialchars($row['misc_fee']) ?></td>
      <td><?= htmlspecialchars($row['lab_fee']) ?></td>
      <td><?= htmlspecialchars($row['total_fee']) ?></td>
      <td><?= htmlspecialchars($row['balance']) ?></td>
      <td><?= htmlspecialchars($row['payment_method']) ?></td>
      <td>
        <a href="edit_tuition.php?id=<?= $row['id'] ?>" style="margin-right: 10px; color: #007bff;">
          <i class="fas fa-edit"></i> Edit
        </a>
        <a href="delete_tuition.php?id=<?= $row['id'] ?>" style="color: red;" onclick="return confirm('Are you sure you want to delete this record?');">
          <i class="fas fa-trash-alt"></i> Delete
        </a>
      </td>
    </tr>
  <?php endwhile; ?>
</tbody>


</table>

    </form>
  </div>
</body>
</html>
