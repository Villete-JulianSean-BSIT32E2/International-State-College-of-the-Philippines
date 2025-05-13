<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$requests = $conn->query("SELECT * FROM requests ORDER BY date_requested DESC");

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM requests WHERE id = $id");
    header("Location: registrar.php?page=request_queue");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Request Queue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eaf6fb;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            margin-top: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.1);
            border-left: 6px solid #0d6efd;
        }
        h2 {
            color: #0d6efd;
            text-align: center;
            margin-bottom: 25px;
        }
        .table th {
            background-color: #cfe9fc;
        }
        .btn-sm {
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>📨 Request Queue</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="pages/add_request.php" class="btn btn-primary">➕ Add Request</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
           
                <th>Student Name</th>
                <th>Request Type</th>
                <th>Status</th>
                <th>Date Requested</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($requests->num_rows > 0): ?>
                <?php while ($row = $requests->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_name']) ?></td>
                        <td><?= htmlspecialchars($row['request_type']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= $row['date_requested'] ?></td>
                        <td>
                            <a href="pages/edit_request.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="registrar.php?page=request_queue&delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this request?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">No requests found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>
