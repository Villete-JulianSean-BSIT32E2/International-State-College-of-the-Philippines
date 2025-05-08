<?php
session_start(); // Start session if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admission Overview</title>

  <!-- Include any shared styles here -->
  <link rel="stylesheet" href="css/Admission-AddStudent.css">
  <link rel="stylesheet" href="css/Admission-GuardianInfo.css">
  <link rel="stylesheet" href="css/Admission-AcademicInfo.css">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f9f9f9;
    }

    .overview-container {
      display: flex;
      flex-direction: column;
      gap: 40px; /* space between each form section */
      max-width: 1000px;
      margin: 0 auto;
    }

    section {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    section h2 {
      margin-top: 0;
    }
  </style>
</head>
<body>

  <div class="overview-container">

    <!-- Add Student Form -->
    <section id="add-student-section">
      <h2>Add Student</h2>
      <?php include 'Admission-AddStudent.php'; ?>
    </section>

    <!-- Guardian Info Form -->
    <section id="guardian-section">
      <h2>Guardian Information</h2>
      <?php include 'Admission-GuardianInfo.php'; ?>
    </section>

    <!-- Academic Info Form -->
    <section id="academic-section">
      <h2>Academic Information</h2>
      <?php include 'Admission-AcademicInfo.php'; ?>
    </section>

  </div>

</body>
</html>
