<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "iscpdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest student
$result = $conn->query("SELECT name FROM admission ORDER BY id DESC LIMIT 1");
$studentName = "Student Name";

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentName = $row['name'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to ISCP!</title>
</head>
<body style="font-family: sans-serif; margin: 0; background-color: #f4f4f4; display: flex;">
  <!-- Sidebar -->
  <div style="background-color: #003865; color: white; width: 250px; height: 100vh; padding: 20px; display: flex; flex-direction: column; align-items: flex-start;">
    <div style="margin-bottom: 40px;">
      <img src="logo.png" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
    </div>
    <ul style="list-style: none; padding: 0; width: 100%;">
      <li style="padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; display: flex; align-items: center;">
          
          Dashboard
        </a>
      </li>
      <li style="padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; display: flex; align-items: center; justify-content: space-between;">
          <div style="display: flex; align-items: center;">
            
            Admission
          </div>
          <span style="margin-left: 10px;"></span>
        </a>
      </li>
      <li style="padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; display: flex; align-items: center;">
         
          Registrar
        </a>
      </li>
      <li style="padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; display: flex; align-items: center;">
          
          Cashier
        </a>
      </li>
      <li style="padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; display: flex; align-items: center;">
          
          Settings and profile
        </a>
      </li>
      <li style="padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; display: flex; align-items: center;">
          
          Exams
        </a>
      </li>
    </ul>
    <div style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.2); width: 100%;">
      <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
        <a href="#" style="text-decoration: none; color: white; font-size: 14px;">Features</a>
        <span style="background-color: #ffc107; color: #003865; border-radius: 5px; padding: 2px 5px; font-size: 10px;">NEW</span>
      </div>
    </div>
  </div>

  <!-- Main Content -->
  <div style="flex-grow: 1; padding: 40px;">
    <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 30px;">
      <a href="#" style="text-decoration: none; color: #555; margin-left: 20px; display: flex; align-items: center;">
        <img src="notification-icon.png" alt="Notification" style="width: 20px; height: 20px; margin-right: 5px; opacity: 0.7;">
      </a>
      <a href="#" style="text-decoration: none; color: #555; margin-left: 20px;">Log out</a>
    </div>

    <h1 style="color: #333; margin-bottom: 20px;">Welcome to ISCP!</h1>

    <div style="display: flex; flex-direction: column; align-items: center;">
      <img src="student-profile.jpg" alt="Student Profile" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">
      <h2 style="color: #333; margin-bottom: 5px;"><?php echo htmlspecialchars($studentName); ?></h2>
      <p style="color: #777; margin-bottom: 5px;">BSIT</p>
      <p style="color: #777;">S.Y 3001-3002</p>
    </div>
   

    <div style="display: flex; justify-content: flex-end; margin-top: 40px;">
      <a href="index.php">
      <button style="padding: 10px 20px; border: 1px solid #ccc; border-radius: 5px; background-color: white; color: #555; cursor: pointer; margin-right: 10px;">Back to Dashboard</button>
    </a>

      <a href="Personal-Information.php">
      <button style="padding: 10px 20px; border: none; border-radius: 5px; background-color: #007bff; color: white; cursor: pointer;">Next</button>
      </a>
    </div>
  </div>
</body>
</html>
