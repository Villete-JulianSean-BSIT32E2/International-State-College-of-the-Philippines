<?php
// Connect to database
$conn = new mysqli("localhost", "root", "", "iscpdb");

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch student records
$sql = "SELECT student_id, fullname, school_year, current_sem, course, enrollment_status, tuition_fee_status FROM studentinformation";
$result = $conn->query($sql);
?>

<div class="">
  <!-- Title -->
  <h2 class="text-2xl font-bold text-blue-600 mb-4">Student Records</h2>

  <!-- Table -->
  <div class="overflow-x-auto rounded-xl">
    <table class="min-w-full bg-white border border-gray-300 rounded">
      <thead class="bg-blue-600 text-white">
        <tr>
          <th class="px-4 py-2 text-left">Student ID</th>
          <th class="px-4 py-2 text-left">Full Name</th>
          <th class="px-4 py-2 text-left">School Year</th>
          <th class="px-4 py-2 text-left">Semester</th>
          <th class="px-4 py-2 text-left">Course</th>
          <th class="px-4 py-2 text-left">Enrollment Status</th>
          <th class="px-4 py-2 text-left">Tuition Fee Status</th>
          <th class="px-4 py-2 text-left">Actions</th>
        </tr>
      </thead>
      <tbody class="text-gray-700">
        <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="border-t">
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['student_id']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['fullname']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['school_year']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['current_sem']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['course']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['enrollment_status']); ?></td>
              <td class="px-4 py-2"><?php echo htmlspecialchars($row['tuition_fee_status']); ?></td>
              <td class="px-4 py-2">
                <div class="flex gap-2">
                  <button class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded flex items-center gap-1">
                    <img src="../img/editlogo.png" alt="Edit" class="h-6 w-6">
                  </button>
                  <button class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded flex items-center gap-1">
                    <img src="../img/deletelogo.png" alt="Delete" class="h-6 w-6">
                  </button>
                </div>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="8" class="text-center px-4 py-4 text-gray-500">No student records found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php $conn->close(); ?>
