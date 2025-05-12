<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>School ERP Dashboard</title>
  <style>
    /* Simulated Parallax Background with Gradient */
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom, #e0f0ff 0%, #cfe8ff 100%);
      background-attachment: fixed;
      background-repeat: no-repeat;
      background-size: cover;
    }

    .overlay {
      background: rgba(255, 255, 255, 0.85);
      min-height: 100vh;
    }

    .navbar {
      background-color: #003f7f;
      color: white;
      padding: 15px 25px;
      display: flex;
      align-items: center;
      font-size: 18px;
    }

    .navbar h1 {
      margin: 0;
      font-size: 20px;
      font-weight: normal;
    }

    .dashboard {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 25px;
      padding: 40px;
      max-width: 1000px;
      margin: auto;
    }

    .tile {
      background-color: #007bff;
      color: white;
      text-align: center;
      padding: 30px 20px;
      border-radius: 12px;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .tile:hover {
      transform: translateY(-5px);
      background-color: #005bb5;
    }

    .tile i {
      font-size: 36px;
      margin-bottom: 10px;
      display: block;
    }

    .tile span {
      font-size: 18px;
      font-weight: 500;
    }

    .dashboard a {
      text-decoration: none;
    }
  </style>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

  <div class="overlay">
    <!-- Navigation Bar -->
    <div class="navbar">
      <h1>ERP Dashboard</h1>
    </div>

    <!-- Dashboard Section -->
    <div class="dashboard">
      <a href="admission.php" class="tile">
        <i class="fas fa-user-plus"></i>
        <span>Admissions</span>
      </a>

      <a href="Registrar/registrar.php" class="tile">
        <i class="fas fa-building-columns"></i>
        <span>Registrar</span>
      </a>

      <a href="#" class="tile">
        <i class="fas fa-calendar-check"></i>
        <span>Attendance</span>
      </a>

      <a href="#" class="tile">
        <i class="fas fa-user-graduate"></i>
        <span>Student Management</span>
      </a>
    </div>
  </div>

</body>
</html>
