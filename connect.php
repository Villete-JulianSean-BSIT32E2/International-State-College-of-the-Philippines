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

$conn->close();
?>
