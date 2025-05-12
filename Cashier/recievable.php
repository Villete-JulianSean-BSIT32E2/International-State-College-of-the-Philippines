<?php
include '../connect.php';

// Generate a random 6-digit OR number
$or_no = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

// Handle receipt generation
if (isset($_GET['receipt']) && isset($_GET['or_no'])) {
    $or_number = mysqli_real_escape_string($conn, $_GET['or_no']);
    $receiptQuery = mysqli_query($conn, "SELECT * FROM receivables WHERE or_no = '$or_number' LIMIT 1");
    
    if (mysqli_num_rows($receiptQuery) > 0) {
        $receiptData = mysqli_fetch_assoc($receiptQuery);
        
        // Get payment amount from payments table
        $paymentQuery = mysqli_query($conn, 
            "SELECT p.amount_paid 
             FROM payments p
             JOIN tuition t ON p.student_id = t.student_id
             JOIN admission a ON t.student_id = a.id
             WHERE a.name = '".mysqli_real_escape_string($conn, $receiptData['student_name'])."'");
        
        $paymentAmount = 0;
        while ($row = mysqli_fetch_assoc($paymentQuery)) {
            $paymentAmount += $row['amount_paid'];
        }
        
        // Generate receipt HTML
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Official Receipt - '.htmlspecialchars($receiptData['or_no']).'</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
                .receipt { width: 80%; max-width: 600px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { margin: 0; font-size: 24px; }
                .header p { margin: 5px 0; }
                .details { margin: 20px 0; }
                .detail-row { display: flex; margin-bottom: 10px; }
                .detail-label { font-weight: bold; width: 150px; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; }
                .signature { margin-top: 50px; display: flex; justify-content: space-between; }
                .signature div { width: 45%; text-align: center; border-top: 1px solid #000; padding-top: 5px; }
                @media print {
                    body { padding: 0; }
                    .no-print { display: none; }
                    .receipt { border: none; }
                }
            </style>
        </head>
        <body>
            <div class="receipt">
                <div class="header">
                    <h1>OFFICIAL RECEIPT</h1>
                    <p>INTERNATIONAL STATE COLLEGE OF THE PHILIPPINES</p>
                    <p>Cubao, The Center of The Philippines</p>
                    <p>OR No: '.htmlspecialchars($receiptData['or_no']).'</p>
                </div>
                
                <div class="details">
                    <div class="detail-row">
                        <div class="detail-label">Date:</div>
                        <div>'.htmlspecialchars($receiptData['date']).'</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Student Name:</div>
                        <div>'.htmlspecialchars($receiptData['student_name']).'</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Course:</div>
                        <div>'.htmlspecialchars($receiptData['course']).'</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Payment Date:</div>
                        <div>'.htmlspecialchars($receiptData['payment_date']).'</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Total Fee:</div>
                        <div>₱'.number_format($receiptData['total_fee'], 2).'</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Payment Amount:</div>
                        <div>₱'.number_format($paymentAmount, 2).'</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Remaining Balance:</div>
                        <div>₱'.number_format($receiptData['balance'], 2).'</div>
                    </div>
                </div>
                
                <div class="signature">
                    <div>Cashier/Authorized Signature</div>
            
                </div>
                
                <div class="footer">
                    <p>This serves as your official receipt. Please keep this for your records.</p>
                    <p>Thank you for your payment!</p>
                </div>
                
                <div class="no-print" style="margin-top: 20px; text-align: center;">
                    <button onclick="window.print()">Print Receipt</button>
                    <a href="recievable.php" style="margin-left: 10px;">Back to Receivables</a>
                </div>
            </div>
        </body>
        </html>';
        exit();
    }
}

// Fetch students from admission table
$students = [];
$result = mysqli_query($conn, "SELECT name FROM admission");
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row['name'];
}

// Fetch selected student from GET
$selectedStudent = isset($_GET['student']) ? $_GET['student'] : '';

$studentTuition = ['balance' => '0.00', 'total_fee' => '0.00', 'payment_amount' => '0.00'];

if (!empty($selectedStudent)) {
    $safeStudent = mysqli_real_escape_string($conn, $selectedStudent);
    
    // Get tuition info
    $query = "SELECT t.balance, t.total_fee 
              FROM tuition t
              JOIN admission a ON t.student_id = a.id
              WHERE a.name = '$safeStudent'
              LIMIT 1";
    
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $studentTuition = mysqli_fetch_assoc($result);
    }
    
    // Get payment amount
    $paymentQuery = mysqli_query($conn, 
        "SELECT p.amount_paid 
         FROM payments p
         JOIN tuition t ON p.student_id = t.student_id
         JOIN admission a ON t.student_id = a.id
         WHERE a.name = '$safeStudent'");
    
    $paymentAmount = 0;
    while ($row = mysqli_fetch_assoc($paymentQuery)) {
        $paymentAmount += $row['amount_paid'];
    }
    $studentTuition['payment_amount'] = $paymentAmount;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $or = $_POST['or_no'];
    $student = $_POST['student_name'];
    $total_fee = $_POST['total_fee'];
    $date = $_POST['date'];
    $course = $_POST['course'];
    $payment_date = $_POST['payment_date'];
    $balance = $_POST['balance'];
    $payment_amount = $_POST['payment_amount'];

    $insert = "INSERT INTO receivables (or_no, student_name, total_fee, date, course, payment_date, balance, payment_amount)
               VALUES ('$or', '$student', '$total_fee', '$date', '$course', '$payment_date', '$balance', '$payment_amount')";
    mysqli_query($conn, $insert);
    
    // Redirect to prevent form resubmission
    header("Location: recievable.php?student=".urlencode($student));
    exit();
}

// Fetch all receivables
$receivables = mysqli_query($conn, "SELECT * FROM receivables ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receivables</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #f0f2f5;
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
    .main-content {
      margin-left: 200px;
      padding: 20px;
    }
    .field {
      margin-bottom: 10px;
    }
    .field label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .field input, .field select {
      width: 250px;
      padding: 8px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }
    form button {
      background-color: #4267b2;
      width: 160px;
      height: 30px;
      border-radius: 10px;
      color: white;
      border: none;
      cursor: pointer;
      margin-top: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
      background: white;
    }
    table th, table td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    table th {
      background: #4267b2;
      color: white;
    }
    .action-link {
      color: #4267b2;
      text-decoration: none;
    }
    .action-link:hover {
      text-decoration: underline;
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
    <a href="recievable.php" class="nav-item active"><i class="fas fa-file-invoice"></i> <span>Receivables</span></a>
    <a href="soa.php" class="nav-item"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></a>
    <div class="nav-item"><i class="fas fa-list"></i> <span>Summary</span></div>
  </div>
</div>

<div class="main-content">
  <h2>Select Student</h2>
  <form method="GET" action="recievable.php">
    <div class="field">
      <label>Student Name</label>
      <select name="student" onchange="this.form.submit()" required>
        <option value="">Select Student</option>
        <?php foreach ($students as $student): ?>
          <option value="<?= htmlspecialchars($student) ?>" <?= ($student == $selectedStudent) ? 'selected' : '' ?>>
            <?= htmlspecialchars($student) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
  </form>

  <?php if (!empty($selectedStudent)): ?>
  <h2>Receivable Entry</h2>
  <form method="POST" action="recievable.php">
    <input type="hidden" name="student_name" value="<?= htmlspecialchars($selectedStudent) ?>">
    <input type="hidden" name="or_no" value="<?= $or_no ?>">

    <div class="field">
      <label>Date</label>
      <input type="date" name="date" required>
    </div>

    <div class="field">
      <label>Course</label>
      <select name="course" required>
        <option value="">Select Course</option>
        <option value="BSIT">BSIT</option>
        <option value="CSS">CSS</option>
      </select>
    </div>

    <div class="field">
      <label>Payment Date</label>
      <input type="date" name="payment_date" required>
    </div>

    <div class="field">
      <label>Total Fee</label>
      <input type="text" name="total_fee" readonly 
             value="<?= isset($studentTuition['total_fee']) ? htmlspecialchars($studentTuition['total_fee']) : '0.00' ?>">
    </div>

    <div class="field">
      <label>Payment Amount</label>
      <input type="text" name="payment_amount" readonly 
             value="<?= isset($studentTuition['payment_amount']) ? htmlspecialchars($studentTuition['payment_amount']) : '0.00' ?>">
    </div>

    <div class="field">
      <label>Remaining Balance</label>
      <input type="text" name="balance" readonly 
             value="<?= isset($studentTuition['balance']) ? htmlspecialchars($studentTuition['balance']) : '0.00' ?>">
    </div>

    <button type="submit">Add Receivable</button>
  </form>
  <?php endif; ?>

  <h2>Receivables Table</h2>
  <table>
    <thead>
      <tr>
        <th>OR Number</th>
        <th>Student Name</th>
        <th>Total Fee</th>
        <th>Payment Amount</th>
        <th>Date</th>
        <th>Course</th>
        <th>Payment Date</th>
        <th>Remaining Balance</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($receivables)): ?>
        <tr>
          <td><?= htmlspecialchars($row['or_no']) ?></td>
          <td><?= htmlspecialchars($row['student_name']) ?></td>
          <td><?= number_format($row['total_fee'], 2) ?></td>
          <td><?= number_format($row['payment_amount'], 2) ?></td>
          <td><?= htmlspecialchars($row['date']) ?></td>
          <td><?= htmlspecialchars($row['course']) ?></td>
          <td><?= htmlspecialchars($row['payment_date']) ?></td>
          <td><?= number_format($row['balance'], 2) ?></td>
          <td>
            <a href="recievable.php?receipt=1&or_no=<?= $row['or_no'] ?>" target="_blank" class="action-link">View Receipt</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body>
</html>