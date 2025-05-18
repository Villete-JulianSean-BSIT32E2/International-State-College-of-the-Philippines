<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "iscpdb");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle delete request via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $id = $_POST['delete_id'];

  $stmt = $conn->prepare("DELETE FROM attendance WHERE Admission_ID = ?");
  $stmt->bind_param("s", $id);

  echo $stmt->execute() ? "success" : "error";
  $stmt->close();
  exit;
}

// Fetch attendance records
$sql = "SELECT Admission_ID, Date, Status, Notes, TimeIn, TimeOut FROM attendance";
$result = $conn->query($sql);
?>

<script src="https://cdn.tailwindcss.com"></script>

<div>
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-600">Attendance Records</h2>

    <div class="relative w-52">
      <input 
        type="text" 
        id="searchBox" 
        placeholder="Search" 
        class="w-full pl-3 pr-10 py-1 border border-blue-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300"
      />
      <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none border-l border-blue-300">
        <img src="../img/searchlogo.png" alt="Search" class="h-5 w-5" />
      </div>
    </div>
  </div>

  <div class="overflow-x-auto rounded-xl">
    <table class="min-w-full bg-white border border-gray-300 rounded" id="attendanceTable">
      <thead class="bg-blue-600 text-white">
        <tr>
          <th class="px-4 py-2 text-left">Student ID</th>
          <th class="px-4 py-2 text-left">Date</th>
          <th class="px-4 py-2 text-left">Status</th>
          <th class="px-4 py-2 text-left">Notes</th>
          <th class="px-4 py-2 text-left">Time In</th>
          <th class="px-4 py-2 text-left">Time Out</th>
          <th class="px-4 py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-t" data-id="<?= htmlspecialchars($row['Admission_ID']); ?>">
              <td class="px-4 py-2"><?= htmlspecialchars($row['Admission_ID']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['Date']); ?></td>
             <td class="px-4 py-2 font-semibold
  <?php
    if ($row['Status'] == 0) echo 'text-red-600';
    elseif ($row['Status'] == 1) echo 'text-green-600';
    elseif ($row['Status'] == 2) echo 'text-yellow-500';
  ?>">
  <?php
    if ($row['Status'] == 0) echo 'ABSENT';
    elseif ($row['Status'] == 1) echo 'PRESENT';
    elseif ($row['Status'] == 2) echo 'LATE';
  ?>
</td>

              <td class="px-4 py-2"><?= htmlspecialchars($row['Notes']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['TimeIn']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['TimeOut']); ?></td>
             <td class="px-4 py-2">
  <div class="relative inline-block text-left">
    <button 
      type="button" 
      class="bg-blue-100 font-bold border border-blue-900 text-blue-900  px-3 py-1 rounded dropdown-button"
      title="Actions"
      aria-haspopup="true" 
      aria-expanded="false"
    >
      manage
    </button>
    <div class="dropdown-menu hidden items-center flex flex-col items-center justify-center p-2 absolute right-0 mt-1 w-28 bg-white text-blue-500 font-semibold border border-blue-500 rounded-xl shadow-xl z-50">
      <button 
         class="w-20 text-center p-1 hover:bg-green-300 hover:rounded-lg hover:scale-110 hover:border hover:border-green-900 flex items-center gap-1 edit-btn"
        data-id="<?= htmlspecialchars($row['Admission_ID']); ?>" 
        title="Edit"
      >
        <img src="../img/editlogo.png" alt="Edit" class="h-5 w-5" /> Edit
      </button>
      <button 
        class="w-20 text-center p-1 hover:bg-red-300 hover:rounded-lg hover:scale-110 hover:border hover:border-red-900 flex items-center delete-btn"  
        data-id="<?= htmlspecialchars($row['Admission_ID']); ?>" 
        title="Delete"
      >
        <img src="../img/deletelogo.png" alt="Delete" class="h-5 w-5" /> Delete
      </button>
    </div>
  </div>
</td>



            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" class="text-center px-4 py-4 text-gray-500">No attendance records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $conn->close(); ?>

<script>




document.addEventListener('DOMContentLoaded', () => {
  // Delete button
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
      const id = button.getAttribute('data-id');
      if (confirm('Are you sure you want to delete record with ID ' + id + '?')) {
        fetch('', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'delete_id=' + encodeURIComponent(id)
        })
        .then(res => res.text())
        .then(data => {
          if (data.trim() === 'success') {
            const row = button.closest('tr');
            if (row) row.remove();
          } else {
            alert('Failed to delete record.');
          }
        })
        .catch(() => alert('Error deleting record.'));
      }
    });
  });

  // Real-time search with yellow highlight
  const searchBox = document.getElementById('searchBox');
  const tableBody = document.querySelector('#attendanceTable tbody');

  searchBox.addEventListener('input', () => {
    const filter = searchBox.value.toLowerCase();

    Array.from(tableBody.rows).forEach(row => {
      let rowHasMatch = false;

      Array.from(row.cells).forEach(cell => {
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
});
</script>
