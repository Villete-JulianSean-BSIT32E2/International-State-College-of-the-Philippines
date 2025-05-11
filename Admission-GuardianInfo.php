<?php
session_start();

// Save posted form data to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $_SESSION['fathers_name'] = $_POST['fathers_name'] ?? '';
  $_SESSION['mothers_name'] = $_POST['mothers_name'] ?? '';
  $_SESSION['fathers_occupation'] = $_POST['fathers_occupation'] ?? '';
  $_SESSION['mothers_occupation'] = $_POST['mothers_occupation'] ?? '';
  $_SESSION['father_contact'] = $_POST['father_contact'] ?? '';
  $_SESSION['mother_contact'] = $_POST['mother_contact'] ?? '';
  $_SESSION['guardian_name'] = $_POST['guardian_name'] ?? '';
  $_SESSION['guardian_relationship'] = $_POST['guardian_relationship'] ?? '';
  $_SESSION['guardian_contact'] = $_POST['guardian_contact'] ?? '';

   // Redirect to academic info form
  header("Location: Admission-AcademicInfo.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admission Form</title>
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

    .form-section {
      margin-bottom: 30px;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px 0px; /* reduce the column gap */
      
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    #InCase {
      margin-top: 50px;
    }

    .form-grid1 {
  
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0px 50px; /* reduce the column gap */
      
    }

    .form-group1 {
      display: flex;
      flex-direction: column;
    }

    .form-group1 label {
      margin-bottom: 5px;

      color: #8A8A8A;
    }

    .form-group label {
      margin-bottom: 5px;

      color: #8A8A8A;
    }

    #textbox {
      width: 340px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    form {
      width: 100%;
      display: contents; /* Prevent layout interference */
    }
    .mothers-group {
      margin-left: -400px; /* adjust to pull left */
    }

    .form-group input, .form-group select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .button-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: flex;
        gap: 10px;
        z-index: 1000;
    }

    .btn-previous,.btn-next {
        padding: 10px 25px;
        border: none;
        border-radius: 6px;
        background-color: #3498db;
        color: white;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-next {
       padding: 10px 40px;
    }

    .btn-previous:hover, .btn-next:hover {
        background-color: #FFDE59;
    }


    @media (max-width: 768px) {
      .form-grid {
        grid-template-columns: 1fr;
      }
      .sidebar {
        width: 100%;
        display: none;
      }
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

<form method="POST">
 <!-- Fathers Name  -->
  <div class="main">
    <h2>Parent Information</h2>
    <div class="form-grid">
      <div class="form-group fathers-group">
      <label>Father's Full Name</label>
        <input type="text" id="textbox" name="fathers_name" value="<?= $_SESSION['fathers_name'] ?? '' ?>" />
      </div>

      <!-- Mothers Name  -->
      <div class="form-group mothers-group">
        <label>Mother's Full Name</label>
       <input type="text" id="textbox" name="mothers_name" value="<?= $_SESSION['mothers_name'] ?? '' ?>" />
      </div>


      <!-- Fathers Occupation  -->
      <div class="form-group">
      <label>Occupation</label>
       <input type="text" id="textbox" name="fathers_occupation" value="<?= $_SESSION['fathers_occupation'] ?? '' ?>" /> 
      </div>

       <!-- Mothers Occupation  -->
       <div class="form-group mothers-group">
      <label>Occupation</label>
        <input type="text" id="textbox" name="mothers_occupation" value="<?= $_SESSION['mothers_occupation'] ?? '' ?>" />
      </div>

      <!-- Fathers Contact Number  -->
      <div class="form-group fathers-group">
        <label>Contact Number</label>
        <input type="text" id="textbox" name="father_contact" value="<?= $_SESSION['father_contact'] ?? '' ?>" />
      </div>

       <!-- Mothers Contact Number  -->
       <div class="form-group mothers-group">
        <label>Contact Number</label>
        <input type="text" id="textbox" name="mother_contact" value="<?= $_SESSION['mother_contact'] ?? '' ?>" />
      </div>

    <h2 id="InCase">In Case of Emergency</h2>
    <br><br>
    <!-- Guardian Name  -->
    <div class="form-grid1">
      <div class="form-group1">
        <label>Guardian's Name</label>
        <input type="text" id="textbox" name="guardian_name" value="<?= $_SESSION['guardian_name'] ?? '' ?>" />
      </div>

      <!-- Relationship to Student  -->
      <div class="form-group1">
        <label>Relationship to Student</label>
       <input type="text" id="textbox" name="guardian_relationship" value="<?= $_SESSION['guardian_relationship'] ?? '' ?>" />
      </div>

      <!-- Contact Number  -->
      <div class="form-group1">
        <label>Guardian Contact Number</label>
        <input type="text" id="textbox" name="guardian_contact" value="<?= $_SESSION['guardian_contact'] ?? '' ?>" />
      </div>

       <!-- Button Container -->
    <div class="button-container">
        <a href="Admission-AddStudent.php">
            <input type="button" class="btn-previous" value="Previous" />
        </a>
        <input type="submit" class="btn-next" value="Next" />
    </div>

  </div>
  </form>
</body>
</html>