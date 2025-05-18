<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $student_id_to_delete = $_POST['delete_id'];

    $stmt = $conn->prepare("DELETE FROM `studentlogin` WHERE `student_id` = ?");
    $stmt->bind_param("s", $student_id_to_delete);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
    exit;
}

$sql = "SELECT student_id, user, password FROM studentlogin";
$result = $conn->query($sql);
?>

<script src="https://cdn.tailwindcss.com"></script>
<div>
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold text-blue-600">Account Settings</h2>

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
    <table class="min-w-full bg-white border border-gray-300 rounded"  id="accountSettingsTable">
      <thead class="bg-blue-600 text-white">
        <tr>
          <th class="px-4 py-2 text-left">Student ID</th>
          <th class="px-4 py-2 text-left">Username</th>
          <th class="px-4 py-2 text-left">Password</th>
          <th class="px-4 py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-t" data-student-id="<?= htmlspecialchars($row['student_id']); ?>">
              <td class="px-4 py-2"><?= htmlspecialchars($row['student_id']); ?></td>
              <td class="px-4 py-2"><?= htmlspecialchars($row['user']); ?></td>
              <td class="px-4 py-2 relative">
                <span class="password-mask"><?= str_repeat('*', strlen($row['password'])); ?></span>
                <span class="password-text hidden"><?= htmlspecialchars($row['password']); ?></span>
                <button 
                  class="show-password-btn text-blue-600 underline text-sm ml-2 hidden" 
                  type="button"
                  aria-label="Show password"
                  title="Show password">
                  Show
                </button>
              </td>
             <td class="px-4 py-2">
  <div class="relative inline-block text-left">
    <button type="button" class="bg-blue-100 font-bold border border-blue-900 text-blue-900 px-3 py-1 rounded dropdown-button" title="Actions">
      manage
    </button>
    <div class="dropdown-menu hidden items-center flex flex-col items-center justify-center p-2 absolute right-0 mt-1 w-28 bg-white text-blue-500 font-semibold border border-blue-500 rounded-xl shadow-xl z-50">
      <button 
        class="w-20 text-center p-1 hover:bg-green-300 hover:rounded-lg hover:scale-110 hover:border hover:border-green-900 flex items-center gap-1 edit-btn"
        data-id="<?= htmlspecialchars($row['student_id']); ?>" 
        title="Edit">
        <img src="../img/editlogo.png" alt="Edit" class="h-6 w-6" /> Edit
      </button>
      <button 
        class="w-20 text-center p-1 hover:bg-red-300 hover:rounded-lg hover:scale-110 hover:border hover:border-red-900 flex items-center delete-btn" 
        data-id="<?= htmlspecialchars($row['student_id']); ?>" 
        title="Delete">
        <img src="../img/deletelogo.png" alt="Delete" class="h-6 w-6" /> Delete
      </button>
    </div>
  </div>
</td>

            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center px-4 py-4 text-gray-500">No account records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $conn->close(); ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Delete button functionality
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
      const student_id = button.getAttribute('data-id');
      if (confirm('Are you sure you want to delete account for student ID ' + student_id + '?')) {
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
            alert('Failed to delete the account.');
          }
        })
        .catch(() => alert('Error deleting account.'));
      }
    });
  });

  // Search filter with highlight
  const searchBox = document.getElementById('searchBox');
  const tableBody = document.querySelector('#accountTable tbody');

  searchBox.addEventListener('input', () => {
    const filter = searchBox.value.toLowerCase();

    Array.from(tableBody.rows).forEach(row => {
      let rowHasMatch = false;

      Array.from(row.cells).forEach(cell => {
        // Skip cells containing buttons or images (actions)
        if (cell.querySelector('button') || cell.querySelector('img')) return;

        const originalText = cell.textContent;
        cell.innerHTML = originalText; // reset HTML before applying highlights

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

  // Future feature: toggle password visibility (currently hidden)
  document.querySelectorAll('.show-password-btn').forEach(button => {
    button.addEventListener('click', () => {
      const td = button.closest('td');
      const mask = td.querySelector('.password-mask');
      const text = td.querySelector('.password-text');
      
      if (text.classList.contains('hidden')) {
        text.classList.remove('hidden');
        mask.classList.add('hidden');
        button.textContent = 'Hide';
      } else {
        text.classList.add('hidden');
        mask.classList.remove('hidden');
        button.textContent = 'Show';
      }
    });
  });
});
</script>
