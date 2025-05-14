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

    <!-- Form to Add or Edit Subject -->
    <form method="post" action="">
        <input type="hidden" name="edit_id" value="<?= $_GET['edit_id'] ?? '' ?>">
        <input type="hidden" name="student_id" id="student_id" value="<?= $_GET['student_id'] ?? '' ?>">

        <div class="mb-3">
            <label for="student_search" class="form-label">Search Student</label>
            <div class="input-group">
                <input type="text" id="student_search" class="form-control" 
                       placeholder="Enter student name" 
                       value="<?= isset($_GET['edit_id']) ? htmlspecialchars($_GET['student_name']) : '' ?>">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#studentModal">
                    Search
                </button>
            </div>
            <div id="selected_student" class="mt-2 p-2 bg-light rounded">
                <?php if (isset($_GET['edit_id']) && isset($_GET['student_id'])): ?>
                    <?= htmlspecialchars($_GET['student_name']) ?> - <?= htmlspecialchars($_GET['student_course']) ?>
                <?php else: ?>
                    No student selected
                <?php endif; ?>
            </div>
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
            <label class="form-label">Teacher</label>
            <input type="text" name="teacher" class="form-control" required>
        </div>

        <button type="submit" name="save_subject" class="btn btn-success">Save Subject</button>
    </form>

    <!-- Student Search Modal -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentModalLabel">Search Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="GET" action="" style="margin-bottom: 15px;">
                        <input type="hidden" name="page" value="add_subjects">
                        <div class="input-group mb-3">
                            <input type="text" name="search" class="form-control" placeholder="Enter student name" 
                                   value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <?php if (!empty($_GET['registrar.php?page=Add_Subjects'])): ?>
                                <a href="?" class="btn btn-outline-secondary">Clear Search</a>
                            <?php endif; ?>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $search_term = $_GET['search'] ?? '';
                                $sql = "SELECT Admission_ID, full_name, course FROM tbladmission_addstudent";
                                if (!empty($search_term)) {
                                    $sql .= " WHERE full_name LIKE '%" . $conn->real_escape_string($search_term) . "%'";
                                }
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                                        <td><?= htmlspecialchars($row['course']) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary select-student" 
                                                    data-id="<?= $row['Admission_ID'] ?>"
                                                    data-name="<?= htmlspecialchars($row['full_name']) ?>"
                                                    data-course="<?= htmlspecialchars($row['course']) ?>">
                                                Select
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Handle Insert or Update -->
    <?php
    if (isset($_POST['save_subject'])) {
        $student_id = $_POST['student_id'];
        $subject = $conn->real_escape_string($_POST['subject']);
        $days = $conn->real_escape_string($_POST['days']);
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $room = $conn->real_escape_string($_POST['room']);
        $teacher = $conn->real_escape_string($_POST['teacher']);

        if (!empty($_POST['edit_id'])) {
            // Update existing subject
            $edit_id = $_POST['edit_id'];
            $sql = "UPDATE student_subjects SET 
                        student_id = '$student_id',
                        subject = '$subject',
                        days = '$days',
                        start_time = '$start_time',
                        end_time = '$end_time',
                        room = '$room',
                        teacher = '$teacher'
                    WHERE id = $edit_id";
            $conn->query($sql);
            echo "<div class='alert alert-warning mt-3'>Subject updated.</div>";
        } else {
            // Insert new subject
            $sql = "INSERT INTO student_subjects 
                    (student_id, subject, days, start_time, end_time, room, teacher) 
                    VALUES ('$student_id', '$subject', '$days', '$start_time', '$end_time', '$room', '$teacher')";
            $conn->query($sql);
            echo "<div class='alert alert-success mt-3'>Subject added.</div>";
        }
    }

    // Handle Delete
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
            <th>Student</th>
            <th>Course</th>
            <th>Subject</th>
            <th>Days</th>
            <th>Time</th>
            <th>Room</th>
            <th>Teacher</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT ss.*, s.full_name, s.course 
                FROM student_subjects ss 
                JOIN tbladmission_addstudent s ON ss.student_id = s.Admission_ID";
        $res = $conn->query($sql);
        while ($row = $res->fetch_assoc()):
        ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']); ?></td>
                <td><?= htmlspecialchars($row['course']); ?></td>
                <td><?= htmlspecialchars($row['subject']); ?></td>
                <td><?= htmlspecialchars($row['days']); ?></td>
                <td><?= $row['start_time'] . " - " . $row['end_time']; ?></td>
                <td><?= htmlspecialchars($row['room']); ?></td>
                <td><?= htmlspecialchars($row['teacher']); ?></td>
                <td>
                   <a href="?page=add_subjects&delete_id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>

                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Handle student selection from modal
    document.querySelectorAll('.select-student').forEach(button => {
        button.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            const studentName = this.getAttribute('data-name');
            const studentCourse = this.getAttribute('data-course');
            
            // Set the hidden input value
            document.getElementById('student_id').value = studentId;
            
            // Update the display
            document.getElementById('selected_student').innerHTML = 
                `${studentName} - ${studentCourse}`;
            
            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('studentModal'));
            modal.hide();
        });
    });
</script>
</body>
</html>