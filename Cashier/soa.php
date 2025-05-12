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
    // Fetch student's statement of account or tuition information here
    $soa_query = "SELECT * FROM tuition WHERE student_id = '$student_id'";
    $soa_result = $conn->query($soa_query);
    $soa_data = $soa_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .container {
            flex-grow: 1;
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
            background: #e6f4ff;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div style="text-align: center; padding: 20px;">
            <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
        </div>
        <div style="padding: 10px;">
            <div class="nav-item"><i class="fa fa-tachometer"></i> <span>Dashboard</span></div>
            <div class="nav-item active">
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
            <div class="nav-item"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></div>
            <div class="nav-item"><i class="fas fa-list"></i> <span>Summary</span></div>
        </div>
        <div style="padding: 20px; border-top: 1px solid #ffffff30;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
                <a href="homepage.html" style="text-decoration: none; color: white;">
                    <span>Back</span>
                </a>
                <span style="background: #FFD700; color: black; padding: 2px 5px; border-radius: 12px; font-size: 10px;">NEW</span>
            </div>
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
                            <td>
                                <a href="?student_id=<?= $row['Admission_ID'] ?>">View SOA</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($search_query): ?>
            <p>No students found matching your search.</p>
        <?php endif; ?>

        <!-- Display Statement of Account if student is selected -->
        <?php if ($student_id && isset($soa_data)): ?>
            <h3>Statement of Account for <?= htmlspecialchars($soa_data['student_id']) ?></h3>
            <table>
                <thead>
                    <tr>
                        <th>Tuition Fees</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tuition Fee</td>
                        <td>₱<?= number_format($soa_data['total_fee'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>Miscellaneous Fees</td>
                        <td>₱<?= number_format($soa_data['misc_fee'], 2) ?></td>
                    </tr>
                    <tr>
                        <td>Total Balance</td>
                        <td>₱<?= number_format($soa_data['balance'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        <?php elseif ($student_id): ?>
            <p>Student not found.</p>
        <?php endif; ?>

    </div>

</body>
</html>

<?php $conn->close(); ?>
