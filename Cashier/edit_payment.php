<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$result = $conn->query("SELECT * FROM payments WHERE id = $id");
$data = $result->fetch_assoc();

$students = $conn->query("SELECT id, name FROM admission ORDER BY name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $amount_paid = $_POST['amount_paid'];
    $payment_date = $_POST['payment_date'];
    $method = $_POST['payment_method'];
    $note = $_POST['note'];

    $conn->query("UPDATE payments SET 
        student_id='$student_id', 
        amount_paid='$amount_paid', 
        payment_date='$payment_date', 
        payment_method='$method', 
        note='$note' 
        WHERE id=$id");

    header("Location: Payments.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Payment</title>
</head>
<body>
  <h2>Edit Payment</h2>
  <form method="POST">
    <label>Student</label>
    <select name="student_id" required>
      <?php while ($row = $students->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>" <?= $data['student_id'] == $row['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($row['name']) ?>
        </option>
      <?php endwhile; ?>
    </select><br><br>

    <label>Amount Paid</label>
    <input type="number" name="amount_paid" value="<?= $data['amount_paid'] ?>" required><br><br>

    <label>Payment Date</label>
    <input type="date" name="payment_date" value="<?= $data['payment_date'] ?>" required><br><br>

    <label>Method</label>
    <select name="payment_method" required>
      <?php foreach (['Cash', 'Gcash', 'Bank Transfer'] as $method): ?>
        <option value="<?= $method ?>" <?= $data['payment_method'] == $method ? 'selected' : '' ?>>
          <?= $method ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Note</label>
    <textarea name="note"><?= htmlspecialchars($data['note']) ?></textarea><br><br>

    <button type="submit">Update Payment</button>
  </form>
</body>
</html>
