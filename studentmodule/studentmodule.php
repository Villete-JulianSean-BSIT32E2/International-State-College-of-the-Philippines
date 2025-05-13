<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Student Dashboard</title>
</head>
<body class="bg-gray-100">

  <!-- Container -->
  <div class="flex h-screen">

    <!-- Sidebar -->
  <aside class="w-64 bg-blue-800 text-white p-5 space-y-4 rounded-tr-lg rounded-br-lg flex flex-col">
  <!-- Logo -->
  <div class="flex justify-center">
    <img src="/International-State-College-of-the-Philippines/logo.jpg" alt="Logo" class="h-20 w-auto bg-white rounded-full" />
  </div>
  <h2 class="text-2xl font-bold mb-6 text-center">Student Panel</h2>
  <nav class="space-y-3 flex-1">
    <!-- Dashboard Link with Logo -->
    <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded">
      <img src="../img/dashboardlogo.png" alt="Dashboard Logo" class="h-6 w-6" />
      <span>Dashboard</span>
    </a>

    <!-- Attendance Link with Logo -->
    <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded">
      <img src="../img/attendancelogo.png" alt="Attendance Logo" class="h-6 w-6" />
      <span>Attendance</span>
    </a>

    <!-- Evaluations Link with Logo -->
    <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded">
      <img src="../img/classschedulelogo.png" alt="Evaluations Logo" class="h-6 w-6" />
      <span>Class Schedule</span>
    </a>

    <!-- History Link with Logo -->
    <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded">
      <img src="../img/gradeslogo.png" alt="History Logo" class="h-6 w-6" />
      <span>History</span>
    </a>

    <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded">
        <img src="../img/notificationlogo.png" alt="History Logo" class="h-6 w-6" />
        <span>Notification</span>
    </a>

    <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded">
        <img src="../img/settingslogo.png" alt="History Logo" class="h-6 w-6" />
        <span>Settings</span>
    </a>
  </nav>

  <!-- Logout Link with Logo at the bottom -->
  <a href="#" class="flex items-center space-x-2 hover:bg-blue-700 p-2 rounded mt-auto">
    <img src="../img/logoutlogo.png" alt="Logout Logo" class="h-6 w-6" />
    <span>Logout</span>
  </a>
</aside>


    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-y-auto">

      <!-- Header -->
      <header class="bg-white shadow p-4">
        <h1 class="text-xl font-semibold">Welcome, Student!</h1>
      </header>

      <!-- Content Area -->
      <?php include('dashboardcontent.html'); ?>

    </div>
  </div>

</body>
</html>
