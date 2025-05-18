<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<div id="myAccountModal" class="fixed inset-0 flex items-center rounded-xl justify-center bg-black bg-opacity-50 z-50 hidden">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg font-poppins">
    
    <div class="h-24 w-full bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-xl"></div>
    
    <div class="top-16 z-10 flex items-center flex-col gap-4 px-5 py-5">
      <div class="-mt-20">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-gray-300 bg-white rounded-full p-2 border-4 border-white shadow" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8V21h19.2v-1.8c0-3.2-6.4-4.8-9.6-4.8z"/>
        </svg>
      </div>

      <table class="table-auto border-separate border-spacing-y-4 w-full text-gray-700">
  <tr>
    <td class="font-light text-sm">Student Name:</td>
    <td id="studentname" class="font-bold text-sm text-black">Loading...</td>
  </tr>
  <tr>
    <td class="font-light text-sm">Student ID:</td>
    <td id="studentID" class="font-light text-sm">Loading...</td>
  </tr>
  <tr>
    <td class="font-light text-sm">Section:</td>
    <td id="studentSection" class="font-light text-sm">Loading...</td>
  </tr>
  <tr>
    <td class="font-light text-sm">Year:</td>
    <td id="studentyear" class="font-light text-sm">Loading...</td>
  </tr>
  <tr>
    <td class="font-light text-sm">Semester Enrolled:</td>
    <td id="studentSemester" class="font-light text-sm">Loading...</td>
  </tr>
    <tr>
    <td class="font-light text-sm">New Password:</td>
    <td><input type="password" id="newPassword" class="border border-black px-2 py-1 rounded w-full text-sm" /></td>
  </tr>
</table>

      <div class="flex items-center gap-3">
        <button onclick="closeMyAccount()"
          class="bg-red-400 transition-all gradient text-[15px] text-white px-3 py-[6px] rounded-full flex items-center gap-1">
          Close
        </button>
        <button onclick="updatePassword()" 
  class="bg-green-600 transition-all gradient text-[15px] text-white px-3 py-[6px] rounded-full flex items-center gap-1">Update Password</button>
      </div>
    </div>

  </div>
</div>

<script>
  function updatePassword() {
  const studentID = document.getElementById('studentID').innerText.trim();
  const newPassword = document.getElementById('newPassword').value.trim();

  if (!newPassword) {
    alert('Please enter a new password.');
    return;
  }

  fetch('update_password.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: `student_id=${encodeURIComponent(studentID)}&new_password=${encodeURIComponent(newPassword)}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert('Password updated successfully.');
      document.getElementById('newPassword').value = '';
    } else {
      alert('Error: ' + data.message);
    }
  })
  .catch(() => alert('Error updating password.'));
}

</script>
