<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $subject = $_POST['subject'];
    $grade = $_POST['grade'];
    $semester = $_POST['semester'];
    $school_year = $_POST['school_year'];
    $remarks = $_POST['remarks'];

    $stmt = $conn->prepare("INSERT INTO student_grades (student_id, subject, grade, semester, school_year, remarks) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $student_id, $subject, $grade, $semester, $school_year, $remarks);

    if ($stmt->execute()) {
        $message = "Grade successfully added.";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Get students from admission table
$students = $conn->query("SELECT id, name FROM admission ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student Grades</title>
    <style>
        /* General Body Styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f8ff; /* Light Blue background */
            color: #333;
        }

        /* Centering the content and giving it a nice box */
        .container {
            width: 80%;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #007bff; /* Light blue color */
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
            font-size: 16px;
        }

        input, select {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            width: 100%;
        }

        /* Button Styling */
        button {
            padding: 10px 15px;
            background-color: #007bff; /* Light blue */
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Success Message */
        .message {
            color: #28a745; /* Green */
            text-align: center;
            margin-bottom: 20px;
        }

        /* Back Link */
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Add Student Grades</h2>

        <?php if (!empty($message)): ?>
            <p class="message"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST">
            <label>Select Student:</label>
            <select name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php while ($row = $students->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Subject:</label>
            <input type="text" name="subject" required>

            <label>Grade:</label>
            <input type="text" name="grade" required>

            <label>Semester:</label>
            <input type="text" name="semester" required>

            <label>School Year:</label>
            <input type="text" name="school_year" required>
            <label>Remarks</label>
            <input type="text" name="remarks" required>


            <button type="submit">Save Grade</button>
            <a href="/International-State-College-of-the-Philippines/Registrar/registrar.php?page=grades_transcripts">Cancel</a>
        </form>

        
    </div>

</body>
</html>
