<?php
session_start();
include 'connect.php'; // include your DB connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }

    // Store POST data in session
    $_SESSION['full_name'] = isset($_POST['full_name']) ? sanitizeInput($_POST['full_name']) : '';
    $_SESSION['date'] = isset($_POST['date']) ? sanitizeInput($_POST['date']) : '';
    $_SESSION['gender'] = isset($_POST['gender']) ? sanitizeInput($_POST['gender']) : '';
    $_SESSION['nationality'] = isset($_POST['nationality']) ? sanitizeInput($_POST['nationality']) : '';
    $_SESSION['address'] = isset($_POST['address']) ? sanitizeInput($_POST['address']) : '';
    $_SESSION['province'] = isset($_POST['province']) ? sanitizeInput($_POST['province']) : '';
    $_SESSION['zip'] = isset($_POST['zip']) ? sanitizeInput($_POST['zip']) : '';
    $_SESSION['city'] = isset($_POST['city']) ? sanitizeInput($_POST['city']) : '';
    $_SESSION['email'] = isset($_POST['email']) ? filter_var($_POST['email'], FILTER_SANITIZE_EMAIL) : '';
    $_SESSION['phone'] = isset($_POST['phone']) ? preg_replace('/[^0-9]/', '', $_POST['phone']) : '';
    $_SESSION['photo'] = '';

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024;

        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($fileInfo, $_FILES['photo']['tmp_name']);
        finfo_close($fileInfo);

        if (in_array($mime, $allowedTypes) && $_FILES['photo']['size'] <= $maxSize) {
            $uploadDir = 'uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photoName = uniqid() . '.' . $extension;
            $destination = $uploadDir . $photoName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
                $_SESSION['photo'] = $destination;
            }
        }
    }

    // Insert data into tbladmission_addstudent
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("INSERT INTO tbladmission_addstudent 
            (Name, Birthdate, Gender, Nationality, Address, Province, City, ZIP, Email, Phone_Number, `2x2Photo`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        die('MySQL prepare error: ' . $conn->error);
    }
    
    $photo = isset($_SESSION['photo']) ? $_SESSION['photo'] : null;
    
    $stmt->bind_param(
        "sssssssssss",
        $_SESSION['full_name'],
        $_SESSION['date'],
        $_SESSION['gender'],
        $_SESSION['nationality'],
        $_SESSION['address'],
        $_SESSION['province'],
        $_SESSION['city'],
        $_SESSION['zip'],
        $_SESSION['email'],
        $_SESSION['phone'],
        $photo
    );
    
    if ($stmt->execute()) {
        $_SESSION['student_id'] = $stmt->insert_id;
        $stmt->close();
        header("Location: Admission-GuardianInfo.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!-- HTML & CSS -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

    form {
      width: 100%;
      display: contents;
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
      gap: 20px 0px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      margin-bottom: 5px;
      color: #8A8A8A;
    }

    #full-name, #date, #gender, #nationality, #address, 
    #province, #city, #zip, #email, #phone {
      width: auto;
      height: 40px;
      padding: 8px 10px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .birthdate-group {
      margin-left: -100px;
    }

    .photo-group {
      margin-left: 250px;
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
      margin-bottom: 20px;
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

    .button-container {
      margin-left: 90%;
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

  <form method="POST" enctype="multipart/form-data" action="">
    <div class="main">
      <h2>Personal Information</h2>
      <div class="form-grid">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="full_name" id="full-name" value="<?php echo isset($_SESSION['full_name']) ? $_SESSION['full_name'] : ''; ?>" />
        </div>

        <div class="form-group birthdate-group">
          <label>Birthdate</label>
          <input type="date" name="date" id="date" value="<?php echo isset($_SESSION['date']) ? $_SESSION['date'] : ''; ?>" />
        </div>

        <div class="form-group">
          <label>Gender</label>
          <select name="gender" id="gender">
            <option <?php echo (!isset($_SESSION['gender']) || $_SESSION['gender'] === 'Gender') ? 'selected' : ''; ?>>Gender</option>
            <option <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
            <option <?php echo (isset($_SESSION['gender']) && $_SESSION['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
          </select>
        </div>

        <div class="form-group birthdate-group">
          <label>Nationality</label>
          <input type="text" name="nationality" id="nationality" value="<?php echo isset($_SESSION['nationality']) ? $_SESSION['nationality'] : ''; ?>" />
        </div>

        <div class="upload-section photo-group">
          <div class="upload-box">
            <?php if (!empty($_SESSION['photo'])): ?>
              <img src="<?php echo htmlspecialchars($_SESSION['photo']); ?>" alt="Uploaded Photo" width="100" />
            <?php else: ?>
              <img src="camera-icon.png" alt="Upload" />
            <?php endif; ?>
          </div>
          <input type="file" name="photo" id="photo-input" style="display: none;" />
          <label for="photo-input" class="btn-upload">Upload</label>
        </div>
      </div>

      <h2>Contact Information</h2>
      <div class="form-grid">
        <div class="form-group">
          <label>Streets/Block/Barangay</label>
          <input type="text" name="address" id="address" value="<?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?>" />
        </div>
        <div class="form-group birthdate-group">
          <label>Province</label>
          <input type="text" name="province" id="province" value="<?php echo isset($_SESSION['province']) ? $_SESSION['province'] : ''; ?>" />
        </div>
        <div class="form-group">
          <label>ZIP</label>
          <input type="text" name="zip" id="zip" value="<?php echo isset($_SESSION['zip']) ? $_SESSION['zip'] : ''; ?>" />
        </div>
        <div class="form-group birthdate-group">
          <label>City</label>
          <input type="text" name="city" id="city" value="<?php echo isset($_SESSION['city']) ? $_SESSION['city'] : ''; ?>" />
        </div>
        <div class="form-group">
          <label>Email ID</label>
          <input type="email" name="email" id="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" />
        </div>
        <div class="form-group birthdate-group">
          <label>Phone Number</label>
          <input type="text" name="phone" id="phone" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" />
        </div>
      </div>

      <div class="button-container">
        <input type="submit" class="btn-next" value="Next" />
     </div>
     </div>
     </form>
     </body>
     </html>
