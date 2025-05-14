<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200">

  <div class="flex min-h-screen">

    <!-- Sidebar -->
<aside class="w-64 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-500 rounded-tr-lg rounded-br-lg text-white p-6 hidden md:flex flex-col items-center space-y-6">

  <!-- Logo (Centered) -->
  <div class="mb-8 flex flex-col items-center">
    <img src="../img/logo.png" alt="Logo" class="h-20 w-20 mb-2 bg-white rounded-full">
    <span class="text-xl font-bold text-white">STUDENT MODULE</span>
  </div>

  <!-- Navigation (Left-Aligned) -->
  <nav class="space-y-4 w-full text-left">
    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-purple-500">
      <img src="../img/dashboardlogo.png" alt="Dashboard" class="h-5 w-5">
      <span>DASHBOARD</span>
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-yellow-400 ">
      <img src="../img/studentslogo.png" alt="Students" class="h-5 w-5">
      <span>STUDENT RECORDS</span>
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-green-600 ">
      <img src="../img/attendancelogo.png" alt="Attendance" class="h-5 w-5">
      <span>ATTENDANCE</span>
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-purple-600 ">
      <img src="../img/accountsettingslogo.png" alt="Settings" class="h-5 w-5">
      <span>ACCOUNT SETTINGS</span>
    </a>
    <a href="#" class="flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-green-600 ">
      <img src="../img/billinglogo.png" alt="Billing" class="h-5 w-5">
      <span>BILLING & PAYMENT</span>
    </a>
  </nav>
</aside>



    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <header class="bg-white shadow-md py-2 px-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold text-blue-600">Welcome, Student</h1>
        
        <!-- Right Side - Image Button with Select Dropdown -->
        <div class="relative">
          <button id="dropdownButton" class="flex items-center text-white p-2 rounded-full hover:scale-125 transition-all transform">
            <img src="../img/settingslogo.png" alt="User" class="h-8 w-8 rounded-full">
          </button>
          
          <!-- Dropdown Menu -->
          <div id="dropdownMenu" class="absolute right-0 mt-1 p-1 w-44 bg-white border border-blue-500 rounded-md shadow-lg hidden z-10">
            <ul class="p-2">
              <!-- Menu Item with Icon on the Right -->
              <li>
                <a href="#" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
                  My Account
                  <img src="../img/accountsettingslogo.png" alt="Settings" class="h-5 w-5 ml-2"> 
                </a>
              </li>
              <li>
                <a href="#" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
                  Logout
                  <img src="../img/logofflogo.png" alt="Logout" class="h-5 w-5 ml-2">
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6">
        <?php include('./dashboard.php'); ?>
      </main>

    </div>
  </div>

  <script>
    // Toggle the dropdown when the button is clicked
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', () => {
      dropdownMenu.classList.toggle('hidden');  
    });

    // Close the dropdown when an option is clicked
    const dropdownOptions = dropdownMenu.querySelectorAll('a');
    dropdownOptions.forEach(option => {
      option.addEventListener('click', () => {
        dropdownMenu.classList.add('hidden'); 
      });
    });

    window.addEventListener('click', (event) => {
      if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden'); 
      }
    });
  </script>

</body>
</html>
