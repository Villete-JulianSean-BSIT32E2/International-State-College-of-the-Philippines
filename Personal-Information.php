<?php
include 'connect.php';

$studentData = $guardianData = $documentData = [];
$student_status = '';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch from admission table
    $admissionQuery = $conn->prepare("SELECT * FROM admission WHERE id = ?");
    $admissionQuery->bind_param("i", $id);
    $admissionQuery->execute();
    $admissionResult = $admissionQuery->get_result();
    if ($admissionResult->num_rows > 0) {
        $studentData = $admissionResult->fetch_assoc();
    }

    // Fetch from guardian_info table
    $guardianQuery = $conn->prepare("SELECT * FROM guardian_info WHERE id = ?");
    $guardianQuery->bind_param("i", $id);
    $guardianQuery->execute();
    $guardianResult = $guardianQuery->get_result();
    if ($guardianResult->num_rows > 0) {
        $guardianData = $guardianResult->fetch_assoc();
    }

    // Fetch from student_documents table
    $documentQuery = $conn->prepare("SELECT * FROM student_documents WHERE id = ?");
    $documentQuery->bind_param("i", $id);
    $documentQuery->execute();
    $documentResult = $documentQuery->get_result();
    if ($documentResult->num_rows > 0) {
        $documentData = $documentResult->fetch_assoc();
    }

    // Detect student status from status tables
    if (!empty($studentData['name'])) {
        $name = $conn->real_escape_string($studentData['name']);
        $statusTables = ['transferee', 'old', 'irregular', 'new'];

        foreach ($statusTables as $table) {
            $check = $conn->query("SELECT * FROM `$table` WHERE name = '$name' LIMIT 1");
            if ($check && $check->num_rows > 0) {
                $student_status = ucfirst($table);
                break;
            }
        }
    }

} else {
    echo "<h3 style='color:red;'>No student ID provided in URL.</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
        }
        * { box-sizing: border-box; }
        .sidebar {
            width: 220px;
            background: #0d3c74;
            color: white;
            padding: 30px 25px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow: auto;
        }
        .main {
            margin-left: 220px;
            flex: 1;
            padding: 40px 60px;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar h2, .sidebar ul { margin: 0; padding: 0; }
        .sidebar ul { list-style: none; }
        .sidebar ul li {
            padding: 10px 0;
        }
        .sidebar ul li:hover {
            background-color: #1d4fa0;
            cursor: pointer;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
        }
        .field-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }
        .field {
            flex: 1;
            min-width: 200px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 4px;
            font-size: 14px;
        }
        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            width: 100%;
            padding: 5px;
            font-size: 13px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .documents label {
            font-weight: normal;
            font-size: 13px;
        }
        form { flex: 1; }
        .bottom-buttons {
            text-align: right;
            padding-top: 20px;
            padding-bottom: 40px;
        }
        .bottom-buttons button {
            padding: 10px 20px;
            margin-left: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }
        .bottom-buttons button:hover {
            background-color: #0056b3;
        }
        .bottom-buttons .back-btn {
            background-color: gray;
        }
        .bottom-buttons .back-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Dashboard</h2>
    <ul>
        <li>Admission</li>
        <li>Registrar</li>
        <li>Cashier</li>
        <li>Settings and Profile</li>
        <li>Exams</li>
    </ul>
</div>

<div class="main">
    <div>
        <h2>Student Info</h2>

       <?php if (empty($studentData)): ?>
    <p style="color: red;">No admission data found for this student.</p>
<?php else: ?>

        <form action="update_student.php" method="POST">
            <input type="hidden" name="admission_id" value="<?= $studentData['id'] ?>">
            <input type="hidden" name="guardian_id" value="<?= $guardianData['id'] ?>">

            <div class="section">
                <h3>Personal Information</h3>
                <div class="field-group">
                    <div class="field"><label>Full Name</label><input name="name" type="text" value="<?= $studentData['name'] ?>"></div>
                    <div class="field"><label>Birthdate</label><input name="bdate" type="date" value="<?= $studentData['bdate'] ?>"></div>
                    <div class="field"><label>Gender</label>
                        <select name="gender">
                            <option <?= $studentData['gender'] == 'm' ? 'selected' : '' ?>>Male</option>
                            <option <?= $studentData['gender'] == 'f' ? 'selected' : '' ?>>Female</option>
                            <option <?= $studentData['gender'] == 'o' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="field"><label>Religion</label><input name="religion" type="text" value="<?= $studentData['religion'] ?>"></div>
                    <div class="field"><label>Nationality</label><input name="nat" type="text" value="<?= $studentData['nat'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Contact Information</h3>
                <div class="field-group">
                    <div class="field"><label>Current Address</label><input name="curraddress" type="text" value="<?= $studentData['curraddress'] ?>"></div>
                    <div class="field"><label>Permanent Address</label><input name="peraddress" type="text" value="<?= $studentData['peraddress'] ?>"></div>
                    <div class="field"><label>City</label><input name="city" type="text" value="<?= $studentData['city'] ?>"></div>
                    <div class="field"><label>Province</label><input name="province" type="text" value="<?= $studentData['province'] ?>"></div>
                    <div class="field"><label>ZIP</label><input name="zip" type="text" value="<?= $studentData['zip'] ?>"></div>
                    <div class="field"><label>Phone Number</label><input name="phoneno" type="text" value="<?= $studentData['phoneno'] ?>"></div>
                    <div class="field"><label>Email ID</label><input name="email" type="email" value="<?= $studentData['email'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Parent’s Information</h3>
                 <?php if (empty($guardianData)): ?>
        <p style="color: orange;">Guardian information is missing.</p>
    <?php else: ?>
                <div class="field-group">
                    <div class="field"><label>Mother's Name</label><input name="mname" type="text" value="<?= $guardianData['mname'] ?>"></div>
                    <div class="field"><label>Occupation</label><input name="moccu" type="text" value="<?= $guardianData['moccu'] ?>"></div>
                    <div class="field"><label>Contact Information</label><input name="mno" type="text" value="<?= $guardianData['mno'] ?>"></div>
                    <div class="field"><label>Father's Name</label><input name="fname" type="text" value="<?= $guardianData['fname'] ?>"></div>
                    <div class="field"><label>Occupation</label><input name="foccu" type="text" value="<?= $guardianData['foccu'] ?>"></div>
                    <div class="field"><label>Contact Information</label><input name="fno" type="text" value="<?= $guardianData['fno'] ?>"></div>
                </div>
                <?php endif; ?>
            </div>

            <div class="section">
                <h3>Contact Person</h3>
                <div class="field-group">
                    <div class="field"><label>Guardian's Name</label><input name="gname" type="text" value="<?= $guardianData['gname'] ?>"></div>
                    <div class="field"><label>Relationship to Student</label><input name="relationship" type="text" value="<?= $guardianData['relationship'] ?>"></div>
                    <div class="field"><label>Contact Information</label><input name="gno" type="text" value="<?= $guardianData['gno'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Academic Information</h3>
                <?php if (empty($documentData)): ?>
        <p style="color: orange;">Academic information is missing.</p>
    <?php else: ?>
                <div class="field-group">
                    <div class="field"><label>Previous School</label><input name="prevschool" type="text" value="<?= $documentData['prevschool'] ?>"></div>
                    <div class="field"><label>Last Grade Completed</label><input name="last_grade" type="text" value="<?= $documentData['last_grade'] ?>"></div>
                    <div class="field"><label>Applying Grade</label><input name="applying_grade" type="text" value="<?= $documentData['applying_grade'] ?>"></div>
                    <div class="field"><label>Student Status</label><input name="status_std" type="text" value="<?= $documentData['status_std'] ?>"></div>
                    <div class="field"><label>Course</label><input name="Course" type="text" value="<?= $documentData['Course'] ?>"></div>

                    <?php endif; ?>

                </div>
            </div>
    </div>

    <div class="bottom-buttons">
        <button type="submit">Update</button>
        <a href="Post-AdmissionPreview.php"><button type="button" class="back-btn">Back</button></a>
    </div>

    </form>
    <?php endif; ?>
</div>

</body>
</html>
