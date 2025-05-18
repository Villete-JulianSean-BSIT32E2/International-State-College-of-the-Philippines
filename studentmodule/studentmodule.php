<?php
session_start();

if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.php?login=student");
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Student Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-200 min-h-screen">

  <!-- Header -->
  <header class="h-14 m-2 rounded-xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 opacity-100 shadow-xl px-4 pl-14 py-2 flex items-center justify-between">
    <!-- Left: Logo and Title -->
    <div class="flex items-center space-x-3">
      <img src="/International-State-College-of-the-Philippines/logo.jpg" alt="Logo" class="h-10 w-10 rounded-full" />
      <h1 class="text-xl text-white font-semibold" style="font-family: 'Poppins', sans-serif;">STUDENT PANEL</h1>
    </div>

    <!-- Right: Notification + Custom Dropdown -->
    <div class="flex items-center space-x-4 relative">
      <!-- Notification Icon -->
      <button onclick="toggleNotification()" class="relative cursor-pointer transition-all duration-200 transform hover:scale-125">
        <img src="../img/notificationlogos.png" alt="Notifications" class="h-6 w-6" />
        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full px-1" id="notification-badge"></span>
      </button>


      <div id="notificationDropdown" class="hidden absolute top-10 right-16 mr-4 p-4 w-[500px] bg-white border border-blue-500 rounded-md shadow-lg z-10">

        <div id="notificationContent" class="max-h-auto overflow-y-auto bg-white">

        </div>
        <a href="" class="text-blue-500 text-xs mt-2 block hover:underline">See all notifications</a>
      </div>

      <!-- Custom Dropdown -->
      <div class="relative">
        <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-white px-4 py-2 rounded-md transition-all duration-200 transform hover:scale-125">
          <img src="../img/settingslogo.png" alt="User Icon" class="h-6 w-15" />
        </button>
        <!-- Dropdown Items -->
        <div id="dropdownMenu" class="absolute right-0 mt-1 p-1 w-44 bg-white border border-blue-500 rounded-md shadow-lg hidden z-10">
  <button onclick="handleMyAccount()" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
    <span>My Account</span>
    <img src="../img/accountsettingslogo.png" alt="Account" class="h-6 w-6" />
  </button>
  
  <button onclick="handleSettings()" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
    <span>Settings</span>
    <img src="../img/settingslogo.png" alt="Settings" class="h-6 w-6" />
  </button>
  
  <a href="../logout.php" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
    <span>Logout</span>
    <img src="../img/logofflogo.png" alt="Logout" class="h-6 w-6" />
  </a>
</div>

      </div>
    </div>
  </header>


  <main class="p-6">
    <?php include('dashboardcontent.php'); ?>
    <?php include('myaccount.php'); ?>
  </main>


 <script>

  const notifications = [
    {
      title: "Registrar's Office",
      description: "Grade Reports for Semester 1 are now available.",
      time: "2 minutes ago",
      bgColor: "bg-indigo-600",
      iconLetter: "R"
    },
    {
      title: "Library",
      description: "Reminder: Return borrowed books before May 20 to avoid late fees.",
      time: "10 minutes ago",
      bgColor: "bg-green-600",
      iconLetter: "L"
    },
    {
      title: "Academic Affairs",
      description: "Final exam schedule has been posted.",
      time: "30 minutes ago",
      bgColor: "bg-yellow-500",
      iconLetter: "A"
    },
    {
      title: "Class Coordinator",
      description: "IT Elective class moved to Room 304 starting next week.",
      time: "1 hour ago",
      bgColor: "bg-red-500",
      iconLetter: "C"
    },
    {
      title: "Guidance Office",
      description: "Online counseling sessions open this week. Book your slot.",
      time: "Today",
      bgColor: "bg-blue-600",
      iconLetter: "G"
    }
  ];

  // Function to toggle Notification Dropdown and load content dynamically
  function toggleNotification() {
    const dropdown = document.getElementById("notificationDropdown");
    const notificationContent = document.getElementById("notificationContent");
    const settingsMenu = document.getElementById("dropdownMenu");

    // Close the settings dropdown if it's open
    if (!settingsMenu.classList.contains("hidden")) {
      settingsMenu.classList.add("hidden");
    }

    // Toggle the visibility of the notification dropdown
    if (dropdown.classList.contains("hidden")) {
      dropdown.classList.remove("hidden");

      // Populate notifications dynamically
      notificationContent.innerHTML = "";
      notifications.forEach(notification => {
        const notificationItem = document.createElement("div");
        notificationItem.classList.add("w-full", "p-4", "text-gray-900", "bg-white");

        notificationItem.innerHTML = `
          <div class="flex items-center mb-2">
            <span class="text-md font-semibold text-gray-900">New Notification</span>
          </div>
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full ${notification.bgColor} flex items-center justify-center text-white font-bold text-xl">${notification.iconLetter}</div>
            <div class="ml-3 text-sm">
              <div class="font-semibold text-gray-900">${notification.title}</div>
              <div>${notification.description}</div>
              <span class="text-xs text-blue-600">${notification.time}</span>
            </div>
          </div>
        `;

        notificationContent.appendChild(notificationItem);
      });
    } else {
      dropdown.classList.add("hidden");
    }
  }

  // Function to toggle Settings Dropdown
  function toggleDropdown() {
    const menu = document.getElementById("dropdownMenu");
    const notificationDropdown = document.getElementById("notificationDropdown");

    // Close the notification dropdown if it's open
    if (!notificationDropdown.classList.contains("hidden")) {
      notificationDropdown.classList.add("hidden");
    }

    // Toggle the visibility of the settings dropdown
    menu.classList.toggle("hidden");
  }

  // Optional: Hide dropdown when clicking outside
  document.addEventListener("click", function (event) {
    const notificationButton = event.target.closest("button");
    const notificationDropdown = document.getElementById("notificationDropdown");

    const dropdownButton = event.target.closest(".relative");
    const dropdown = document.getElementById("dropdownMenu");

    // Hide notification dropdown when clicking outside
    if (!event.target.closest("#notificationDropdown") && !notificationButton) {
      notificationDropdown.classList.add("hidden");
    }

    // Hide settings dropdown when clicking outside
    if (!event.target.closest("#dropdownMenu") && !dropdownButton) {
      dropdown.classList.add("hidden");
    }
  });

function handleMyAccount() {
  const name = localStorage.getItem('studentName') || 'N/A';
  const id = localStorage.getItem('studentID') || 'N/A';
  const section = localStorage.getItem('studentSection') || 'N/A';
  const year = localStorage.getItem('studentYear') || 'N/A';
  const sem = localStorage.getItem('studentSem') || 'N/A';

  document.getElementById('studentname').innerText = name;
  document.getElementById('studentID').innerText = id;
  document.getElementById('studentSection').innerText = section;
  document.getElementById('studentyear').innerText = year;
  document.getElementById('studentSemester').innerText = sem;

  document.getElementById("myAccountModal").classList.remove('hidden');
}

function closeMyAccount() {
  document.getElementById("myAccountModal").classList.add('hidden');
}



</script>


</body>
</html>
