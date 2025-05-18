<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
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
            margin-left: 250px;
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        h2 {
            margin-bottom: 20px;
        }

        form input, form select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        form input[type="submit"] {
            background-color: #112D4E;
            color: white;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .search-results div {
            background: #fff;
            padding: 8px;
            border: 1px solid #ccc;
            cursor: pointer;
        }

        .search-results div:hover {
            background: #f0f0f0;
        }
    </style>

    <script>
        function searchStudent() {
            const query = document.getElementById("search_student").value;
            if (query.length < 3) {
                document.getElementById("search_results").style.display = "none";
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open("GET", "search_student.php?query=" + query, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById("search_results").innerHTML = xhr.responseText;
                    document.getElementById("search_results").style.display = "block";
                }
            };
            xhr.send();
        }

        function selectStudent(admissionId, name, year_level, course) {
            document.getElementById("student_id").value = admissionId;
            document.getElementById("search_student").value = name;
            document.getElementById("search_results").style.display = "none";
    
            // Debug: Show what ID was selected
            console.log("Selected student ID:", admissionId);
        }
    </script>
</head>
<body>

<div class="sidebar">
  <div style="padding: 20px; text-align: center;">
    <img src="logo.jpg" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
  </div>
  <div style="padding: 10px;">
    <div class="nav-item"><i class="fa fa-tachometer"></i> <span>Dashboard</span></div>
    <a href="tuition.php" class="nav-item"><i class="fas fa-peso-sign"></i> <span>Tuition</span></a>
    <a href="Payments.php" class="nav-item active"><i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span></a>
    <a href="recievable.php" class="nav-item"><i class="fas fa-file-invoice"></i> <span>Receivables</span></a>
    <a href="soa.php" class="nav-item"><i class="fas fa-file-alt"></i> <span>Statement of Account</span></a>
    <a href="summary.php" class="nav-item"><i class="fas fa-list"></i> <span>Get Summary Reports</span></a>
  </div>
</div>

<div class="main-content">
    <h2>Manage Payments</h2>
    
    <?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $conn = new mysqli("localhost", "root", "", "iscpdb");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    ?>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (
            isset($_POST["student_id"]) &&
            isset($_POST["amount_paid"]) &&
            isset($_POST["payment_date"]) &&
            isset($_POST["payment_method"])
        ) {
            $student_id = $_POST["student_id"];
            $amount_paid = $_POST["amount_paid"];
            $payment_date = $_POST["payment_date"];
            $payment_method = $_POST["payment_method"];
            $payment_status = 'Paid'; // Status for the payments table

            $check_student = $conn->prepare("SELECT * FROM tbladmission_addstudent WHERE Admission_ID = ?");
            $check_student->bind_param("s", $student_id);
            $check_student->execute();
            $check_student->store_result();

            if ($check_student->num_rows == 0) {
                echo "<p style='color: red;'>Student with ID $student_id not found. Please try again.</p>";
            } else {
                // ✅ UPDATED QUERY to include `status`
                $insert = $conn->prepare("INSERT INTO payments (student_id, amount_paid, payment_date, payment_method, status) 
                        VALUES (?, ?, ?, ?, ?)");
                $insert->bind_param("sdsss", $student_id, $amount_paid, $payment_date, $payment_method, $payment_status);

                if ($insert->execute()) {
                    echo "<p style='color: green;'>Payment recorded successfully.</p>";

                    $status_check = $conn->prepare("SELECT * FROM tblstatus WHERE Admission_ID = ?");
                    $status_check->bind_param("s", $student_id);
                    $status_check->execute();
                    $status_check->store_result();

                    if ($status_check->num_rows == 0) {
                        $status_insert = $conn->prepare("INSERT INTO tblstatus (Admission_ID, enrollment_status, payment_status) 
                                              VALUES (?, 'Enrolled', 'Paid')");
                        $status_insert->bind_param("s", $student_id);
                        if (!$status_insert->execute()) {
                            echo "<p style='color: red;'>Error inserting status: " . $conn->error . "</p>";
                        }
                    } else {
                        $status_update = $conn->prepare("UPDATE tblstatus SET enrollment_status = 'Enrolled', payment_status = 'Paid' 
                                              WHERE Admission_ID = ?");
                        $status_update->bind_param("s", $student_id);
                        if (!$status_update->execute()) {
                            echo "<p style='color: red;'>Error updating status: " . $conn->error . "</p>";
                        }
                    }

                    $admission_update = $conn->prepare("UPDATE tbladmission_addstudent SET status = 'Enrolled' WHERE Admission_ID = ?");
                    $admission_update->bind_param("s", $student_id);
                    if (!$admission_update->execute()) {
                        echo "<p style='color: red;'>Error updating admission: " . $conn->error . "</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Error recording payment: " . $conn->error . "</p>";
                }
            }
        } else {
            echo "<p style='color: red;'>Please fill in all required fields.</p>";
        }
    }
    ?>

    <form method="POST" action="">
        <label for="search_student">Search Student:</label>
        <input type="text" id="search_student" oninput="searchStudent()" placeholder="Enter student name" required>
        <div id="search_results" class="search-results" style="display: none;"></div>
        <input type="hidden" id="student_id" name="student_id" required>

        <input type="number" step="0.01" name="amount_paid" placeholder="Amount Paid" required>
        <input type="date" name="payment_date" required>
        <select name="payment_method" required>
            <option value="">-- Select Payment Method --</option>
            <option value="Cash">Cash</option>
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="Gcash">Gcash</option>
        </select>
        <input type="submit" value="Record Payment">
    </form>

    <h3>Payment History</h3>
    <table>
        <tr>
            <th>Student Name</th>
            <th>Amount Paid</th>
            <th>Payment Date</th>
            <th>Payment Method</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT payments.*, tbladmission_addstudent.full_name 
                                       FROM payments
                                       JOIN tbladmission_addstudent ON payments.student_id = tbladmission_addstudent.Admission_ID
                                       ORDER BY payments.payment_date DESC");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['full_name']}</td>
                <td>{$row['amount_paid']}</td>
                <td>{$row['payment_date']}</td>
                <td>{$row['payment_method']}</td>
            </tr>";
        }
        ?>
    </table>
</div>

</body>
</html>
