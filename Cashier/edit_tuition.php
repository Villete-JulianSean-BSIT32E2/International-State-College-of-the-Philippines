<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$record = $conn->query("SELECT * FROM tuition WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $course = $conn->real_escape_string($_POST['course']);
    $year_level = $conn->real_escape_string($_POST['year_level']);
    $tuition = $conn->real_escape_string($_POST['tuition']);
    $monthly = $conn->real_escape_string($_POST['monthly']);
    $misc_fee = $conn->real_escape_string($_POST['misc_fee']);
    $lab_fee = $conn->real_escape_string($_POST['lab_fee']);
    $total_fee = $conn->real_escape_string($_POST['total_fee']);
    $payment_method = $conn->real_escape_string($_POST['payment_method']);
    $balance = $conn->real_escape_string($_POST['balance']);

    $update = "UPDATE tuition SET
                student_id='$student_id',
                course='$course',
                year_level='$year_level',
                tuition='$tuition',
                monthly='$monthly',
                misc_fee='$misc_fee',
                lab_fee='$lab_fee',
                total_fee='$total_fee',
                payment_method='$payment_method',
                balance='$balance'
              WHERE id=$id";

    if ($conn->query($update)) {
        header("Location: tuition.php?updated=1");
        exit();
    } else {
        echo "Update failed: " . $conn->error;
    }
}

// Fetch students list for dropdown
$students = $conn->query("SELECT id, name FROM admission ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Tuition</title>
  <style>
    body {
      background-color: #e6f2ff;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }

    .container {
      max-width: 700px;
      background: #ffffff;
      padding: 30px;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h2 {
      color: #004080;
      text-align: center;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      margin-bottom: 5px;
    }

    input, select {
      padding: 10px;
      border: 1px solid #b3d1ff;
      border-radius: 5px;
      width: 100%;
    }

    button {
      padding: 12px;
      background-color: #3399ff;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #007acc;
    }

    .back-link {
      display: inline-block;
      margin-top: 15px;
      text-align: center;
      text-decoration: none;
      color: #007acc;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Edit Tuition Record</h2>
  <form method="POST">
    <label for="student_id">Student</label>
    <select name="student_id" required>
      <option value="">Select Student</option>
      <?php while ($s = $students->fetch_assoc()): ?>
        <option value="<?= $s['id'] ?>" <?= $s['id'] == $record['student_id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($s['name']) ?>
        </option>
      <?php endwhile; ?>
    </select>

    <label for="course">Course</label>
    <input type="text" name="course" value="<?= htmlspecialchars($record['course']) ?>" required>

    <label for="year_level">Year Level</label>
    <select name="year_level" required>
      <?php
        $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
        foreach ($years as $year) {
          $selected = ($year == $record['year_level']) ? 'selected' : '';
          echo "<option value=\"$year\" $selected>$year</option>";
        }
      ?>
    </select>

    <label for="tuition">Tuition</label>
    <input type="text" name="tuition" value="<?= htmlspecialchars($record['tuition']) ?>" required>

    <label for="monthly">Monthly</label>
    <input type="text" name="monthly" value="<?= htmlspecialchars($record['monthly']) ?>" required>

    <label for="misc_fee">Misc Fee</label>
    <input type="text" name="misc_fee" value="<?= htmlspecialchars($record['misc_fee']) ?>" required>

    <label for="lab_fee">Lab Fee</label>
    <input type="text" name="lab_fee" value="<?= htmlspecialchars($record['lab_fee']) ?>" required>

    <label for="total_fee">Total Fee</label>
    <input type="text" name="total_fee" value="<?= htmlspecialchars($record['total_fee']) ?>" required>

    <label for="balance">Balance</label>
    <input type="text" name="balance" value="<?= htmlspecialchars($record['balance']) ?>" required>

    <label for="payment_method">Payment Method</label>
    <select name="payment_method" required>
      <option value="Cash" <?= $record['payment_method'] == 'Cash' ? 'selected' : '' ?>>Cash</option>
      <option value="Installment" <?= $record['payment_method'] == 'Installment' ? 'selected' : '' ?>>Installment</option>
    </select>

    <button type="submit">Update Tuition</button>
  </form>

  <a class="back-link" href="tuition.php">← Back to Tuition</a>
</div>

</body>
</html>
