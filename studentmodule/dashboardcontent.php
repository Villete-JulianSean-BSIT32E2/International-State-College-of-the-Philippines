<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
    }
  </style>
</head>
<body class="bg-gray-100 p-10">

<main class="flex-1 px-6 space-y-6 ">

  <!-- Welcome Section -->
<div class="bg-gradient-to-r from-indigo-700 to-white border-2 border-blue-400 p-6 rounded-2xl shadow-md">
  <h2 class="text-4xl font-bold text-white mb-2">
    Welcome, <span id="studentName">Son Goku D. Arevalo</span>
  </h2>
  <p class="text-gray-200 leading-relaxed">
    <span id="studentCourse">Bachelor of Science in Information Technology - 42A2</span><br>
    <span class="font-medium">Current Semester and Year:</span> <span id="studentYear">2025</span> <span id="studentSem">- 2nd Sem</span>
  </p>
</div>
  

  <!-- 3-Column Section -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-4 h-full">

  <!-- Left Column -->
  <div class="space-y-4 lg:col-span-2 h-full">
    <!-- Attendance Pie Chart -->
    <div class="p-6 flex items-center justify-around bg-white rounded-2xl shadow-md">
      <div class="relative w-30 h-30 hover:scale-105 transition-transform border-4 border-gray-300 rounded-full">
        <svg viewBox="0 0 36 36" class="w-full h-full bg-white rounded-full shadow-xl transform -rotate-90">
          <!-- Background circle -->
          <circle id="present-arc" cx="18" cy="18" r="10" fill="none" stroke="#00C471" stroke-width="17" stroke-dasharray="0, 100" stroke-dashoffset="0" />
<circle id="late-arc" cx="18" cy="18" r="10" fill="none" stroke="#D97706" stroke-width="17" stroke-dasharray="0, 100" stroke-dashoffset="0" />
<circle id="absent-arc" cx="18" cy="18" r="10" fill="none" stroke="#EF4444" stroke-width="17" stroke-dasharray="0, 100" stroke-dashoffset="0" />
        </svg>
      </div>

      <!-- Text values -->
      <div class="grid grid-cols-1 sm:grid-rows-2 gap-12 p-4">
        <!-- Present Card -->
       <!-- Present Card -->
<div class="p-2 text-center">
  <h3 class="text-lg font-bold text-green-500">PRESENT</h3>
  <div class="flex justify-center items-center space-x-2 mt-2">
    <p id="present-count" class="text-2xl font-bold text-green-500">0</p>
    <div class="w-8 h-8 bg-green-500 rounded-sm"></div>
  </div>
</div>

<!-- Late Card -->
<div class="p-2 text-center">
  <h3 class="text-lg font-bold text-yellow-600">LATE</h3>
  <div class="flex justify-center items-center space-x-2 mt-2">
    <p id="late-count" class="text-2xl font-bold text-yellow-600">0</p>
    <div class="w-8 h-8 bg-yellow-600 rounded-sm"></div>
  </div>
</div>

<!-- Absent Card -->
<div class="p-2 text-center">
  <h3 class="text-lg font-bold text-red-500">ABSENT</h3>
  <div class="flex justify-center items-center space-x-2 mt-2">
    <p id="absent-count" class="text-2xl font-bold text-red-500">0</p>
    <div class="w-8 h-8 bg-red-500 rounded-sm"></div>
  </div>
</div>

      </div>
    </div>

 <div class="p-6 bg-white rounded-2xl shadow-md">
  <h3 class="text-sm font-thin mb-4 text-blue-800">Recent Grades - <span id="gradeYear"></span></h3>
  <div id="gradesContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 px-16">
    <!-- Grades will be dynamically inserted here -->
  </div>
</div>



  </div>

  <!-- Right Column: School Notifications -->
  <div class="space-y-4 lg:col-span-2 h-full">
    <!-- Schedule List Section -->
    <div class="bg-white rounded-xl shadow-md p-6 text-white">
  <h2 class="text-sm font-thin mb-2 text-blue-800">Schedule List</h2>
  <ul class="space-y-2" id="schedule-list">
    <!-- Dynamic schedules go here -->
  </ul>
</div>

    <!-- Tuition History Section -->
    <div class="bg-white rounded-xl shadow-md p-6 text-gray-700">
  <h2 class="text-sm font-thin mb-2 text-blue-800">Tuition Payment History</h2>
  <table class="w-full text-sm">
    <thead>
      <tr class="text-left border-b border-blue-200">
        <th class="py-1">Date</th>
        <th class="py-1">Amount</th>
        <th class="py-1">Status</th>
      </tr>
    </thead>
    <tbody id="payment-history-body">
      <!-- Dynamic rows here -->
    </tbody>
  </table>
</div>

  </div>
</div>

</main>

<script>
 const attendanceData = JSON.parse(localStorage.getItem('studentAttendance')) || [];

// Counters
let totalPresent = 0;
let totalAbsent = 0;
let totalLate = 0;

attendanceData.forEach(entry => {
  const status = parseInt(entry.Status);
  if (status === 0) totalAbsent++;
  else if (status === 1) totalPresent++;
  else if (status === 2) totalLate++;
});

const total = totalPresent + totalAbsent + totalLate;

// Percentages
const presentPercent = total ? (totalPresent / total) * 100 : 0;
const latePercent = total ? (totalLate / total) * 100 : 0;
const absentPercent = total ? (totalAbsent / total) * 100 : 0;

// Update UI counts
document.getElementById("present-count").innerText = totalPresent;
document.getElementById("absent-count").innerText = totalAbsent;
document.getElementById("late-count").innerText = totalLate;

// Animate arcs
function animateArc(element, percent, offset = 0) {
  let current = 0;
  const interval = setInterval(() => {
    if (current >= percent) {
      clearInterval(interval);
      return;
    }
    current += 1;
    element.setAttribute("stroke-dasharray", `${current}, 100`);
    element.setAttribute("stroke-dashoffset", -offset);
  }, 10);
}

const presentArc = document.getElementById("present-arc");
const lateArc = document.getElementById("late-arc");
const absentArc = document.getElementById("absent-arc");

animateArc(presentArc, presentPercent);
setTimeout(() => {
  animateArc(lateArc, latePercent, presentPercent);
}, 500);
setTimeout(() => {
  animateArc(absentArc, absentPercent, presentPercent + latePercent);
}, 1000);


 document.addEventListener('DOMContentLoaded', () => {
    const schedule = JSON.parse(localStorage.getItem('studentSchedule')) || [];
    const attendance = JSON.parse(localStorage.getItem('studentAttendance')) || [];

    schedule.forEach(item => {
      const startTime = item.start_time;
      const endTime = item.end_time;
      const subject = item.subject;
      const time = `${startTime} - ${endTime}`;

      createScheduleItem(subject, time, attendance);
    });
  });

  function createScheduleItem(subject, time, attendance) {
    const [startTime, endTime] = time.split(" - ");
    const today = new Date().toLocaleDateString();
    const startDate = new Date(`${today} ${startTime}`);
    const endDate = new Date(`${today} ${endTime}`);
    const now = new Date();

    const listItem = document.createElement("li");
    listItem.classList.add("schedule-item", "bg-gradient-to-r", "from-blue-600", "via-blue-500", "to-indigo-600", "p-2", "rounded-md");

    listItem.innerHTML = `
      <div class="flex justify-between items-center">
        <span class="text-sm text-white">${subject} - ${time}</span>
        <button class="attend-btn bg-gray-500 text-white p-2 rounded-md text-xs transition-transform">Next Class</button>
      </div>
    `;

    const button = listItem.querySelector(".attend-btn");

    // Determine attendance status
    let status = "Next Class"; // Default status
    let buttonClass = "bg-gray-500";
    let disabled = true;

    // Check if current time is within class time
    if (now >= startDate && now <= endDate) {
      status = "Mark as Attended";
      buttonClass = "bg-gray-500 hover:scale-105";
      disabled = false;
    } else if (now > endDate) {
      status = "Missed";
      buttonClass = "bg-red-500";
      disabled = true;
    } else if (startDate - now <= 3600000) {
      status = "Next Class";
      buttonClass = "bg-yellow-500";
      disabled = true;
    }

    // Check attendance records
    attendance.forEach(record => {
      const timeIn = new Date(record.TimeIn);
      const timeOut = new Date(record.TimeOut);

      if (timeIn >= startDate && timeIn <= endDate) {
        status = "Attended";
        buttonClass = "bg-green-500";
        disabled = true;
      } else if (timeIn > endDate) {
        status = "Missed";
        buttonClass = "bg-red-500";
        disabled = true;
      }
    });

    button.className = `attend-btn ${buttonClass} text-white p-2 rounded-md text-xs transition-transform`;
    button.innerText = status;
    button.disabled = disabled;

    // Click handler for marking attendance
    if (!disabled && status === "Mark as Attended") {
      button.addEventListener("click", () => {
        button.classList.remove("bg-gray-500", "hover:scale-105");
        button.classList.add("bg-green-500");
        button.innerText = "Attended";
        button.disabled = true;

        // Optionally, send attendance data to the server here
      });
    }

    document.getElementById("schedule-list").appendChild(listItem);
  };



document.getElementById("studentName").textContent = localStorage.getItem('studentName');
document.getElementById("studentCourse").textContent = localStorage.getItem('studentCourse');
document.getElementById("studentSem").textContent = localStorage.getItem('studentSem');
document.getElementById("studentYear").textContent = localStorage.getItem('studentYear');


  document.getElementById("gradeYear").textContent = localStorage.getItem("studentYear");

  const grades = JSON.parse(localStorage.getItem('studentGrades')) || [];
  const container = document.getElementById('gradesContainer');

  grades.forEach(item => {
    const gradeCard = document.createElement('div');
    gradeCard.className = "bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 hover:scale-105 transition-transform p-4 rounded-xl shadow-lg text-white";

    gradeCard.innerHTML = `
      <div class="flex justify-between items-center">
        <h4 class="font-normal text-sm">${item.subject}</h4>
        <p class="text-3xl font-bold">${item.grade}</p>
      </div>
    `;

    container.appendChild(gradeCard);
  });


  document.addEventListener('DOMContentLoaded', function () {
  const payments = JSON.parse(localStorage.getItem('studentPayments')) || [];
  const tableBody = document.querySelector('#payment-history-body');
  tableBody.innerHTML = ''; // Clear previous content

  payments.forEach(payment => {
    const row = document.createElement('tr');
    row.className = 'border-b border-blue-100';

    const statusClass = payment.status === 'Paid'
      ? 'text-green-600'
      : payment.status === 'Pending'
      ? 'text-yellow-500'
      : 'text-red-500';

    row.innerHTML = `
      <td class="py-1">${payment.payment_date}</td>
      <td class="py-1">₱${parseFloat(payment.amount_paid).toLocaleString()}</td>
      <td class="py-1 ${statusClass}">${payment.status}</td>
    `;
    tableBody.appendChild(row);
  });
});
</script>


</body>
</html>
