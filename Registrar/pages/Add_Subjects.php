<?php include '../connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Subjects</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h3 class="mb-4">Add / Manage Subjects</h3>

    <!-- Subject Form -->
    <form method="post" action="">
        <input type="hidden" name="edit_id" value="<?php echo isset($_GET['edit_id']) ? $_GET['edit_id'] : ''; ?>">

        <div class="mb-3">
            <label for="student_id" class="form-label">Select Student</label>
            <select name="student_id" id="student_id" class="form-control" required>
                <option value="">-- Select Student --</option>
                <?php
                $students = $conn->query("SELECT Admission_ID, full_name FROM tbladmission_addstudent");
                while ($s = $students->fetch_assoc()):
                ?>
                    <option value="<?= $s['Admission_ID'] ?>" <?= (isset($_POST['student_id']) && $_POST['student_id'] == $s['Admission_ID']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['full_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text" name="subject" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Days</label>
            <input type="text" name="days" class="form-control" placeholder="e.g. Mon/Wed/Fri" required>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Start Time</label>
                <input type="time" name="start_time" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">End Time</label>
                <input type="time" name="end_time" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Room</label>
            <input type="text" name="room" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Section</label>
            <input type="text" name="section" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Professor</label>
            <input type="text" name="teacher" class="form-control" required>
        </div>

        <button type="submit" name="save_subject" class="btn btn-success">Save Subject</button>
    </form>

    <!-- PHP: Insert subject -->
    <?php
    if (isset($_POST['save_subject'])) {
        $student_id = $_POST['student_id'];
        $subject = $conn->real_escape_string($_POST['subject']);
        $days = $conn->real_escape_string($_POST['days']);
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $room = $conn->real_escape_string($_POST['room']);
        $section = $conn->real_escape_string($_POST['section']);
        $teacher = $conn->real_escape_string($_POST['teacher']);

        if (!empty($_POST['edit_id'])) {
            $edit_id = $_POST['edit_id'];
            $conn->query("UPDATE student_subjects SET 
                student_id='$student_id', subject='$subject', days='$days', 
                start_time='$start_time', end_time='$end_time', room='$room', 
                section='$section', teacher='$teacher' WHERE id=$edit_id");
            echo "<div class='alert alert-warning mt-3'>Subject updated.</div>";
        } else {
            $conn->query("INSERT INTO student_subjects 
                (student_id, subject, days, start_time, end_time, room, section, teacher) 
                VALUES ('$student_id', '$subject', '$days', '$start_time', '$end_time', '$room', '$section', '$teacher')");
            echo "<div class='alert alert-success mt-3'>Subject added.</div>";
        }
    }

    // Delete subject
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $conn->query("DELETE FROM student_subjects WHERE id = $delete_id");
        echo "<div class='alert alert-danger mt-3'>Subject deleted.</div>";
    }
    ?>

    <!-- Subject List -->
    <h5 class="mt-5">Assigned Subjects</h5>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
        <tr>
            <th>Student Name</th>
            <th>Course</th>
            <th>Status</th>
            <th>Section</th>
            <th>Subject</th>
            <th>Days</th>
            <th>Time</th>
            <th>Room</th>
            <th>Professor</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $res = $conn->query("SELECT ss.*, s.full_name, s.course, s.status 
                             FROM student_subjects ss 
                             JOIN tbladmission_addstudent s ON ss.student_id = s.Admission_ID");
        while ($row = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td><?= htmlspecialchars($row['course']); ?></td>
                <td><?= htmlspecialchars($row['status']); ?></td>
                <td><?= htmlspecialchars($row['section']); ?></td>
                <td><?= htmlspecialchars($row['subject']); ?></td>
                <td><?= htmlspecialchars($row['days']); ?></td>
                <td><?= $row['start_time'] . " - " . $row['end_time']; ?></td>
                <td><?= htmlspecialchars($row['room']); ?></td>
                <td><?= htmlspecialchars($row['teacher']); ?></td>
                <td>
                <a href="registrar.php?page=Add_Subjects&delete_id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subject?');">Delete</a>

                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
