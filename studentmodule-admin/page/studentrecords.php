<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "iscpdb");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Handle delete request via POST (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $student_id_to_delete = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM `studentinformation` WHERE `student_id` = ?");
    $stmt->bind_param("s", $student_id_to_delete);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit;
}

// Fetch updated student records with all required columns
$sql = "SELECT student_id, fullname, school_year, current_sem, course, enrollment_status, 
        student_type, birth_cert, form137, tor, good_moral, honorable_dismissal, 
        library_clearance, accounting_clearance, dept_head_clearance, final_clearance 
        FROM studentinformation";
$result = $conn->query($sql);
?>
<script src="https://cdn.tailwindcss.com"></script>
<div>
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-600">Student Records</h2>

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
    <table class="min-w-full bg-white border border-gray-300 rounded" id="studentTable">
      <thead class="bg-blue-600 text-white">
        <tr>
          <th class="px-4 py-2 text-left">Student ID</th>
          <th class="px-4 py-2 text-left">Full Name</th>
          <th class="px-4 py-2 text-left">School Year</th>
          <th class="px-4 py-2 text-left">Semester</th>
          <th class="px-4 py-2 text-left">Course</th>
          <th class="px-4 py-2 text-left">Enrollment Status</th>
          <th class="px-4 py-2 text-left">Student Type</th>
          <th class="px-4 py-2 text-left">Birth Cert</th>
          <th class="px-4 py-2 text-left">Form 137</th>
          <th class="px-4 py-2 text-left">TOR</th>
          <th class="px-4 py-2 text-left">Good Moral</th>
          <th class="px-4 py-2 text-left">Honorable Dismissal</th>
          <th class="px-4 py-2 text-left">Library Clearance</th>
          <th class="px-4 py-2 text-left">Accounting Clearance</th>
          <th class="px-4 py-2 text-left">Department Head Clearance</th>
          <th class="px-4 py-2 text-left">Final Clearance</th>
          <th class="px-4 py-2 text-left">Actions</th>
        </tr> 
      </thead>
      <tbody class="text-gray-700">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-t" data-student-id="<?= htmlspecialchars($row['student_id']); ?>">
              <td class="px-4 py-2"><?= htmlspecialchars($row['student_id']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['fullname']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['school_year']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['current_sem']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['course']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['enrollment_status']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['student_type']); ?></td>
              <td class="px-4 py-2">
  <img src="../img/<?= $row['birth_cert'] == '1' ? 'checklogo.png' : 'noentrylogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= $row['form137'] == '1' ? 'checklogo.png' : 'noentrylogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= $row['tor'] == '1' ? 'checklogo.png' : 'noentrylogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= $row['good_moral'] == '1' ? 'checklogo.png' : 'noentrylogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= $row['honorable_dismissal'] == '1' ? 'checklogo.png' : 'noentrylogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= strtolower($row['library_clearance']) === 'cleared' ? 'checklogo.png' : 'pendinglogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= strtolower($row['accounting_clearance']) === 'cleared' ? 'checklogo.png' : 'pendinglogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= strtolower($row['dept_head_clearance']) === 'cleared' ? 'checklogo.png' : 'pendinglogo.png'; ?>" class="h-6 w-6" />
</td>
<td class="px-4 py-2">
  <img src="../img/<?= strtolower($row['final_clearance']) === 'cleared' ? 'checklogo.png' : 'pendinglogo.png'; ?>" class="h-6 w-6" />
</td>

              <td class="px-4 py-2 relative">
  <button 
    class="bg-blue-100 font-bold border border-blue-900 text-blue-900 px-3 py-1 rounded dropdown-button"
    type="button"
    aria-haspopup="true"
    aria-expanded="false"
  >
    manage
  </button>
<div class="dropdown-menu hidden items-center flex flex-col items-center justify-center p-2 absolute right-0 mt-1 w-28 bg-white text-blue-500 font-semibold border border-blue-500 rounded-xl shadow-xl z-50">
    <button 
      class="w-20 text-center p-1 hover:bg-green-300 hover:rounded-lg hover:scale-110 hover:border hover:border-green-900 flex items-center gap-1 edit-btn"
      title="Edit"
      data-id="<?= htmlspecialchars($row['student_id']); ?>"
    >
      <img src="../img/editlogo.png" alt="Edit" class="h-4 w-4" /> Edit
    </button>
    <button 
      class="w-20 text-center p-1 hover:bg-red-300 hover:rounded-lg hover:scale-110 hover:border hover:border-red-900 flex items-center delete-btn"   
      data-id="<?= htmlspecialchars($row['student_id']); ?>" 
      title="Delete"
    >
      <img src="../img/deletelogo.png" alt="Delete" class="h-4 w-4" /> Delete
    </button>
  </div>
</td>

            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="17" class="text-center px-4 py-4 text-gray-500">No student records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $conn->close(); ?>

<script>
 document.addEventListener('DOMContentLoaded', () => {
  // Dropdown toggle logic
  document.querySelectorAll('.dropdown-button').forEach(button => {
    button.addEventListener('click', (e) => {
      e.stopPropagation();
      const menu = button.nextElementSibling;
      // Close other dropdowns
      document.querySelectorAll('.dropdown-menu').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
      });
      menu.classList.toggle('hidden');
    });
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
      menu.classList.add('hidden');
    });
  });

  // Delete button inside dropdown
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
      const student_id = button.getAttribute('data-id');
      if (confirm('Are you sure you want to delete student ID ' + student_id + '?')) {
        fetch('', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: 'delete_id=' + encodeURIComponent(student_id)
        })
        .then(response => response.text())
        .then(data => {
          if(data.trim() === "success") {
            const row = button.closest('tr');
            row.remove();
          } else {
            alert('Failed to delete the record.');
          }
        })
        .catch(() => alert('Error deleting record.'));
      }
    });
  });

  // Edit button inside dropdown (you can implement your edit logic here)
  document.querySelectorAll('.edit-btn').forEach(button => {
    button.addEventListener('click', () => {
      const student_id = button.getAttribute('data-id');
      alert('Edit function for student ID ' + student_id + ' goes here.');
      // You can redirect or open an edit modal here
    });
  });

  // Real-time search (keep your existing logic)
  const searchBox = document.getElementById('searchBox');
  const tableBody = document.querySelector('#studentTable tbody');

  searchBox.addEventListener('input', () => {
  const filter = searchBox.value.toLowerCase();

  Array.from(tableBody.rows).forEach(row => {
    let rowHasMatch = false;

    // Reset cells to original text first
    Array.from(row.cells).forEach(cell => {
      cell.innerHTML = cell.textContent;
    });

    // Then check and highlight matches
    Array.from(row.cells).forEach(cell => {
      const originalText = cell.textContent;

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
