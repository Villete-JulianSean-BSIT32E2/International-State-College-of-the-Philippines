<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $conn->real_escape_string($_POST['student_id']);
    
    // Prepare document statuses (1 for cleared, 0 for pending)
    $birth_cert = isset($_POST['birth_cert']) ? 1 : 0;
    $form137 = isset($_POST['form137']) ? 1 : 0;
    $tor = isset($_POST['tor']) ? 1 : 0;
    $good_moral = isset($_POST['good_moral']) ? 1 : 0;
    $honorable_dismissal = isset($_POST['honorable_dismissal']) ? 1 : 0;
    
    // Check if record exists
    $check = $conn->query("SELECT * FROM student_documents WHERE Admission_ID = '$student_id'");
    
    if ($check->num_rows > 0) {
        // Update existing record
        $sql = "UPDATE student_documents SET 
                birth_cert = $birth_cert,
                form137 = $form137,
                tor = $tor,
                good_moral = $good_moral,
                honorable_dismissal = $honorable_dismissal
                WHERE Admission_ID = '$student_id'";
    } else {
        // Insert new record
        $sql = "INSERT INTO student_documents 
                (Admission_ID, birth_cert, form137, tor, good_moral, honorable_dismissal)
                VALUES ('$student_id', $birth_cert, $form137, $tor, $good_moral, $honorable_dismissal)";
    }
    
    $conn->query($sql);
    header("Location: registrar.php?page=student_records");
    exit();
}

// Get student info
$student_id = $conn->real_escape_string($_GET['id']);
$student = $conn->query("SELECT * FROM tbladmission_addstudent WHERE Admission_ID = '$student_id'")->fetch_assoc();
$documents = $conn->query("SELECT * FROM student_documents WHERE Admission_ID = '$student_id'")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student Documents</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .form-container { max-width: 600px; margin: 0 auto; }
        .document-item { margin: 15px 0; padding: 10px; border: 1px solid #ddd; }
        .btn-submit { background: #0b2a5b; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .btn-cancel { background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Documents for <?php echo htmlspecialchars($student['full_name']); ?></h2>
        
        <form method="POST">
            <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
            
            <div class="document-item">
                <label>
                    <input type="checkbox" name="birth_cert" <?php echo (isset($documents['birth_cert']) && $documents['birth_cert']) ? 'checked' : ''; ?>>
                    Birth Certificate - Cleared
                </label>
            </div>
            
            <div class="document-item">
                <label>
                    <input type="checkbox" name="form137" <?php echo (isset($documents['form137']) && $documents['form137']) ? 'checked' : ''; ?>>
                    Form 137 - Cleared
                </label>
            </div>
            
            <div class="document-item">
                <label>
                    <input type="checkbox" name="tor" <?php echo (isset($documents['tor']) && $documents['tor']) ? 'checked' : ''; ?>>
                    TOR - Cleared
                </label>
            </div>
            
            <div class="document-item">
                <label>
                    <input type="checkbox" name="good_moral" <?php echo (isset($documents['good_moral']) && $documents['good_moral']) ? 'checked' : ''; ?>>
                    Good Moral - Cleared
                </label>
            </div>
            
            <div class="document-item">
                <label>
                    <input type="checkbox" name="honorable_dismissal" <?php echo (isset($documents['honorable_dismissal']) && $documents['honorable_dismissal']) ? 'checked' : ''; ?>>
                    Honorable Dismissal - Cleared
                </label>
            </div>
            
            <button type="submit" class="btn-submit">Update Documents</button>
            <a href="registrar.php?page=student_records" class="btn-cancel">Cancel</a>
        </form>
    </div>
</body>
</html>