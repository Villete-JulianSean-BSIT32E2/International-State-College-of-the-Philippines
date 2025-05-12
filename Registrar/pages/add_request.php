<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['student_name'];
    $type = $_POST['request_type'];
    $status = $_POST['status'];
    $date = $_POST['date_requested'];

    $stmt = $conn->prepare("INSERT INTO requests (student_name, request_type, status, date_requested) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $type, $status, $date);
    $stmt->execute();
    header("Location: ../registrar.php?page=request_queue");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf6fb;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 60px auto;
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
            border-left: 6px solid #0d6efd;
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #0d6efd;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .btn-success {
            background-color: #198754;
            border: none;
        }
        .btn-success:hover {
            background-color: #157347;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>➕ Add New Request</h2>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Student Name</label>
            <input type="text" name="student_name" placeholder="Student Name" required class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Request Type</label>
            <input type="text" name="request_type" placeholder="Request Type" required class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-control" required>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Date Requested</label>
            <input type="date" name="date_requested" required class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-success">💾 Save Request</button>
        </div>
    </form>
</div>

</body>
</html>
