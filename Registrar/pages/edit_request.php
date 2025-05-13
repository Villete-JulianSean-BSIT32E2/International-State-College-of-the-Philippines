<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['student_name'];
    $type = $_POST['request_type'];
    $status = $_POST['status'];
    $date = $_POST['date_requested'];

    $stmt = $conn->prepare("UPDATE requests SET student_name=?, request_type=?, status=?, date_requested=? WHERE id=?");
    $stmt->bind_param("ssssi", $name, $type, $status, $date, $id);
    $stmt->execute();
    header("Location: ../registrar.php?page=request_queue");
    exit;
}

$request = $conn->query("SELECT * FROM requests WHERE id = $id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Request</title>
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
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>✏️ Edit Request</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="student_name" class="form-label">Student Name</label>
            <input type="text" name="student_name" id="student_name" value="<?= htmlspecialchars($request['student_name']) ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="request_type" class="form-label">Request Type</label>
            <input type="text" name="request_type" id="request_type" value="<?= htmlspecialchars($request['request_type']) ?>" required class="form-control">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
                <option value="Pending" <?= $request['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $request['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Rejected" <?= $request['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="date_requested" class="form-label">Date Requested</label>
            <input type="date" name="date_requested" id="date_requested" value="<?= $request['date_requested'] ?>" required class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary">💾 Update Request</button>
        </div>
    </form>
</div>

</body>
</html>
