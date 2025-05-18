<?php
include '../connect.php';

// Handle date filters
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

$dateFilter = "";
if ($start_date && $end_date) {
    $dateFilter = "AND p.payment_date BETWEEN '$start_date' AND '$end_date'";
}

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
LEFT JOIN payments p ON a.Admission_ID = p.student_id AND p.payment_date IS NOT NULL $dateFilter
GROUP BY a.Admission_ID, a.full_name
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
            width: 200px;
            background: #112D4E;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 10px 0;
            min-height: 100vh;
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
        .content {
            margin-left: 200px;
            padding: 30px;
        }
        h2 {
            margin-bottom: 10px;
            color: #003366;
        }
        .print-btn {
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
        .filter-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="sidebar">
  <div style="padding: 20px; text-align: center;">
    <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
  </div>
  <div style="padding: 10px;">
    <div class="nav-item"><i class="fa fa-tachometer"></i> <span>Dashboard</span></div>
    <a href="tuition.php" class="nav-item"><i class="fas fa-peso-sign"></i> <span>Tuition</span></a>
    <a href="Payments.php" class="nav-item"><i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span></a>
    <a href="recievable.php" class="nav-item"><i class="fas fa-file-invoice"></i> <span>Receivables</span></a>
    <a href="soa.php" class="nav-item"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></a>
    <a href="summary.php" class="nav-item active"><i class="fas fa-list"></i> <span>Get Summary Reports</span></a>
  </div>
</div>

<div class="content">
    <h2>Summary Report</h2>

    <!-- Date Filter -->
    <form method="GET" class="filter-form">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required value="<?= htmlspecialchars(isset($start_date) ? $start_date : '') ?>">



        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required value="<?= htmlspecialchars(isset($end_date) ? $end_date : '') ?>">


        <button type="submit" class="print-btn">🔍 Filter</button>
        <button type="button" onclick="exportToExcel('summaryTable')" class="print-btn">📥 Export to Excel</button>
        <button class="print-btn" onclick="window.print()">🖨️ Print / Export PDF</button>
    </form>

    <?php if ($start_date && $end_date): ?>
        <p><strong>Showing results from <?= htmlspecialchars($start_date) ?> to <?= htmlspecialchars($end_date) ?></strong></p>
    <?php endif; ?>

    <table id="summaryTable">
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

<script>
function exportToExcel(tableID) {
    let dataType = 'application/vnd.ms-excel';
    let tableSelect = document.getElementById(tableID);
    let tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

    let filename = 'summary_report.xls';
    let downloadLink = document.createElement("a");
    document.body.appendChild(downloadLink);

    if(navigator.msSaveOrOpenBlob){
        let blob = new Blob(['\ufeff', tableHTML], { type: dataType });
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
        downloadLink.download = filename;
        downloadLink.click();
    }
}
</script>

</body>
</html>
