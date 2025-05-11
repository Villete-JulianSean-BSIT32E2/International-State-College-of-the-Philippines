<?php
session_start();
require_once 'connect.php';  // Make sure to include the database connection file

// Save form data to session
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['applying_grade'] = isset($_POST['applying_grade']) ? $_POST['applying_grade'] : '';
    $_SESSION['prevschool'] = isset($_POST['prevschool']) ? $_POST['prevschool'] : '';
    $_SESSION['last_grade'] = isset($_POST['last_grade']) ? $_POST['last_grade'] : '';
    $_SESSION['Course'] = isset($_POST['Course']) ? $_POST['Course'] : '';
    $_SESSION['student_type'] = isset($_POST['student_type']) ? $_POST['student_type'] : '';
    $_SESSION['confirm'] = isset($_POST['confirm']) ? $_POST['confirm'] : '';
    $_SESSION['sigdate'] = isset($_POST['sigdate']) ? $_POST['sigdate'] : '';

    // Upload directory
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $documents = [];
    for ($i = 1; $i <= 5; $i++) {
        if (isset($_FILES["doc$i"]) && $_FILES["doc$i"]['error'] == 0) {
            $filename = basename($_FILES["doc$i"]["name"]);
            $target_path = $upload_dir . time() . "_$filename";
            move_uploaded_file($_FILES["doc$i"]["tmp_name"], $target_path);
            $documents[] = $target_path;
        } else {
            $documents[] = null;
        }
    }
    $_SESSION['documents'] = $documents;

    // Signature upload
    if (isset($_FILES["signature"]) && $_FILES["signature"]['error'] == 0) {
        $filename = basename($_FILES["signature"]["name"]);
        $sig_path = $upload_dir . time() . "_signature_$filename";
        move_uploaded_file($_FILES["signature"]["tmp_name"], $sig_path);
        $_SESSION['signature'] = $sig_path;
    }

    // The insert logic is disabled for now
    /*
    $applying_grade = $_SESSION['applying_grade'];
    $prevschool = $_SESSION['prevschool'];
    $last_grade = $_SESSION['last_grade'];
    $course = $_SESSION['Course'];
    $student_type = $_SESSION['student_type'];
    $confirm = $_SESSION['confirm'];
    $sigdate = $_SESSION['sigdate'];
    $documents_json = json_encode($documents);

    $query = "INSERT INTO tbladmission_addstudent (applying_grade, prevschool, last_grade, course, student_type, confirm, sigdate, documents, signature)
              VALUES ('$applying_grade', '$prevschool', '$last_grade', '$course', '$student_type', '$confirm', '$sigdate', '$documents_json', '$sig_path')";

    if (mysqli_query($conn, $query)) {
        $_SESSION['insert_status'] = "Data inserted successfully!";
    } else {
        $_SESSION['insert_status'] = "Error inserting data: " . mysqli_error($conn);
    }
    */

    header("Location: Admission-Overview.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Academic Information</title>
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

    .form-grid, .form-grid1 {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .form-grid1 {
      display: grid;
      grid-template-columns: repeat(1, 1fr);
      gap: 20px;
      margin-bottom: 30px;
    }

    .form-group, .form-group1 {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 5px;
      color: #8A8A8A;
    }

    #I_confirm {
        color:rgb(0, 0, 0);
    }

    #signature {
      width: 500px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    #date1 {
      width: 500px;      /* Adjust width */
      height: 40px;      /* Adjust height */
      padding: 8px 10px; /* Optional padding for inner spacing */
      font-size: 16px;   /* Optional: text size */
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input, select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 16px;
    }

    .button-container {
        margin-left: 80%;
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
      .form-grid, .form-grid1 {
        grid-template-columns: 1fr;
      }

      .sidebar {
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

  <div class="main">
    <h2>Academic Information</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="form-grid">
        <div class="form-group">
          <label>Applying For Class/Grade</label>
          <select name="applying_grade" required>
            <option value="">Select Grade</option>
            <option value="1st Year" <?php if ((isset($_SESSION['applying_grade']) && $_SESSION['applying_grade'] == '1st Year')) echo 'selected'; ?>>1st Year</option>
            <option value="2nd Year" <?php if ((isset($_SESSION['applying_grade']) && $_SESSION['applying_grade'] == '2nd Year')) echo 'selected'; ?>>2nd Year</option>
            <option value="3rd Year" <?php if ((isset($_SESSION['applying_grade']) && $_SESSION['applying_grade'] == '3rd Year')) echo 'selected'; ?>>3rd Year</option>
            <option value="4th Year" <?php if ((isset($_SESSION['applying_grade']) && $_SESSION['applying_grade'] == '4th Year')) echo 'selected'; ?>>4th Year</option>
          </select>
        </div>

        <div class="form-group">
          <label>Previous School Name</label>
          <input type="text" name="prevschool" value="<?php echo isset($_SESSION['prevschool']) ? $_SESSION['prevschool'] : ''; ?>" required />
        </div>

        <div class="form-group">
          <label>Last Grade Completed</label>
          <select name="last_grade" required>
            <option value="">Select Grade</option>
            <option value="1st Year" <?php if ((isset($_SESSION['last_grade']) && $_SESSION['last_grade'] == '1st Year')) echo 'selected'; ?>>1st Year</option>
            <option value="2nd Year" <?php if ((isset($_SESSION['last_grade']) && $_SESSION['last_grade'] == '2nd Year')) echo 'selected'; ?>>2nd Year</option>
            <option value="3rd Year" <?php if ((isset($_SESSION['last_grade']) && $_SESSION['last_grade'] == '3rd Year')) echo 'selected'; ?>>3rd Year</option>
            <option value="4th Year" <?php if ((isset($_SESSION['last_grade']) && $_SESSION['last_grade'] == '4th Year')) echo 'selected'; ?>>4th Year</option>
          </select>
        </div>

        <div class="form-group">
          <label>Course</label>
          <select name="Course" required>
            <option value="">Select</option>
            <option value="BSIT" <?php if ((isset($_SESSION['Course']) && $_SESSION['Course'] == 'BSIT')) echo 'selected'; ?>>BSIT</option>
            <option value="BSCRIM" <?php if ((isset($_SESSION['Course']) && $_SESSION['Course'] == 'BSCRIM')) echo 'selected'; ?>>BSCRIM</option>
            <option value="BSED" <?php if ((isset($_SESSION['Course']) && $_SESSION['Course'] == 'BSED')) echo 'selected'; ?>>BSED</option>
            <option value="BSCS" <?php if ((isset($_SESSION['Course']) && $_SESSION['Course'] == 'BSCS')) echo 'selected'; ?>>BSCS</option>
            <option value="CTHM" <?php if ((isset($_SESSION['Course']) && $_SESSION['Course'] == 'CTHM')) echo 'selected'; ?>>CTHM</option>
            <option value="BSA" <?php if ((isset($_SESSION['Course']) && $_SESSION['Course'] == 'BSA')) echo 'selected'; ?>>BSA</option>
          </select>
        </div>

        <div class="form-group">
          <label>Student Type</label>
          <select name="student_type" required>
            <option value="">Select</option>
            <option value="old" <?php if ((isset($_SESSION['student_type']) && $_SESSION['student_type'] == 'old')) echo 'selected'; ?>>Old</option>
            <option value="irregular" <?php if ((isset($_SESSION['student_type']) && $_SESSION['student_type'] == 'irregular')) echo 'selected'; ?>>Irregular</option>
            <option value="new" <?php if ((isset($_SESSION['student_type']) && $_SESSION['student_type'] == 'new')) echo 'selected'; ?>>New</option>
          </select>
        </div>
      </div>
      
      <h2>Documents</h2>
      <div class="form-grid">
        <?php
          $docs = ['Birth Certificate', 'Form 137', 'Transcript of Records', 'Good Moral', 'Honorable Dismissal'];
          foreach ($docs as $index => $doc) {
            echo '<div class="form-group">';
            echo "<label>$doc</label>";
            echo "<input type='file' name='doc" . ($index + 1) . "' accept='image/*,application/pdf'>";
            echo '</div>';
          }
        ?>
      </div>

      <h2>Declaration</h2>
      <div class="form-grid1">
        <div class="form-group1">
          <label id="I_confirm">
            <input type="radio" name="confirm" value="yes" <?php if ((isset($_SESSION['confirm']) && $_SESSION['confirm'] == 'yes')) echo 'checked'; ?> required>
            I confirm all the information provided is true.
          </label>
        </div>

        <div class="form-group1">
          <label>Signature</label>
          <input type="file" id="signature" name="signature" accept="image/*" required />
        </div>

        <div class="form-group1">
          <label>Date</label>
          <input type="date" id="date1" name="sigdate" value="<?php echo isset($_SESSION['sigdate']) ? $_SESSION['sigdate'] : ''; ?>" required />
        </div>
      </div>

      <div class="button-container">
         <a href="Admission-GuardianInfo.php">
            <input type="button" class="btn-previous" value="Previous" />
        </a>
        <input type="submit" class="btn-next" value="Next" />
      </div>
    </form>
  </div>
</body>
</html>
