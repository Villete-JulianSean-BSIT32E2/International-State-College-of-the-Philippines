

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body class="bg-gray-200">

  <div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 my-4 ml-4 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-500 rounded-xl rounded-br-lg text-white p-6 hidden md:flex flex-col items-center space-y-6">
      <!-- Logo (Centered) -->
      <div class="mb-8 flex flex-col items-center justify-center align-center text-center">
        <img src="../img/logo.png" alt="Logo" class="h-20 w-20 mb-2 bg-white rounded-full">
        <span class="text-xl font-bold text-white">STUDENT MODULE</span>
      </div>

      <!-- Navigation (Left-Aligned) -->
      <nav class="space-y-4 w-full text-left">
        <a href="#" data-page="dashboard.php" class="nav-link flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-purple-500">
          <img src="../img/dashboardlogo.png" alt="Dashboard" class="h-5 w-5">
          <span>DASHBOARD</span>
        </a>
        <a href="#" data-page="studentrecords.php" class="nav-link flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-yellow-400">
          <img src="../img/studentslogo.png" alt="Students" class="h-5 w-5">
          <span>STUDENT RECORDS</span>
        </a>
        <a href="#" data-page="attendance.php" class="nav-link flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-green-600">
          <img src="../img/attendancelogo.png" alt="Attendance" class="h-5 w-5">
          <span>ATTENDANCE</span>
        </a>
        <a href="#" data-page="accountsettings.php" class="nav-link flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-purple-600">
          <img src="../img/accountsettingslogo.png" alt="Settings" class="h-5 w-5">
          <span>ACCOUNT SETTINGS</span>
        </a>
        <a href="#" data-page="billing.php" class="nav-link flex items-center gap-3 px-3 py-2 rounded text-sm font-bold transition-all duration-200 transform hover:scale-105 hover:bg-blue-400 hover:text-green-600">
          <img src="../img/billinglogo.png" alt="Billing" class="h-5 w-5">
          <span>BILLING & PAYMENT</span>
        </a>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <header class="h-14 bg-gradient-to-r from-blue-600 via-blue-600 to-indigo-500 shadow-md py-2 px-4 flex justify-between items-center rounded-xl m-2">
        <h1 class="text-xl font-semibold text-white">Welcome, Student</h1>
        
        <!-- Right Side - Image Button with Select Dropdown -->
        <div class="relative">
          <button id="dropdownButton" class="flex items-center text-white p-2 rounded-full hover:scale-125 transition-all transform">
            <img src="../img/settingslogo.png" alt="User" class="h-8 w-8 rounded-full">
          </button>
          
          <!-- Dropdown Menu -->
          <div id="dropdownMenu" class="absolute right-0 mt-1 p-1 w-44 bg-white border border-blue-500 rounded-md shadow-lg hidden z-10">
            <ul class="p-2">
              <li>
                <a href="#" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
                  My Account
                  <img src="../img/accountsettingslogo.png" alt="Settings" class="h-5 w-5 ml-2"> 
                </a>
              </li>
              <li>
                <a href="../logout.php" class="w-36 text-blue-500 mx-auto my-1 flex items-center justify-between px-4 py-2 text-sm rounded-md transition-all duration-200 transform hover:scale-105 hover:bg-blue-100 hover:text-black hover:border hover:border-blue-500">
                  Logout
                  <img src="../img/logofflogo.png" alt="Logout" class="h-5 w-5 ml-2">
                </a>
              </li>
            </ul>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main id="main-content" class="flex-1 p-6 overflow-x-hidden max-w-full">
        <div>Loading...</div>
      </main>

    </div>
  </div>

<script>
  // Dropdown toggle
  const dropdownButton = document.getElementById('dropdownButton');
  const dropdownMenu = document.getElementById('dropdownMenu');

  dropdownButton.addEventListener('click', () => {
    dropdownMenu.classList.toggle('hidden');
  });

  document.addEventListener('click', (e) => {
    if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.add('hidden');
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('.nav-link');
    const mainContent = document.getElementById('main-content');

   function attachDeleteHandlers() {
  const deleteButtons = mainContent.querySelectorAll('.delete-btn');
  deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
      const student_id = button.getAttribute('data-id');

      const currentPage = mainContent.querySelector('table')?.id === 'attendanceTable'
        ? 'page/attendance.php'
        : mainContent.querySelector('table')?.id === 'accountSettingsTable'
          ? 'page/accountsettings.php'
          : 'page/studentrecords.php';

      if (confirm('Are you sure you want to delete student ID ' + student_id + '?')) {
        fetch(currentPage, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'delete_id=' + encodeURIComponent(student_id)
        })
        .then(response => response.text())
        .then(data => {
          if (data.trim() === 'success') {
            const row = button.closest('tr');
            if (row) row.remove();
          } else {
            alert('Failed to delete the record.');
          }
        })
        .catch(() => alert('Error deleting record.'));
      }
    });
  });
}

    function attachSearchHandler(tableId = 'studentTable', searchBoxId = 'searchBox') {
      const searchBox = mainContent.querySelector(`#${searchBoxId}`);
      const tableBody = mainContent.querySelector(`#${tableId} tbody`);

      if (!searchBox || !tableBody) return;

      searchBox.addEventListener('input', () => {
        const filter = searchBox.value.toLowerCase();

        Array.from(tableBody.rows).forEach(row => {
          let rowHasMatch = false;

          Array.from(row.cells).forEach(cell => {
            if (cell.querySelector('button') || cell.querySelector('a') || cell.querySelector('input')) return;

            const originalText = cell.textContent;
            cell.innerHTML = originalText;

            if (filter && originalText.toLowerCase().includes(filter)) {
              rowHasMatch = true;
              const regex = new RegExp(`(${filter})`, 'gi');
              const highlighted = originalText.replace(regex, '<span style="background-color: yellow;">$1</span>');
              cell.innerHTML = highlighted;
            }
          });

          row.style.display = rowHasMatch || !filter ? '' : 'none';
        });
      });
    }

   function attachDropdownHandlers() {
  const mainContent = document.getElementById('main-content');

  mainContent.querySelectorAll('.dropdown-button').forEach(button => {
    button.addEventListener('click', e => {
      e.stopPropagation();

      const dropdownMenu = button.nextElementSibling;
      if (!dropdownMenu) return;

      // Close other dropdown menus first
      mainContent.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (menu !== dropdownMenu) menu.classList.add('hidden');
      });

      // Show this dropdown
      dropdownMenu.classList.toggle('hidden');

      if (!dropdownMenu.classList.contains('hidden')) {
        // Position the dropdown menu ABOVE the button

        // Get button's position relative to viewport
        const rect = button.getBoundingClientRect();

        // Get the height of the dropdown menu
        const menuHeight = dropdownMenu.offsetHeight;

        // Calculate the top position to place menu above the button
        // We'll set the dropdownMenu's CSS top and left (absolute positioning)
        dropdownMenu.style.position = 'fixed';
        dropdownMenu.style.left = `${rect.left}px`;
        dropdownMenu.style.top = `${rect.top - menuHeight}px`; // Above button

        // Optionally set width same as button
        dropdownMenu.style.minWidth = `${rect.width}px`;
      } else {
        // Reset styles when hidden
        dropdownMenu.style.position = '';
        dropdownMenu.style.top = '';
        dropdownMenu.style.left = '';
        dropdownMenu.style.minWidth = '';
      }
    });
  });

  // Clicking outside closes all dropdown menus
  document.addEventListener('click', () => {
    mainContent.querySelectorAll('.dropdown-menu').forEach(menu => {
      menu.classList.add('hidden');
      // Reset inline styles as well
      menu.style.position = '';
      menu.style.top = '';
      menu.style.left = '';
      menu.style.minWidth = '';
    });
  });
}


    function loadPage(page) {
    console.log("Loading page:", `page/${page}`);
    fetch(`page/${page}`)
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok (' + response.status + ')');
        return response.text();
      })
      .then(html => {
        mainContent.innerHTML = html;

       if (page === 'dashboard.php') {
  const canvas = document.getElementById('attendanceChart');
  if (canvas) {
    const ctx = canvas.getContext('2d');

    fetch('attendance_data.php')
      .then(res => res.json())
      .then(data => {
        if (window.attendanceChartInstance) {
          window.attendanceChartInstance.destroy();
        }

        window.attendanceChartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Present', 'Late', 'Absent'],
            datasets: [{
              label: 'Students',
              data: [data.present, data.late, data.absent],
              backgroundColor: [
                'rgba(34,197,94,0.7)',  // Present - green
                'rgba(202,138,4,0.7)',  // Late - yellow
                'rgba(220,38,38,0.7)'   // Absent - red
              ],
              borderColor: [
                'rgba(34,197,94,1)',
                'rgba(202,138,4,1)',
                'rgba(220,38,38,1)'
              ],
              borderWidth: 10,
              borderRadius: 20
            }]
          },
          options: {
            scales: {
              x: {
                ticks: {
                  callback: function(label, index) {
                    return this.getLabelForValue(index);
                  },
                  color: function(context) {
                    const index = context.index;
                    const labelColors = ['#22c55e', '#eab308', '#dc2626']; // green, yellow, red
                    return labelColors[index] || '#000';
                  },
                  font: {
                    size: 14,
                    weight: 'bold'
                  }
                }
              },
              y: {
                beginAtZero: true,
                ticks: { stepSize: 5, color: 'blue', font: { size: 14, weight: 'bold' } },
                grid: {
                  display: false
                }
              }
            },
            plugins: {
              legend: { display: false },
              title: {
                display: true,
                text: 'ATTENDANCE OVERVIEW / ' + data.date,
                font: { size: 18 },
                color: 'blue'
              }
            }
          }
        });
      })
      .catch(error => console.error('Error fetching attendance data:', error));
  }
}

        // Other page specific logic
        if (page === 'studentrecords.php') {
          attachDeleteHandlers();
          attachSearchHandler('studentTable', 'searchBox');
          attachDropdownHandlers();
        } else if (page === 'attendance.php') {
          attachDeleteHandlers();
          attachSearchHandler('attendanceTable', 'searchBox');
          attachDropdownHandlers();
        } else if (page === 'accountsettings.php') {
          attachDeleteHandlers();
          attachSearchHandler('accountSettingsTable', 'searchBox');
          attachDropdownHandlers();
        } else if (page === 'billing.php') {
          attachDeleteHandlers();
          attachSearchHandler('billingTable', 'searchBox');
          attachDropdownHandlers();
        }
      })
      .catch(error => {
        mainContent.innerHTML = `<div style="color:red;">Failed to load <strong>${page}</strong>: ${error.message}</div>`;
        console.error('Error loading page:', error);
      });
  }

  // Load default page or from URL
  const params = new URLSearchParams(window.location.search);
  const initialPage = params.get('page') || 'dashboard.php';
  loadPage(initialPage);

  // Navigation link handlers
  links.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const page = this.getAttribute('data-page');
      if (page) {
        loadPage(page);
        history.pushState({ page }, '', `?page=${page}`);
      }
    });
  });

  // Handle browser back/forward buttons
  window.addEventListener('popstate', function (event) {
    const page = event.state?.page || 'dashboard.php';
    loadPage(page);
  });
});
  

</script>




</body>
</html>
