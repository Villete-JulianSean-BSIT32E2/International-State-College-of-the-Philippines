<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM class_schedules WHERE id = $id");
$schedule = $result->fetch_assoc();

// Fetch courses
$courses = $conn->query("SELECT DISTINCT course FROM student_documents WHERE course IS NOT NULL AND course != ''");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'];
    $instructor = $_POST['instructor'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    $room = $_POST['room'];
    $course = $_POST['course'];
    $section = $_POST['section'];

    $stmt = $conn->prepare("UPDATE class_schedules SET subject=?, instructor=?, day=?, time=?, room=?, course=?, section=? WHERE id=?");
    $stmt->bind_param("sssssssi", $subject, $instructor, $day, $time, $room, $course, $section, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: registrar.php?page=class_schedules");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Schedule</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ecf8ff;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #2c3e50;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 0 10px rgba(0, 128, 255, 0.1);
            max-width: 500px;
        }
        label {
            font-weight: bold;
            color: #2c3e50;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 8px;
            margin: 6px 0 15px 0;
            border: 1px solid #b3d4fc;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #f4faff;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<h2>Edit Schedule</h2>
<form method="POST">
    <label>Subject:</label>
    <input type="text" name="subject" value="<?= htmlspecialchars($schedule['subject']) ?>" required>

    <label>Instructor:</label>
    <input type="text" name="instructor" value="<?= htmlspecialchars($schedule['instructor']) ?>" required>

    <label>Day:</label>
    <input type="text" name="day" value="<?= htmlspecialchars($schedule['day']) ?>" required>

    <label>Time:</label>
    <input type="text" name="time" value="<?= htmlspecialchars($schedule['time']) ?>" required>

    <label>Room:</label>
    <input type="text" name="room" value="<?= htmlspecialchars($schedule['room']) ?>" required>

    <label>Course:</label>
    <select name="course" required>
        <option value="">Select course</option>
        <?php while ($row = $courses->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['course']) ?>" <?= $schedule['course'] == $row['course'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['course']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Section:</label>
    <input type="text" name="section" value="<?= htmlspecialchars($schedule['section']) ?>" required>

    <button type="submit">Update</button>
</form>

</body>
</html>
