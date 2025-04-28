<?php
include 'connect.php';

// Fetch the latest student data safely
$admission = null;
$guardian = null;
$docs = null;
$student_status = '';
$course = '';

if ($conn && !$conn->connect_error) {
    $admission = $conn->query("SELECT * FROM admission ORDER BY id DESC LIMIT 1")->fetch_assoc();
    $guardian = $conn->query("SELECT * FROM guardian_info ORDER BY id DESC LIMIT 1")->fetch_assoc();
    $docs = $conn->query("SELECT * FROM student_documents ORDER BY id DESC LIMIT 1")->fetch_assoc();

    if ($admission && isset($admission['name'])) {
        $name = $conn->real_escape_string($admission['name']);
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
    die("Database connection failed.");
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
        * {
            box-sizing: border-box;
        }
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
        .sidebar h2, .sidebar ul {
            margin: 0;
            padding: 0;
        }
        .sidebar ul {
            list-style: none;
        }
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
        form {
            flex: 1;
        }
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

        <?php if (!$admission || !$guardian || !$docs): ?>
            <p style="color: red;">No student data found.</p>
        <?php else: ?>
        <form action="update_student.php" method="POST">
            <input type="hidden" name="admission_id" value="<?= $admission['id'] ?>">
            <input type="hidden" name="guardian_id" value="<?= $guardian['id'] ?>">

            <div class="section">
                <h3>Personal Information</h3>
                <div class="field-group">
                    <div class="field"><label>Full Name</label><input name="name" type="text" value="<?= $admission['name'] ?>"></div>
                    <div class="field"><label>Birthdate</label><input name="bdate" type="date" value="<?= $admission['bdate'] ?>"></div>
                    <div class="field"><label>Gender</label>
                        <select name="gender">
                            <option <?= $admission['gender'] == 'm' ? 'selected' : '' ?>>Male</option>
                            <option <?= $admission['gender'] == 'f' ? 'selected' : '' ?>>Female</option>
                            <option <?= $admission['gender'] == 'o' ? 'selected' : '' ?>>Other</option>
                        </select>
                    </div>
                    <div class="field"><label>Religion</label><input name="religion" type="text" value="<?= $admission['religion'] ?>"></div>
                    <div class="field"><label>Nationality</label><input name="nat" type="text" value="<?= $admission['nat'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Contact Information</h3>
                <div class="field-group">
                    <div class="field"><label>Current Address</label><input name="curraddress" type="text" value="<?= $admission['curraddress'] ?>"></div>
                    <div class="field"><label>Permanent Address</label><input name="peraddress" type="text" value="<?= $admission['peraddress'] ?>"></div>
                    <div class="field"><label>City</label><input name="city" type="text" value="<?= $admission['city'] ?>"></div>
                    <div class="field"><label>Province</label><input name="province" type="text" value="<?= $admission['province'] ?>"></div>
                    <div class="field"><label>ZIP</label><input name="zip" type="text" value="<?= $admission['zip'] ?>"></div>
                    <div class="field"><label>Phone Number</label><input name="phoneno" type="text" value="<?= $admission['phoneno'] ?>"></div>
                    <div class="field"><label>Email ID</label><input name="email" type="email" value="<?= $admission['email'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Parent’s Information</h3>
                <div class="field-group">
                    <div class="field"><label>Mother's Name</label><input name="mname" type="text" value="<?= $guardian['mname'] ?>"></div>
                    <div class="field"><label>Occupation</label><input name="moccu" type="text" value="<?= $guardian['moccu'] ?>"></div>
                    <div class="field"><label>Contact Information</label><input name="mno" type="text" value="<?= $guardian['mno'] ?>"></div>
                    <div class="field"><label>Father's Name</label><input name="fname" type="text" value="<?= $guardian['fname'] ?>"></div>
                    <div class="field"><label>Occupation</label><input name="foccu" type="text" value="<?= $guardian['foccu'] ?>"></div>
                    <div class="field"><label>Contact Information</label><input name="fno" type="text" value="<?= $guardian['fno'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Contact Person</h3>
                <div class="field-group">
                    <div class="field"><label>Guardian's Name</label><input name="gname" type="text" value="<?= $guardian['gname'] ?>"></div>
                    <div class="field"><label>Relationship to Student</label><input name="relationship" type="text" value="<?= $guardian['relationship'] ?>"></div>
                    <div class="field"><label>Contact Information</label><input name="gno" type="text" value="<?= $guardian['gno'] ?>"></div>
                </div>
            </div>

            <div class="section">
                <h3>Academic Information</h3>
                <div class="field-group">
                    <div class="field"><label>Previous School</label><input name="prevschool" type="text" value="<?= $docs['prevschool'] ?>"></div>
                    <div class="field"><label>Last Grade Completed</label><input name="last_grade" type="text" value="<?= $docs['last_grade'] ?>"></div>
                    <div class="field"><label>Applying Grade</label><input name="applying_grade" type="text" value="<?= $docs['applying_grade'] ?>"></div>

                    <div class="field"><label>Student Status</label><input name="status_std" type="text" value="<?= $docs['status_std'] ?>"></div>
                    <div class="field"><label>Course</label><input name="Course" type="text" value="<?= $docs['Course'] ?>"></div>
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
