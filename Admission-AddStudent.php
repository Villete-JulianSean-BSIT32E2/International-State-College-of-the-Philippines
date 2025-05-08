<?php session_start(); ?>


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

    .form-group label {
      margin-bottom: 5px;

      color: #8A8A8A;
    }


    #full-name {
      width: 340px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    
    #date {
      width: 169px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #gender {
      width: 179px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #nationality {
      width: 240px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #religion {
      width: 240px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #address {
      width: 324px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #province {
      width: 324px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #city {
      width: 324px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #zip {
      width: 86px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #email {
      width: 324px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #phone {
      width: 324px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }


    .birthdate-group {
      margin-left: -100px; /* adjust to pull left */
    }

    .photo-group {
      margin-left: 250px; /* adjust to pull left */
    }

    .form-group input, .form-group select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .upload-section {
      text-align: center;
      margin-top: 20px;
    }

    .upload-box {
      width: 180px;
      height: 180px;
      border: 4px solid #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px;
      background-color: #eee;
    }

    .upload-box img {
      width: 50px;
    }

    .btn-upload {
      padding: 10px 20px;
      border: none;
      border-radius: 6px;
      background-color: #3498db;
      
      color: white;
      cursor: pointer;
    }

    .btn-upload:hover, .btn-next:hover {
  
      background-color: #FFDE59;
    }

    .btn-next {
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

    .button-container {
        margin-left: 90%; /* adjust to pull left */
        bottom: 20px;
        right: 20px;
        display: flex;
        gap: 10px;
        z-index: 1000;
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


 <!-- Full Name  -->
  <div class="main">
    <h2>Personal Information</h2>
    <div class="form-grid">
      <div class="form-group">
      <label>Full Name</label>
        <input type="text" id="full-name" value="<?= $_SESSION['full_name'] ?? '' ?>" /> 
      </div>

      <!-- Birthday  -->
      <div class="form-group birthdate-group">
        <label>Birthdate</label>
        <input type="date" id="date" value="<?= $_SESSION['date'] ?? '' ?>" />
      </div>


      <!-- Gender  -->
      <div class="form-group">
        <label>Gender</label>
        <select id="gender">
        <option <?= (!isset($_SESSION['gender']) || $_SESSION['gender'] === 'Gender') ? 'selected' : '' ?>>Gender</option>
          <option>Male</option>
          <option>Female</option>
        </select>
      </div>

      <!-- Nationality  -->
      <div class="form-group birthdate-group">
        <label>Nationality</label>
        <input type="text" id="nationality" value="<?= $_SESSION['nationality'] ?? '' ?>" /> 
      </div>

      <!-- Religion  -->
      <div class="form-group">
        <label>Religion</label>
        <input type="text" id="religion" value="<?= $_SESSION['religion'] ?? '' ?>" />
      </div>
      
      <!-- Photo  -->
      <div class="upload-section photo-group">
        <div class="upload-box">
          <img src="camera-icon.png" alt="Upload" value="<?= $_SESSION['full_name'] ?? '' ?>" />
        </div>
        <button class="btn-upload">Upload</button>
      </div>
    </div>

    <h2>Contact Information</h2>

    <!-- Address  -->
    <div class="form-grid">
      <div class="form-group">
        <label>Streets/Block/Barangay</label>
        <input type="text" id="address" value="<?= $_SESSION['address'] ?? '' ?>" /> 
      </div>

      <!-- Province  -->
      <div class="form-group birthdate-group">
        <label>Province</label>
        <input type="text" id="province" value="<?= $_SESSION['province'] ?? '' ?>" /> 
      </div>

      <!-- Zip  -->
      <div class="form-group">
        <label>ZIP</label>
        <input type="text" id="zip" value="<?= $_SESSION['zip'] ?? '' ?>" />
      </div>

      <!-- City  -->
      <div class="form-group birthdate-group">
        <label>City</label>
        <input type="text" id="city" value="<?= $_SESSION['city'] ?? '' ?>" />
      </div>

      <!-- Email  -->
      <div class="form-group">
        <label>Email ID</label>
        <input type="email" id="email" value="<?= $_SESSION['email'] ?? '' ?>" /> 
      </div>

      <!-- Phone  -->
      <div class="form-group birthdate-group">
        <label>Phone Number</label>
        <input type="text" id="phone" value="<?= $_SESSION['phone'] ?? '' ?>" /> 
      </div>
    </div>

      <!-- Button Container -->
      <div class="button-container">
        <input type="submit" class="btn-next" value="Next" />
    </div>
  </div>
</body>
</html>