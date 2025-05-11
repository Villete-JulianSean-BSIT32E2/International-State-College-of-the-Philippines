<?php
session_start();
include 'connect.php';

// Check if ID parameter exists
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admission.php');
    exit();
}

$student_id = intval($_GET['id']);
$student = [];

// 1. Verify database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// 2. Prepare and execute main student query
$student_query = "
    SELECT a.*, s.StudentType 
    FROM tbladmission_addstudent a
    LEFT JOIN tbladmission_studenttype s ON a.Admission_ID = s.Admission_ID
    WHERE a.Admission_ID = ?
";

$stmt = $conn->prepare($student_query);

if ($stmt === false) {
    die("Error preparing student query: " . $conn->error);
}

if (!$stmt->bind_param("i", $student_id)) {
    die("Error binding parameters: " . $stmt->error);
}

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $student = $result->fetch_assoc();
} else {
    header('Location: admission.php');
    exit();
}

// Student type labels
$typeLabels = array(
    'new' => 'New Student',
    'old' => 'Returning Student',
    'irregular' => 'Irregular Student'
);
$studentType = isset($student['StudentType']) ? $student['StudentType'] : 'new';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Admission - <?= htmlspecialchars($student['full_name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

</head>

<style>
        * {
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f6f8fb;
            color: #333;
        }
        .container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 240px;
            background-color: #0b2a5b;
            color: white;
            padding: 20px;
        }
        .sidebar img {
            width: 120px;
            margin-bottom: 30px;
        }
        .nav-item {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px;
            background-color: #1a3d7c;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
        }
        .main-content {
            flex: 1;
            padding: 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .back-btn {
            background-color: #0b2a5b;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .section {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .section h2 {
            color: #0b2a5b;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-item strong {
            display: block;
            color: #666;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .info-item span {
            font-size: 16px;
        }
        .document-list {
            list-style: none;
        }
        .document-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .document-list li:last-child {
            border-bottom: none;
        }
        .document-link {
            color: #0b2a5b;
            text-decoration: none;
        }
        .document-link:hover {
            text-decoration: underline;
        }
        .photo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .student-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0b2a5b;
        }
    </style>
<body>
    <div class="container">
        <div class="sidebar">
            <img src="img/LOGO.png" alt="College Logo">
            <a href="admission.php" class="nav-item">Back to Admissions</a>
            <a href="#" class="nav-item">Dashboard</a>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Student Admission Details</h1>
                <a href="admission.php" class="back-btn">← Back to List</a>
            </div>

            <div class="section">
                <div class="photo-container">
                    <?php if (!empty($student['photo'])): ?>
                        <img src="<?= htmlspecialchars($student['photo']) ?>" alt="Student Photo" class="student-photo">
                    <?php else: ?>
                        <div style="width:150px;height:150px;background:#eee;border-radius:50%;margin:0 auto;"></div>
                    <?php endif; ?>
                </div>
                
                <h2>Personal Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Full Name</strong>
                        <span><?= htmlspecialchars($student['full_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Student Type</strong>
                        <span><?= htmlspecialchars($typeLabels[$studentType]) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Birthdate</strong>
                        <span><?= htmlspecialchars($student['birthdate']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Gender</strong>
                        <span><?= htmlspecialchars($student['gender']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Nationality</strong>
                        <span><?= htmlspecialchars($student['nationality']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Religion</strong>
                        <span><?= htmlspecialchars($student['religion']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Email</strong>
                        <span><?= htmlspecialchars($student['email']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Phone</strong>
                        <span><?= htmlspecialchars($student['phone']) ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Address Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Address</strong>
                        <span><?= htmlspecialchars($student['address']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>City</strong>
                        <span><?= htmlspecialchars($student['city']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Province</strong>
                        <span><?= htmlspecialchars($student['province']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>ZIP Code</strong>
                        <span><?= htmlspecialchars($student['zip']) ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Family Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Father's Name</strong>
                        <span><?= htmlspecialchars($student['fathers_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Father's Occupation</strong>
                        <span><?= htmlspecialchars($student['fathers_occupation']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Father's Contact</strong>
                        <span><?= htmlspecialchars($student['father_contact']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Mother's Name</strong>
                        <span><?= htmlspecialchars($student['mothers_name']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Mother's Occupation</strong>
                        <span><?= htmlspecialchars($student['mothers_occupation']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Mother's Contact</strong>
                        <span><?= htmlspecialchars($student['mother_contact']) ?></span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2>Academic Information</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Applying For Grade/Year</strong>
                        <span><?= htmlspecialchars($student['applying_grade']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Course</strong>
                        <span><?= htmlspecialchars($student['course']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Previous School</strong>
                        <span><?= htmlspecialchars($student['prevschool']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Last Grade Completed</strong>
                        <span><?= htmlspecialchars($student['last_grade']) ?></span>
                    </div>
                </div>
            </div>

            <?php if (!empty($documents)): ?>
            <div class="section">
                <h2>Submitted Documents</h2>
                <ul class="document-list">
                    <?php foreach ($documents as $doc): ?>
                        <li>
                            <a href="<?= htmlspecialchars($doc['document_path']) ?>" target="_blank" class="document-link">
                                <?= htmlspecialchars($doc['document_name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div class="section">
                <h2>Declaration</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Confirmation</strong>
                        <span><?= htmlspecialchars($student['confirm']) ?></span>
                    </div>
                    <div class="info-item">
                        <strong>Signature Date</strong>
                        <span><?= htmlspecialchars($student['sigdate']) ?></span>
                    </div>
                </div>
                <?php if (!empty($student['signature_path'])): ?>
                    <div style="margin-top: 20px;">
                        <strong>Signature:</strong><br>
                        <img src="<?= htmlspecialchars($student['signature_path']) ?>" alt="Signature" style="max-width: 300px; margin-top: 10px;">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>