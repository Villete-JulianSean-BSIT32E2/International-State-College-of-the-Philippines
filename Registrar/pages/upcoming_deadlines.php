<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$today = date('Y-m-d');
$deadlines = $conn->query("SELECT * FROM deadlines WHERE due_date >= '$today' ORDER BY due_date ASC");
?>

<div class="container mt-4">
    <h2>📅 Upcoming Deadlines</h2>
    <p>Important upcoming academic and administrative deadlines:</p>

    <?php if ($deadlines->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($row = $deadlines->fetch_assoc()): ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($row['title']) ?></strong> 
                    (Due: <?= date('F d, Y', strtotime($row['due_date'])) ?>)
                    <br>
                    <small><?= htmlspecialchars($row['description']) ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info mt-3">No upcoming deadlines found.</div>
    <?php endif; ?>
</div>
