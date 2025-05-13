<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>School ERP Dashboard</title>
  <style>
    html, body {
      margin: 0;
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to bottom, #e0f0ff 0%, #cfe8ff 100%);
      background-attachment: fixed;
      background-repeat: no-repeat;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .overlay {
      background: transparent;
      padding: 30px 40px;
      border-radius: 16px;
      width: 100%;
      max-width: 100%;
      text-align: center;
    }

    .dashboard-title {
      font-size: 28px;
      color: #003f7f;
      margin-bottom: 30px;
    }

    .dashboard {
      display: flex;
      justify-content: center;
      align-items: stretch;
      gap: 20px;
      flex-wrap: wrap;
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
      flex: 1;
      min-width: 180px;
      max-width: 250px;
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

    @media (max-width: 768px) {
      .dashboard {
        flex-wrap: wrap;
      }
    }
  </style>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
  <div class="overlay">
    <h1 class="dashboard-title">ERP MODULES</h1>

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

      <a href="Cashier/tuition.php" class="tile">
        <i class="fa-solid fa-circle-dollar-to-slot"></i>
        <span>Cashier</span>
      </a>
    </div>
  </div>
</body>
</html>
