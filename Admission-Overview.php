<?php
session_start();

// Ensure that data exists before displaying it
if (!isset($_SESSION['full_name'])) {
    header('Location: Admission-AddStudent.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admission Overview</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f5f6fa;
        }

        .sidebar {
            width: 240px;
            background-color: #0b2a5b;
            color: white;
            padding: 20px;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar .logo img {
            width: 120px;
        }

        .sidebar nav a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 15px;
            background-color: #1a3d7c;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
        }

        .main {
            flex: 1;
            padding: 40px;
            background-color: white;
        }

        h2 {
            margin-bottom: 20px;
            color: #1a1a1a;
        }

        .overview-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .overview-box img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .button-container {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .btn-next {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-next:hover {
            background-color: #FFDE59;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <img src="img/LOGO.png" alt="College Logo" />
        </div>
        <nav>
            <a href="#">Admission</a>
        </nav>
    </div>

    <div class="main">
        <h2>Admission Overview</h2>

        <div class="overview-box">
            <h3>Personal Information</h3>
            <p><strong>Full Name:</strong> <?= htmlspecialchars($_SESSION['full_name']); ?></p>
            <p><strong>Birthdate:</strong> <?= htmlspecialchars($_SESSION['date']); ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($_SESSION['gender']); ?></p>
            <p><strong>Nationality:</strong> <?= htmlspecialchars($_SESSION['nationality']); ?></p>
            <p><strong>Religion:</strong> <?= htmlspecialchars($_SESSION['religion']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($_SESSION['address']); ?></p>
            <p><strong>Province:</strong> <?= htmlspecialchars($_SESSION['province']); ?></p>
            <p><strong>ZIP Code:</strong> <?= htmlspecialchars($_SESSION['zip']); ?></p>
            <p><strong>City:</strong> <?= htmlspecialchars($_SESSION['city']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['email']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($_SESSION['phone']); ?></p>

            <?php if (!empty($_SESSION['photo'])): ?>
                <p><strong>Photo:</strong> <img src="<?= htmlspecialchars($_SESSION['photo']); ?>" alt="Uploaded Photo" /></p>
            <?php else: ?>
                <p><strong>Photo:</strong> No photo uploaded</p>
            <?php endif; ?>
        </div>

        <div class="overview-box">
            <h3>Guardian Information</h3>
            <p><strong>Father's Name:</strong> <?= htmlspecialchars($_SESSION['fathers_name']); ?></p>
            <p><strong>Father's Occupation:</strong> <?= htmlspecialchars($_SESSION['fathers_occupation']); ?></p>
            <p><strong>Father's Contact:</strong> <?= htmlspecialchars($_SESSION['father_contact']); ?></p>
            <p><strong>Mother's Name:</strong> <?= htmlspecialchars($_SESSION['mothers_name']); ?></p>
            <p><strong>Mother's Occupation:</strong> <?= htmlspecialchars($_SESSION['mothers_occupation']); ?></p>
            <p><strong>Mother's Contact:</strong> <?= htmlspecialchars($_SESSION['mother_contact']); ?></p>
            <p><strong>Guardian's Name:</strong> <?= htmlspecialchars($_SESSION['guardian_name']); ?></p>
            <p><strong>Guardian's Relationship:</strong> <?= htmlspecialchars($_SESSION['guardian_relationship']); ?></p>
            <p><strong>Guardian's Contact:</strong> <?= htmlspecialchars($_SESSION['guardian_contact']); ?></p>
        </div>

        <div class="overview-box">
            <h3>Academic Information</h3>
            <p><strong>Applying For Class/Grade:</strong> <?= htmlspecialchars($_SESSION['applying_grade']); ?></p>
            <p><strong>Previous School Name:</strong> <?= htmlspecialchars($_SESSION['prevschool']); ?></p>
            <p><strong>Last Grade Completed:</strong> <?= htmlspecialchars($_SESSION['last_grade']); ?></p>
            <p><strong>Course:</strong> <?= htmlspecialchars($_SESSION['Course']); ?></p>
            <p><strong>Student Type:</strong> <?= htmlspecialchars($_SESSION['Student_status']); ?></p>
        </div>

        <div class="overview-box">
            <h3>Uploaded Documents</h3>
            <?php
            if (!empty($_SESSION['documents'])) {
                foreach ($_SESSION['documents'] as $index => $docPath) {
                    $docName = basename($docPath);
                    echo "<p><strong>Document " . ($index + 1) . ":</strong> <a href='" . htmlspecialchars($docPath) . "' target='_blank'>" . htmlspecialchars($docName) . "</a></p>";
                }
            } else {
                echo "<p>No documents uploaded.</p>";
            }
            ?>
        </div>

        <div class="overview-box">
            <h3>Declaration</h3>
            <p><strong>Confirmation:</strong> <?= htmlspecialchars($_SESSION['confirm']); ?></p>
            <p><strong>Signature:</strong>
                <?php if (!empty($_SESSION['signature'])): ?>
                    <img src="<?= htmlspecialchars($_SESSION['signature']); ?>" alt="Signature" />
                <?php else: ?>
                    No signature uploaded.
                <?php endif; ?>
            </p>
            <p><strong>Date:</strong> <?= htmlspecialchars($_SESSION['sigdate']); ?></p>
        </div>

      <div class="button-container">
        <a href="Admission-AcademicInfo.php" style="margin-right: 10px;">
            <input type="button" class="btn-next" value="Previous" />
        </a>
        <a href="final_submit.php">
            <input type="button" class="btn-next" value="Submit" />
        </a>
      </div>
    </div>
</body>
</html>
