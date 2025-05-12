<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST (insert new payment)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $method = $_POST['payment_method'];
    $note = $_POST['note'];

    // Insert payment record
    $conn->query("INSERT INTO payments (student_id, amount_paid, payment_date, payment_method, note)
                  VALUES ('$student_id', '$amount_paid', '$payment_date', '$method', '$note')");

    // Update tuition balance
    $conn->query("UPDATE tuition SET balance = balance - $amount_paid WHERE student_id = '$student_id'");
}

// Fetch students
$students = $conn->query("SELECT id, name FROM admission ORDER BY name ASC");

// Fetch payment records
$payments = $conn->query("SELECT p.*, a.name FROM payments p JOIN admission a ON p.student_id = a.id ORDER BY p.payment_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Payments</title>
  <style>
    body {
      background: #f0f8ff;
      font-family: Arial, sans-serif;
      padding: 30px;
    }
    .container {
      background: white;
      padding: 25px;
      border-radius: 10px;
      max-width: 900px;
      margin: auto;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #005580;
      margin-bottom: 25px;
    }
    form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }
    form input, form select, form textarea {
      padding: 10px;
      border: 1px solid #b3daff;
      border-radius: 5px;
      width: 100%;
    }
    form button {
      grid-column: span 2;
      padding: 12px;
      background: #007acc;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    form button:hover {
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
    .action-buttons a {
      margin-right: 10px;
      color: #007acc;
      text-decoration: none;
    }
    .action-buttons a:hover {
      text-decoration: underline;
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
    .layout {
      display: flex;
      min-height: 100vh;
    }
  </style>
  <script>
    function fetchTuition(studentId) {
      if (!studentId) {
        document.getElementById('tuition-info').innerHTML = '';
        return;
      }

      const xhr = new XMLHttpRequest();
      xhr.open('GET', 'get_tuition_info.php?student_id=' + studentId, true);
      xhr.onload = function () {
        if (this.status === 200) {
          const data = JSON.parse(this.responseText);
          if (data.success) {
            document.getElementById('tuition-info').innerHTML = `
              <p><strong>Total Tuition:</strong> ₱${parseFloat(data.total).toFixed(2)}</p>
              <p><strong>Current Balance:</strong> ₱${parseFloat(data.balance).toFixed(2)}</p>
              <input type="hidden" name="current_balance" value="${data.balance}">
            `;
          } else {
            document.getElementById('tuition-info').innerHTML = '<p style="color:red;">No tuition record found for this student.</p>';
          }
        }
      };
      xhr.send();
    }
  </script>
</head>
<body>
  <div class="layout">
    <div class="sidebar">
      <div style="padding: 20px; text-align: center;">
        <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
      </div>
      <div style="padding: 10px;">
        <div class="nav-item"><i class="fa fa-tachometer"></i> <span>Dashboard</span></div>
        <a href="tuition.php" class="nav-item"><i class="fas fa-peso-sign"></i> <span>Tuition</span></a>
        <a href="Payments.php" class="nav-item active"><i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span></a>
        <a href="recievable.php" class="nav-item"><i class="fas fa-file-invoice"></i> <span>Receivables</span></a>
        <a href="#" class="nav-item"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></a>
        <div class="nav-item"><i class="fas fa-list"></i> <span>Summary</span></div>
      </div>
    </div>

    <div class="container">
      <h2>Manage Invoice / Payments</h2>

      <form method="POST">
        <select name="student_id" required onchange="fetchTuition(this.value)">
          <option value="">Select Student</option>
          <?php while ($row = $students->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
          <?php endwhile; ?>
        </select>

        <div id="tuition-info" style="grid-column: span 2; margin-bottom: 15px;"></div>

        <input type="number" name="amount_paid" placeholder="Amount Paid" required>
        <input type="date" name="payment_date" required>

        <select name="payment_method" required>
          <option value="">Payment Method</option>
          <option value="Cash">Cash</option>
          <option value="Gcash">Gcash</option>
          <option value="Bank Transfer">Bank Transfer</option>
        </select>

        <textarea name="note" placeholder="Notes (optional)" rows="2"></textarea>

        <button type="submit">Record Payment</button>
      </form>

      <h3 style="margin-top: 40px;">Payment History</h3>
      <table>
        <thead>
          <tr>
            <th>Student</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Method</th>
            <th>Note</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $payments->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>₱<?= number_format($row['amount_paid'], 2) ?></td>
            <td><?= $row['payment_date'] ?></td>
            <td><?= $row['payment_method'] ?></td>
            <td><?= htmlspecialchars($row['note']) ?></td>
            <td class="action-buttons">
              <a href="edit_payment.php?id=<?= $row['id'] ?>">Edit</a>
              <a href="delete_payment.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this payment?')">Delete</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>
