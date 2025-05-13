<?php
include '../connect.php'; // Adjust the path if needed

// Fetch summary data per student
$query = "
SELECT 
    a.Admission_ID,
    a.full_name,
    COALESCE(t.total_fee, 0) AS total_fee,
    COALESCE(t.balance, 0) AS balance,
    COALESCE(SUM(p.amount_paid), 0) AS total_paid
FROM tbladmission_addstudent a
LEFT JOIN tuition t ON a.Admission_ID = t.student_id
LEFT JOIN payments p ON a.Admission_ID = p.student_id
GROUP BY a.Admission_ID, a.full_name;
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Summary / Get Reports</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            background-color: #e6f0ff;
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

        .content {
            flex: 1;
            padding: 30px;
        }
        h2 {
            margin-bottom: 10px;
            color: #003366;
        }
        .print-btn {
            float: right;
            padding: 8px 16px;
            font-weight: bold;
            background-color: #3399ff;
            color: white;
            border: none;
            border-radius: 4px;
            margin-bottom: 10px;
            cursor: pointer;
        }
        .print-btn:hover {
            background-color: #007acc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            background-color: white;
        }
        table th, table td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #cce5ff;
        }
        .total-row {
            font-weight: bold;
            background-color: #e0ecff;
        }
    </style>
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
                <a href="recievable.php">
                    <i class="fas fa-file-invoice"></i> <span>Receivables</span>
                </a>
            </div>
            <div class="nav-item"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></div>
            <div class="nav-item active">
                <a href="summary.php">
                    <i class="fas fa-file-invoice"></i> <span> Get Summary Reports</span>
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <h2>Summary Report</h2>
        <button class="print-btn" onclick="window.print()">🖨️ Print / Export PDF</button>

        <table>
            <thead>
                <tr>
                    <th>Student ID</th>
                    <th>Full Name</th>
                    <th>Total Fee</th>
                    <th>Total Paid</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_fee = $total_paid = $total_balance = 0;

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $total_fee += $row['total_fee'];
                        $total_paid += $row['total_paid'];
                        $total_balance += $row['balance'];
                        echo "<tr>
                            <td>{$row['Admission_ID']}</td>
                            <td>" . htmlspecialchars($row['full_name']) . "</td>
                            <td>₱" . number_format($row['total_fee'], 2) . "</td>
                            <td>₱" . number_format($row['total_paid'], 2) . "</td>
                            <td>₱" . number_format($row['balance'], 2) . "</td>
                        </tr>";
                    }
                } else {
                    echo '<tr><td colspan="5">No data found.</td></tr>';
                }
                ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="2">TOTAL</td>
                    <td>₱<?= number_format($total_fee, 2) ?></td>
                    <td>₱<?= number_format($total_paid, 2) ?></td>
                    <td>₱<?= number_format($total_balance, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>
