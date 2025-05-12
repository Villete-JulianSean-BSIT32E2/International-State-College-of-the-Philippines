<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get student ID from URL
$student_id = isset($_GET['student_id']) ? (int)$_GET['student_id'] : 0;

// Fetch student data from tbladmission_addstudent
$sql = "SELECT Admission_ID, full_name, course, student_type 
        FROM tbladmission_addstudent
        WHERE Admission_ID = $student_id
        LIMIT 1";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = $conn->real_escape_string($_POST['course']);
    $student_type = $conn->real_escape_string($_POST['student_type']);

    // Update record in tbladmission_addstudent
    $update_sql = "UPDATE tbladmission_addstudent 
                  SET course = '$course', 
                      student_type = '$student_type' 
                  WHERE Admission_ID = $student_id";
    
    if ($conn->query($update_sql)) {
        echo "<p style='color: green;'>Enrollment updated successfully.</p>";
        
        // Reload updated student data
        $result = $conn->query($sql);
        $student = $result->fetch_assoc();
    } else {
        echo "<p style='color: red;'>Error updating record: " . $conn->error . "</p>";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #eaf6fb;
        margin: 20px;
    }

    h2 {
        color: #0b3d91;
    }

    form {
        background-color: #ffffff;
        border: 1px solid #cce7f7;
        padding: 20px;
        border-radius: 8px;
        max-width: 500px;
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #0b3d91;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        border: 1px solid #a7d3f3;
        border-radius: 4px;
        background-color: #f0faff;
    }

    button {
        margin-top: 20px;
        background-color: #0b90d0;
        color: white;
        padding: 10px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    button:hover {
        background-color: #087bb5;
    }

    a {
        display: inline-block;
        margin-left: 15px;
        color: #0b3d91;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    p {
        font-size: 14px;
        color: #333;
    }
</style>

<h2>Edit Enrollment</h2>

<?php if ($student): ?>
    <form method="POST">
        <p><strong>Name:</strong> <?= htmlspecialchars($student['full_name']) ?></p>

        <label for="course">Course:</label>
        <input type="text" name="course" id="course" value="<?= htmlspecialchars(isset($student['course']) ? $student['course'] : '') ?>" required>

        <label for="student_type">Student Type:</label>
        <select name="student_type" id="student_type" required>
            <option value="">-- Select Type --</option>
            <option value="New" <?= isset($student['student_type']) && $student['student_type'] == 'New' ? 'selected' : '' ?>>New</option>
<option value="Old" <?= isset($student['student_type']) && $student['student_type'] == 'Old' ? 'selected' : '' ?>>Old</option>
<option value="Irregular" <?= isset($student['student_type']) && $student['student_type'] == 'Irregular' ? 'selected' : '' ?>>Irregular</option>
        </select>

        <button type="submit">Save Changes</button>
        <a href="/International-State-College-of-the-Philippines/Registrar/registrar.php?page=enrollment_management">Back</a>
    </form>
<?php else: ?>
    <p>Student not found.</p>
<?php endif; ?>