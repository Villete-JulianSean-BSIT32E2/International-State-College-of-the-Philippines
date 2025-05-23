<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search functionality
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Fetch students based on search query
$sql = "SELECT * FROM tbladmission_addstudent WHERE full_name LIKE '%$search_query%' ORDER BY full_name ASC";
$result = $conn->query($sql);

// Handle student selection
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : null;

if ($student_id) {
    $soa_query = "SELECT * FROM tuition WHERE student_id = '$student_id'";
    $soa_result = $conn->query($soa_query);
    $soa_data = $soa_result->fetch_assoc();

    // Fetch student name
    $student_query = "SELECT full_name, address, zip, status FROM tbladmission_addstudent WHERE Admission_ID = '$student_id'";

    $student_result = $conn->query($student_query);
    $student_info = $student_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statement of Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f0f8ff;
        }
       .sidebar {
      width: 200px;
      background: #112D4E;
      color: white;
      display: flex;
      flex-direction: column;
      padding: 10px 0;
      min-height: 100vh;
      box-shadow: 2px 0 8px rgba(0,0,0,0.1);
      position: fixed;
      left: 0;
      top: 0;
    }
    .nav-item {
      margin: 5px 10px;
      padding: 10px;
      display: flex;
      align-items: center;
      gap: 10px;
      border-radius: 4px;
      transition: background 0.3s;
      color: white;
      text-decoration: none;
    }
    .nav-item:hover {
      background: #4267b2;
      cursor: pointer;
    }
    .active {
      background: #4267b2;
    }

       .container {
    margin-left: 200px; /* This prevents the sidebar from overlapping the content */
    padding: 30px;
}
        h2 {
            text-align: center;
            color: #005580;
            margin-bottom: 25px;
        }
        .search-form input[type="text"] {
            padding: 10px;
            border: 1px solid #b3daff;
            border-radius: 5px;
            width: 70%;
        }
        .search-form button {
            padding: 10px 15px;
            background: #007acc;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-form button:hover {
            background: #005f99;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background: #ffffff;
        }
        th, td {
            padding: 12px;
            border: 1px solid #cce6ff;
            text-align: left;
        }
        th {
            background: #003366;
            color: white;
        }
        .soa-header {
            display: flex;
            justify-content: space-between;
            background: #f9f9f9;
            padding: 20px;
            margin-top: 30px;
            border: 1px solid #ccc;
        }
        .soa-header div {
            width: 45%;
        }
        .soa-header h4 {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
   <div class="sidebar">
  <div style="padding: 20px; text-align: center;">
    <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
  </div>
  <div style="padding: 10px;">
    <div class="nav-item"><i class="fa fa-tachometer"></i> <span>Dashboard</span></div>
    <a href="tuition.php" class="nav-item"><i class="fas fa-peso-sign"></i> <span>Tuition</span></a>
    <a href="Payments.php" class="nav-item"><i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span></a>
    <a href="recievable.php" class="nav-item"><i class="fas fa-file-invoice"></i> <span>Receivables</span></a>
    <a href="soa.php" class="nav-item active"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></a>
    <a href="summary.php" class="nav-item"><i class="fas fa-list"></i> <span>Get Summary Reports</span></a>
  </div>
</div>
    <!-- Main Content -->
    <div class="container">
        <h2>Search and Select Student</h2>

        <!-- Search Form -->
        <form method="POST" class="search-form">
            <input type="text" name="search" placeholder="Search by student name" value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit">Search</button>
        </form>

        <?php if ($result->num_rows > 0): ?>
            <h3>Search Results</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['full_name']) ?></td>
                            <td><a href="?student_id=<?= $row['Admission_ID'] ?>">View SOA</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($search_query): ?>
            <p>No students found matching your search.</p>
        <?php endif; ?>

        <!-- Display SOA -->
        <?php if ($student_id && isset($soa_data)): ?>
            <div class="soa-header">
                <div>
                <h4>Bill To <br>
Student Name: <?= htmlspecialchars($student_info['full_name']) ?><br>
Student Adress: <?= htmlspecialchars($student_info['address']) ?><br>
ZIP Code: <?= htmlspecialchars($student_info['zip']) ?><br>
Student Status: <?= htmlspecialchars($student_info['status']) ?>
</h4>
                </div>
                <div>
                    <h4>Account Summary</h4>
                    Date: <?= date('F d, Y') ?><br>
                    Statement #: <?= rand(1000, 9999) ?><br>
                    Student ID: <?= htmlspecialchars($soa_data['student_id']) ?><br>
                    Page 1 of 1<br><br>
                    Previous Balance: ₱<?= number_format($soa_data['total_fee'], 2) ?><br>
                    New Charges: ₱<?= number_format($soa_data['misc_fee'], 2) ?><br>
                    Total Balance Due: ₱<?= number_format($soa_data['balance'], 2) ?><br>
                    Payment Due Date: July 20, 2025
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Invoice #</th>
                        <th>Description</th>
                        <th>Charges</th>
                     
                        <th>Line Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= date('Y-m-d') ?></td>
                        <td>INV-<?= rand(10000, 99999) ?></td>
                        <td>Tuition Fee</td>
                        <td>₱<?= number_format($soa_data['total_fee'], 2) ?></td>
                     
                        <td>₱<?= number_format($soa_data['total_fee'], 2) ?></td>
                    </tr>
                    <tr>
                        <td><?= date('Y-m-d') ?></td>
                        <td>INV-<?= rand(10000, 99999) ?></td>
                        <td>Miscellaneous Fee</td>
                        <td>₱<?= number_format($soa_data['misc_fee'], 2) ?></td>
                     
                        <td>₱<?= number_format($soa_data['misc_fee'], 2) ?></td>
                    </tr>

     

                </tbody>

</div>
            </table>
        <?php elseif ($student_id): ?>
            <p>Student not found or no tuition data available.</p>
        <?php endif; ?>
    </div>

    
</body>
</html>

<?php $conn->close(); ?>
