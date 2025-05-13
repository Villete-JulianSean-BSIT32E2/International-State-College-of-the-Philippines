<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simple query - no JOIN needed
$schedules = $conn->query("SELECT * FROM class_schedules ORDER BY day, time ASC");
?>

<style>
	    a {
            color: #0b90d0;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }
</style>


<h2>Class Schedules</h2>
<a href="registrar.php?page=add_schedule" style="padding: 8px 16px; background: #3498db; color: white; text-decoration: none; border-radius: 4px;">Add Schedule</a>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px; border-collapse: collapse;">
    <thead style="background-color: #d9edf7;">
        <tr>
            <th>Subject</th>
            <th>Instructor</th>
            <th>Day</th>
            <th>Time</th>
            <th>Room</th>
            <th>Course</th>
            <th>Section</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($schedules->num_rows > 0): ?>
            <?php while ($row = $schedules->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['subject']) ?></td>
                    <td><?= htmlspecialchars($row['instructor']) ?></td>
                    <td><?= htmlspecialchars($row['day']) ?></td>
                    <td><?= htmlspecialchars($row['time']) ?></td>
                    <td><?= htmlspecialchars($row['room']) ?></td>
                    <td><?= htmlspecialchars($row['course']) ?></td>
                    <td><?= htmlspecialchars($row['section']) ?></td>
                    <td>
                        <a href="registrar.php?page=edit_schedule&id=<?= $row['id'] ?>">Edit</a> | 
                        <a href="registrar.php?page=delete_schedule&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this schedule?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8">No schedules found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
