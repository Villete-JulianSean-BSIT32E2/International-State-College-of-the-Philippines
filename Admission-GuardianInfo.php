<?php
session_start();
include("connect.php");

// Debugging: Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save posted form data to session with sanitization
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }

    // Get and sanitize all form inputs
    $fathers_name = isset($_POST['Fathers_Name']) ? sanitizeInput($_POST['Fathers_Name']) : '';
    $mothers_name = isset($_POST['Mothers_Name']) ? sanitizeInput($_POST['Mothers_Name']) : '';
    $fathers_occupation = isset($_POST['Fathers_Occupation']) ? sanitizeInput($_POST['Fathers_Occupation']) : '';
    $mothers_occupation = isset($_POST['Mothers_Occupation']) ? sanitizeInput($_POST['Mothers_Occupation']) : '';
    $father_contact = isset($_POST['Fathers_Contact']) ? preg_replace('/[^0-9]/', '', $_POST['Fathers_Contact']) : '';
    $mother_contact = isset($_POST['Mothers_Contact']) ? preg_replace('/[^0-9]/', '', $_POST['Mothers_Contact']) : '';
    $guardian_name = isset($_POST['Guardian_Name']) ? sanitizeInput($_POST['Guardian_Name']) : '';
    $guardian_relationship = isset($_POST['Guardian_Relationship']) ? sanitizeInput($_POST['Guardian_Relationship']) : '';
    $guardian_contact = isset($_POST['Guardian_Contact']) ? preg_replace('/[^0-9]/', '', $_POST['Guardian_Contact']) : '';

    // Save to session (using lowercase keys to match overview page)
    $_SESSION['fathers_name'] = $fathers_name;
    $_SESSION['mothers_name'] = $mothers_name;
    $_SESSION['fathers_occupation'] = $fathers_occupation;
    $_SESSION['mothers_occupation'] = $mothers_occupation;
    $_SESSION['father_contact'] = $father_contact;
    $_SESSION['mother_contact'] = $mother_contact;
    $_SESSION['guardian_name'] = $guardian_name;
    $_SESSION['guardian_relationship'] = $guardian_relationship;
    $_SESSION['guardian_contact'] = $guardian_contact;

    // Debug: Uncomment to check session data before redirect
    // echo "<pre>Session data:\n";
    // print_r($_SESSION);
    // echo "</pre>";
    // exit();

    // Redirect to next page
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
  /* General reset for all elements */
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
  grid-template-columns: 1fr 1fr; /* Ensure two equal columns */
  gap: 20px; /* Adjust the gap between columns */
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
  width: 340px; /* Adjust width */
  height: 40px; /* Adjust height */
  padding: 8px 10px; /* Optional padding for inner spacing */
  font-size: 16px; /* Optional: text size */
  border: 1px solid #ccc;
  border-radius: 4px;
}

form {
  width: 100%;
  display: contents; /* Prevent layout interference */
}

/* Adjust margin for the "Mother's Full Name" field */

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

.btn-previous, .btn-next {
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
    <nav><a href="#">Admission</a></nav>
  </div>

<form method="POST">
  <div class="main">
    <h2>Parent Information</h2>
    <div class="form-grid">
      <div class="form-group fathers-group">
        <label>Father's Full Name</label>
        <input type="text" id="textbox" name="Fathers_Name" value="<?= isset($_SESSION['fathers_name']) ? htmlspecialchars($_SESSION['fathers_name']) : '' ?>" />
      </div>
      <div class="form-group mothers-group">
        <label>Mother's Full Name</label>
        <input type="text" id="textbox" name="Mothers_Name" value="<?= isset($_SESSION['mothers_name']) ? htmlspecialchars($_SESSION['mothers_name']) : '' ?>" />
      </div>
      <div class="form-group">
        <label>Occupation</label>
        <input type="text" id="textbox" name="Fathers_Occupation" value="<?= isset($_SESSION['fathers_occupation']) ? htmlspecialchars($_SESSION['fathers_occupation']) : '' ?>" /> 
      </div>
      <div class="form-group mothers-group">
        <label>Occupation</label>
        <input type="text" id="textbox" name="Mothers_Occupation" value="<?= isset($_SESSION['mothers_occupation']) ? htmlspecialchars($_SESSION['mothers_occupation']) : '' ?>" />
      </div>
      <div class="form-group fathers-group">
        <label>Contact Number</label>
        <input type="text" id="textbox" name="Fathers_Contact" value="<?= isset($_SESSION['father_contact']) ? htmlspecialchars($_SESSION['father_contact']) : '' ?>" />
      </div>
      <div class="form-group mothers-group">
        <label>Contact Number</label>
        <input type="text" id="textbox" name="Mothers_Contact" value="<?= isset($_SESSION['mother_contact']) ? htmlspecialchars($_SESSION['mother_contact']) : '' ?>" />
      </div>
    </div>

    <h2 id="InCase">In Case of Emergency</h2>
    <br><br>
    <div class="form-grid1">
      <div class="form-group1">
        <label>Guardian's Name</label>
        <input type="text" id="textbox" name="Guardian_Name" value="<?= isset($_SESSION['guardian_name']) ? htmlspecialchars($_SESSION['guardian_name']) : '' ?>" />
      </div>
      <div class="form-group1">
        <label>Relationship to Student</label>
        <input type="text" id="textbox" name="Guardian_Relationship" value="<?= isset($_SESSION['guardian_relationship']) ? htmlspecialchars($_SESSION['guardian_relationship']) : '' ?>" />
      </div>
      <div class="form-group1">
        <label>Guardian Contact Number</label>
        <input type="text" id="textbox" name="Guardian_Contact" value="<?= isset($_SESSION['guardian_contact']) ? htmlspecialchars($_SESSION['guardian_contact']) : '' ?>" />
      </div>
    </div>

    <div class="button-container">
      <a href="Admission-AddStudent.php"><input type="button" class="btn-previous" value="Previous" /></a>
      <input type="submit" class="btn-next" value="Next" />
    </div>
  </div>
</form>
</body>
</html>