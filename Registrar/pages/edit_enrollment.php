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

// Fetch student data (only name from `admission`, course/status from `student_documents`)
$sql = "SELECT a.id, a.name, d.course, d.status_std 
        FROM admission a
        LEFT JOIN student_documents d ON a.id = d.id
        WHERE a.id = $student_id
        LIMIT 1";
$result = $conn->query($sql);
$student = $result->fetch_assoc();

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = $conn->real_escape_string($_POST['course']);
    $status_std = $conn->real_escape_string($_POST['status_std']);

    // Check if record exists in student_documents
    $check = $conn->query("SELECT * FROM student_documents WHERE id = $student_id");
    if ($check->num_rows > 0) {
        // Update
        $conn->query("UPDATE student_documents SET course = '$course', status_std = '$status_std' WHERE id = $student_id");
    } else {
        // Insert
        $conn->query("INSERT INTO student_documents (id, course, status_std) VALUES ($student_id, '$course', '$status_std')");
    }

    echo "<p style='color: green;'>Enrollment updated successfully.</p>";

    // Reload student data
    $result = $conn->query($sql);
    $student = $result->fetch_assoc();
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
        <p><strong>Name:</strong> <?= htmlspecialchars($student['name']) ?></p>

        <label for="course">Course:</label>
        <input type="text" name="course" id="course" value="<?= htmlspecialchars($student['course']) ?>" required><br><br>

        <label for="status_std">Status:</label>
        <select name="status_std" id="status_std" required>
            <option value="">-- Select Status --</option>
            <option value="New" <?= $student['status_std'] == 'New' ? 'selected' : '' ?>>New</option>
            <option value="Transferee" <?= $student['status_std'] == 'Transferee' ? 'selected' : '' ?>>Transferee</option>
            <option value="Old" <?= $student['status_std'] == 'Old' ? 'selected' : '' ?>>Old</option>
            <option value="Irregular" <?= $student['status_std'] == 'Irregular' ? 'selected' : '' ?>>Irregular</option>
        </select><br><br>

        <button type="submit">Save Changes</button>
        <a href="/International-State-College-of-the-Philippines/Registrar/registrar.php?page=enrollment_management">Cancel</a>
    </form>
<?php else: ?>
    <p>Student not found.</p>
<?php endif; ?>
