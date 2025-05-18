<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total students
$result_students = $conn->query("SELECT COUNT(*) AS total_students FROM studentinformation");
$total_students = 0;
if ($result_students && $row = $result_students->fetch_assoc()) {
    $total_students = $row['total_students'];
}

// Total users
$result_users = $conn->query("SELECT COUNT(*) AS total_users FROM studentlogin");
$total_users = 0;
if ($result_users && $row = $result_users->fetch_assoc()) {
    $total_users = $row['total_users'];
}

// Total unique paid students
$result_paid = $conn->query("SELECT COUNT(DISTINCT student_id) AS total_paid FROM payments WHERE status = 'paid'");
$total_paid = 0;
if ($result_paid && $row = $result_paid->fetch_assoc()) {
    $total_paid = $row['total_paid'];
}

// Get today's attendance counts by status (0=Absent,1=Present,2=Late)
$today = date('Y-m-d');

$sql_attendance = $conn->prepare("
    SELECT Status, COUNT(*) AS count
    FROM attendance
    WHERE Date = ?
    GROUP BY Status
");
$sql_attendance->bind_param('s', $today);
$sql_attendance->execute();
$result_attendance = $sql_attendance->get_result();

$attendance_counts = [
    'present' => 0,
    'late' => 0,
    'absent' => 0
];

while ($row = $result_attendance->fetch_assoc()) {
    switch ($row['Status']) {
        case 0:
            $attendance_counts['absent'] = (int)$row['count'];
            break;
        case 1:
            $attendance_counts['present'] = (int)$row['count'];
            break;
        case 2:
            $attendance_counts['late'] = (int)$row['count'];
            break;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">

<main class="flex-1 p-6 space-y-6">

  <!-- Dashboard Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    <!-- Card 1: Total Students -->
    <div class="bg-gradient-to-r from-purple-500 border border-purple-500 rounded-lg shadow-md px-2 flex flex-col items-center justify-center">
      <h1 class="text-[108px] font-bold text-blue-600"><?= $total_students ?></h1>
      <h3 class="text-white font-medium">Total of students</h3>
    </div>

    <!-- Card 2: Total Users -->
    <div class="bg-gradient-to-r from-yellow-500 border border-yellow-500 rounded-lg shadow-md px-2 flex flex-col items-center justify-center">
      <h1 class="text-[108px] font-bold text-blue-600"><?= $total_users ?></h1>
      <h3 class="text-white font-medium">Total of users</h3>
    </div>

    <!-- Card 3: Total Paid Students -->
    <div class="bg-gradient-to-r from-green-500 border border-green-500 rounded-lg shadow-md px-2 flex flex-col items-center justify-center">
      <h1 class="text-[108px] font-bold text-blue-600"><?= $total_paid ?></h1>
      <h3 class="text-white font-medium">Total of Paid Students</h3>
    </div>

  </div>

  <!-- Bottom Cards -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[60vh]">
    <div class="flex flex-col gap-6 lg:col-span-2">
      <!-- Left Top Card with Bar Graph -->
      <div class="bg-white p-6 rounded-lg shadow-md h-full">
        <canvas id="attendanceChart" class="w-full h-64"></canvas>
      </div>
    </div>

    <div class="lg:col-span-1">
<div id="notificationContainer" class="bg-white px-6 rounded-lg shadow-md h-full overflow-auto max-h-[600px]">
  <div class="flex items-center mb-4 sticky top-0 bg-white h-10 z-10 py-6 border-b border-black">
    <span class="text-md font-semibold text-blue-900">Notifications</span>
  </div>

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold text-xl">R</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Registrar's Office</div>
        <div>Grade Reports for Semester 1 are now available.</div>
        <span class="text-xs text-blue-600">2 minutes ago</span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-green-600 flex items-center justify-center text-white font-bold text-xl">L</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Library</div>
        <div>Reminder: Return borrowed books before May 20 to avoid late fees.</div>
        <span class="text-xs text-blue-600">10 minutes ago</span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-yellow-500 flex items-center justify-center text-white font-bold text-xl">A</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Academic Affairs</div>
        <div>Final exam schedule has been posted.</div>
        <span class="text-xs text-blue-600">30 minutes ago</span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center text-white font-bold text-xl">C</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Class Coordinator</div>
        <div>IT Elective class moved to Room 304 starting next week.</div>
        <span class="text-xs text-blue-600">1 hour ago</span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-xl">G</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Guidance Office</div>
        <div>Online counseling sessions open this week. Book your slot.</div>
        <span class="text-xs text-blue-600">Today</span>
      </div>
    </div>
  </div>

  <!-- New notifications -->

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-xl">S</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Sports Department</div>
        <div>Inter-school basketball tournament scheduled for next Friday.</div>
        <span class="text-xs text-blue-600">3 hours ago</span>
      </div>
    </div>
  </div>

  <div class="mb-4">
    <div class="flex items-center mb-2">
      <div class="w-12 h-12 rounded-full bg-pink-600 flex items-center justify-center text-white font-bold text-xl">F</div>
      <div class="ml-3 text-sm">
        <div class="font-semibold text-gray-900">Faculty Senate</div>
        <div>Monthly faculty meeting postponed to next Wednesday.</div>
        <span class="text-xs text-blue-600">Yesterday</span>
      </div>
    </div>
  </div>

</div>

</div>
  </div>

</main>

<script>


  const attendanceData = {
    present: <?= $attendance_counts['present'] ?>,
    late: <?= $attendance_counts['late'] ?>,
    absent: <?= $attendance_counts['absent'] ?>,
  };
  const todayDate = '<?= $today ?>';

  const ctx = document.getElementById('attendanceChart').getContext('2d');
  const attendanceChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Present', 'Late', 'Absent'],
      datasets: [{
        label: 'Students',
        data: [attendanceData.present, attendanceData.late, attendanceData.absent],
        backgroundColor: [
          'rgba(34,197,94,0.7)',   // green
          'rgba(202,138,4,0.7)',   // yellow
          'rgba(220,38,38,0.7)'    // red
        ],
        borderColor: [
          'rgba(34,197,94,1)',
          'rgba(202,138,4,1)',
          'rgba(220,38,38,1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 5
          }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: 'Attendance Overview for ' + todayDate,
          font: {
            size: 18
          }
        }
      }
    }
  });
</script>

</body>
</html>
