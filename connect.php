<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle Personal Information Submission (from addstudent.html)
if (
  isset($_POST['name']) &&
  isset($_POST['bdate']) &&
  isset($_POST['gender']) &&
  isset($_POST['nat']) &&
  isset($_POST['religion']) &&
  isset($_POST['curraddress']) &&
  isset($_POST['province']) &&
  isset($_POST['peraddress']) &&
  isset($_POST['zip']) &&
  isset($_POST['email']) &&
  isset($_POST['city']) &&
  isset($_POST['phoneno'])
) {
  $name = $_POST['name'];
  $bdate = $_POST['bdate'];
  $gender = $_POST['gender'];
  $nat = $_POST['nat'];
  $religion = $_POST['religion'];
  $curraddress = $_POST['curraddress'];
  $province = $_POST['province'];
  $peraddress = $_POST['peraddress'];
  $zip = $_POST['zip'];
  $email = $_POST['email'];
  $city = $_POST['city'];
  $phoneno = $_POST['phoneno'];

  $sql = "INSERT INTO admission (name, bdate, gender, nat, religion, curraddress, province, peraddress, zip, email, city, phoneno)
          VALUES ('$name', '$bdate', '$gender', '$nat', '$religion', '$curraddress', '$province', '$peraddress', '$zip', '$email', '$city', '$phoneno')";

  if ($conn->query($sql) === TRUE) {
    header("Location: guardianinfo.html");
    exit();
  } else {
    echo "Error inserting into admission table: " . $conn->error;
  }
}

// Handle Guardian Information Submission (from guardianinfo.html)
if (
  isset($_POST['fname']) &&
  isset($_POST['mname']) &&
  isset($_POST['foccu']) &&
  isset($_POST['moccu']) &&
  isset($_POST['fno']) &&
  isset($_POST['mno']) &&
  isset($_POST['gname']) &&
  isset($_POST['relationship']) &&
  isset($_POST['gno'])
) {
  $fname = $_POST['fname'];
  $mname = $_POST['mname'];
  $foccu = $_POST['foccu'];
  $moccu = $_POST['moccu'];
  $fno = $_POST['fno'];
  $mno = $_POST['mno'];
  $gname = $_POST['gname'];
  $relationship = $_POST['relationship'];
  $gno = $_POST['gno'];

  $sql = "INSERT INTO guardian_info (fname, mname, foccu, moccu, fno, mno, gname, relationship, gno)
          VALUES ('$fname', '$mname', '$foccu', '$moccu', '$fno', '$mno', '$gname', '$relationship', '$gno')";

  if ($conn->query($sql) === TRUE) {
    header("Location: AcademicInformation.html");
    exit();
  } else {
    echo "Error inserting into guardian_info table: " . $conn->error;
  }
}

if (isset($_POST['applying_grade'])) {
  $applying_grade = $_POST['applying_grade'];
  $prevschool = $_POST['prevschool'];
  $last_grade = $_POST['last_grade'];
  $sigdate = $_POST['sigdate'];
  $confirmed = isset($_POST['confirm']) && $_POST['confirm'] === "yes" ? 1 : 0;

  // File upload function
  function uploadFile($fileKey) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
      mkdir($targetDir, 0777, true);
    }

    $fileName = basename($_FILES[$fileKey]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    if (move_uploaded_file($_FILES[$fileKey]["tmp_name"], $targetFile)) {
      return $targetFile;
    }
    return null;
  }

  $birth_cert_path = uploadFile("doc1");
  $form137_path = uploadFile("doc2");
  $tor_path = uploadFile("doc3");
  $good_moral_path = uploadFile("doc4");
  $honorable_dismissal_path = uploadFile("doc5");
  $signature_path = uploadFile("signature");

  $sql = "INSERT INTO student_documents (
      applying_grade, prevschool, last_grade,
      birth_cert_path, form137_path, tor_path,
      good_moral_path, honorable_dismissal_path,
      signature_path, sigdate, confirmed
    ) VALUES (
      '$applying_grade', '$prevschool', '$last_grade',
      '$birth_cert_path', '$form137_path', '$tor_path',
      '$good_moral_path', '$honorable_dismissal_path',
      '$signature_path', '$sigdate', $confirmed
    )";

  if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Academic information and documents submitted successfully!'); window.location.href='Post-AdmissionPreview.html';</script>";
  } else {
    echo "Error inserting academic info: " . $conn->error;
  }
}

$conn->close();
?>
