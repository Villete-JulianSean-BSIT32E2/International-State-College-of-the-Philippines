<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Student Dashboard</title>
</head>
<body class="bg-gray-100">

  <!-- Header -->
  <header class="bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 opacity-90 shadow-xl px-4 pl-20 py-2 flex items-center justify-between ">
    <!-- Left: Logo and Title -->
    <div class="flex items-center space-x-3">
      <img src="/International-State-College-of-the-Philippines/logo.jpg" alt="Logo" class="h-12 w-12 rounded-full" />
      <h1 class="text-xl text-white font-semibold">STUDENT PANEL</h1>
    </div>

    <!-- Right: Notification + Custom Dropdown -->
    <div class="flex items-center space-x-4 relative">
      <!-- Notification Icon -->
      <button class="relative cursor-pointer">
        <img src="../img/notificationlogo.png" alt="Notifications" class="h-6 w-6" />
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1"></span>
      </button>

      <!-- Custom Dropdown -->
      <div class="relative">
        <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-white px-4 py-2 rounded-md">
          <img src="../img/settingslogo.png" alt="User Icon" class="h-5 w-15" />
          
        </button>
        <!-- Dropdown Items -->
        <div id="dropdownMenu" class="absolute right-0 mt-2 w-44 bg-white rounded-md shadow-lg hidden z-10">
  <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
    <img src="../img/attendancelogo.png" alt="Account" class="h-6 w-6 mr-2" />
    My Account
  </a>
  <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
    <img src="../img/settingslogo.png" alt="Settings" class="h-6 w-6 mr-2" />
    Settings
  </a>
  <a href="#" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
    <img src="../img/logoutlogo.png" alt="Logout" class="h-6 w-6 mr-2" />
    Logout
  </a>
</div>

      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="p-6">
    <?php include('dashboardcontent.html'); ?>
  </main>

  <!-- Script for Toggle -->
  <script>
    function toggleDropdown() {
      const menu = document.getElementById("dropdownMenu");
      menu.classList.toggle("hidden");
    }

    // Optional: Hide when clicking outside
    document.addEventListener("click", function (event) {
      const button = event.target.closest("button");
      const dropdown = document.getElementById("dropdownMenu");
      if (!event.target.closest("#dropdownMenu") && !button) {
        dropdown.classList.add("hidden");
      }
    });
  </script>

</body>
</html>
