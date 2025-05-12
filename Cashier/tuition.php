<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Tuition Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      display: flex;
    }
    .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            min-height: 100vh;
            padding-top: 20px;
        }
        .sidebar .nav-item {
            padding: 15px;
            cursor: pointer;
        }
        .sidebar .nav-item:hover, .sidebar .nav-item.active {
            background-color: #34495e;
        }
        .sidebar .nav-item a {
            color: white;
            text-decoration: none;
            display: block;
        }


    .main-content {
      flex: 1;
      padding: 30px;
    }
    form {
      background: #f9f9f9;
      padding: 20px;
      max-width: 700px;
      margin: auto;
      border-radius: 10px;
    }
    input, select {
      padding: 10px;
      margin-bottom: 15px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    input[type=submit] {
      background-color: #27ae60;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }
    .search-results {
      max-height: 150px;
      overflow-y: auto;
      border: 1px solid #ccc;
      padding: 10px;
      background-color: white;
      position: absolute;
      width: 100%;
      z-index: 1;
    }
    .search-result-item {
      padding: 10px;
      cursor: pointer;
    }
    .search-result-item:hover {
      background-color: #f0f0f0;
    }
  </style>
  <script>
    function calculateTotals() {
      const tuition = parseFloat(document.getElementById("tuition").value) || 0;
      const misc = parseFloat(document.getElementById("misc_fee").value) || 0;
      const lab = parseFloat(document.getElementById("lab_fee").value) || 0;

      const totalTuition = tuition + misc + lab;
      const totalFee = totalTuition;
      const balance = totalFee;

      document.getElementById("total_tuition").value = totalTuition.toFixed(2);
      document.getElementById("total_fee").value = totalFee.toFixed(2);
      document.getElementById("balance").value = balance.toFixed(2);
    }

    function searchStudent() {
      const searchQuery = document.getElementById("search_student").value;
      if (searchQuery.length < 3) {
        document.getElementById("search_results").style.display = "none";
        return;
      }

      const xhr = new XMLHttpRequest();
      xhr.open("GET", "search_student.php?query=" + searchQuery, true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          document.getElementById("search_results").innerHTML = xhr.responseText;
          document.getElementById("search_results").style.display = "block";
        }
      }
      xhr.send();
    }

    function selectStudent(id, name, year_level, course) {
      document.getElementById("student_id").value = id;
      document.getElementById("search_student").value = name;
      document.getElementById("year_level").value = year_level;
      document.getElementById("course").value = course;
      document.getElementById("search_results").style.display = "none";
    }
  </script>
</head>
<body>

<div class="sidebar">
        <div style="text-align: center; padding: 20px;">
            <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
        </div>
        <div style="padding: 10px;">
        <div class="nav-item">
    <a href="../main-dashboard.php">
        <i class="fa fa-tachometer"></i> <span>Dashboard</span>
    </a>
</div>
            <div class="nav-item">
                <a href="tuition.php">
                    <i class="fas fa-peso-sign"></i> <span>Tuition</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="Payments.php">
                    <i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="recievable.html">
                    <i class="fas fa-file-invoice"></i> <span>Receivables</span>
                </a>
            </div>
            <div class="nav-item active"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></div>
            <div class="nav-item">
                <a href="summary.php">
                    <i class="fas fa-file-invoice"></i> <span> Get Summary Reports</span>
                </a>
            </div>
        </div>
    </div>

<div class="main-content">
  <h2>Tuition Management</h2>

  <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $student_id = $_POST['student_id'];
      $year_level = $_POST['year_level'];
      $course = $_POST['course'];
      $tuition = $_POST['tuition'];
      $monthly = $_POST['monthly'];
      $payment_method = $_POST['payment_method'];
      $misc_fee = $_POST['misc_fee'];
      $lab_fee = $_POST['lab_fee'];
      $total_tuition = $_POST['total_tuition'];
      $total_fee = $_POST['total_fee'];
      $balance = $_POST['balance'];

      $insert = "INSERT INTO tuition (
        student_id, year_level, course, tuition, monthly, payment_method,
        misc_fee, lab_fee, total_tuition, total_fee, balance
      ) VALUES (
        '$student_id', '$year_level', '$course', '$tuition', '$monthly', '$payment_method',
        '$misc_fee', '$lab_fee', '$total_tuition', '$total_fee', '$balance'
      )";

      if (mysqli_query($conn, $insert)) {
        echo "<p style='color: green;'>Tuition record added successfully.</p>";
      } else {
        echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
      }
    }
  ?>

  <form method="POST" action="">
    <label for="search_student">Search Student:</label>
    <input type="text" id="search_student" oninput="searchStudent()" placeholder="Enter student name" required>
    
    <div id="search_results" class="search-results" style="display: none;"></div>

    <input type="hidden" id="student_id" name="student_id">
    <input type="text" id="year_level" name="year_level" placeholder="Year Level" required>
    <input type="text" id="course" name="course" placeholder="Course" required>
    <input type="number" step="0.01" id="tuition" name="tuition" placeholder="Tuition" oninput="calculateTotals()" required>
    <input type="number" step="0.01" name="monthly" placeholder="Monthly" required>
    <select name="payment_method" required>
      <option value="">-- Select Payment Method --</option>
      <option value="Full">Full</option>
      <option value="Installment">Installment</option>
    </select>
    <input type="number" step="0.01" id="misc_fee" name="misc_fee" placeholder="Miscellaneous Fee" oninput="calculateTotals()" required>
    <input type="number" step="0.01" id="lab_fee" name="lab_fee" placeholder="Laboratory Fee" oninput="calculateTotals()" required>
    <label>Total Tuition</label>
    <input type="number" step="0.01" id="total_tuition" name="total_tuition" placeholder="Total Tuition" readonly required>
    <label>Total Fee</label>
    <input type="number" step="0.01" id="total_fee" name="total_fee" placeholder="Total Fee" readonly required>
    <label>Balance</label>
    <input type="number" step="0.01" id="balance" name="balance" placeholder="Balance" readonly required>

    <input type="submit" value="Submit Tuition Record">
  </form>
</div>

</body>
</html>
