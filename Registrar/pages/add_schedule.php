<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $instructor = $_POST['instructor'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    $room = $_POST['room'];
    $course = $_POST['course'];
    $section = $_POST['section'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO class_schedules (subject, instructor, day, time, room, course, section) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Check if prepare failed
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssssss", $subject, $instructor, $day, $time, $room, $course, $section);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('Schedule added successfully'); window.location.href='registrar.php?page=class_schedules';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch unique courses from student_documents
$courses = $conn->query("SELECT DISTINCT course FROM tbladmission_addstudent WHERE course IS NOT NULL AND course != '' ORDER BY course ASC");
?>

<h2>Add Class Schedule</h2>
<form method="POST" style="max-width: 600px; margin: 0 auto;">
    <label>Subject:</label><br>
    <input type="text" name="subject" required class="form-control"><br>

    <label>Instructor:</label><br>
    <input type="text" name="instructor" required class="form-control"><br>

    <label>Day:</label><br>
    <select name="day" required class="form-control">
        <option value="">Select Day</option>
        <option>Monday</option>
        <option>Tuesday</option>
        <option>Wednesday</option>
        <option>Thursday</option>
        <option>Friday</option>
    </select><br>

    <label>Time:</label><br>
    <input type="text" name="time" placeholder="e.g., 8:00 AM - 10:00 AM" required class="form-control"><br>

    <label>Room:</label><br>
    <input type="text" name="room" required class="form-control"><br>

    <label>Course:</label><br>
    <select name="course" required class="form-control">
        <option value="">Select Course</option>
        <?php while ($row = $courses->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['course']) ?>"><?= htmlspecialchars($row['course']) ?></option>
        <?php endwhile; ?>
    </select><br>

    <label>Section:</label><br>
    <input type="text" name="section" required class="form-control"><br>

    <button type="submit" style="background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px;">Add Schedule</button>
</form>
