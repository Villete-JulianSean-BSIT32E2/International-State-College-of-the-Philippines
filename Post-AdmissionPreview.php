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
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      background-color: #003865;
      color: white;
      width: 250px;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .sidebar img {
      width: 100px;
      border-radius: 50%;
      margin-bottom: 40px;
    }

    .sidebar ul {
      list-style: none;
      width: 100%;
    }

    .sidebar li {
      padding: 12px 0;
    }

    .sidebar a {
      text-decoration: none;
      color: white;
      transition: 0.3s;
    }

    .sidebar a:hover {
      padding-left: 10px;
      color: #00bfff;
    }

    /* Main Content */
    .main {
      flex-grow: 1;
      padding: 40px;
    }

    .main h1 {
      color: #003865;
      margin-bottom: 20px;
    }

    .profile-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      padding: 30px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .profile-card:hover {
      transform: scale(1.02);
    }

    .profile-card img {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 15px;
      border: 4px solid #007bff;
    }

    .profile-card h2 {
      color: #333;
      margin-bottom: 5px;
    }

    .profile-card p {
      color: #777;
    }

    .buttons {
      margin-top: 30px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .buttons a button {
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-back {
      background-color: white;
      border: 2px solid #007bff;
      color: #007bff;
    }

    .btn-back:hover {
      background-color: #007bff;
      color: white;
    }

    .btn-next {
      background-color: #007bff;
      border: none;
      color: white;
    }

    .btn-next:hover {
      background-color: #0056b3;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
      .sidebar {
        width: 100%;
        flex-direction: row;
        overflow-x: auto;
        justify-content: space-around;
      }
      .main {
        padding: 20px;
      }
    }

    .welcome-title {
  text-align: center;
  color: #003865;
  font-size: 2.5rem;
  margin-bottom: 30px;
  transition: all 0.3s ease;
  cursor: default;
}

.welcome-title:hover {
  color: #007bff;
  text-shadow: 1px 2px 6px rgba(0, 0, 0, 0.2);
  transform: scale(1.05);
}

  </style>
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <img src="logo.png" alt="Logo">
    <ul>
      <li><a href="#">Dashboard</a></li>
      <li><a href="#">Admission</a></li>
      <li><a href="#">Registrar</a></li>
      <li><a href="#">Cashier</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main">
    <h1 class="welcome-title">Welcome to ISCP!</h1>


    <div class="profile-card">
      <img src="image/student-profile.jfif" alt="Student Profile">
      <h2><?php echo htmlspecialchars($studentName); ?></h2>
      <p>BSIT</p>
      <p>S.Y 3001-3002</p>
    </div>

    <div class="buttons">
      <a href="admission.php">
        <button class="btn-back">Back to Dashboard</button>
      </a>
    </div>
  </div>
</body>
</html>
