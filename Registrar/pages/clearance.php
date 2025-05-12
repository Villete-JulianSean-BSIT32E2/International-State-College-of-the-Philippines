<?php
$conn = new mysqli("localhost", "root", "", "iscpdb");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get selected name search term
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';

// Build SQL with optional filtering by name
$sql = "
    SELECT a.Admission_ID, a.full_name, c.library_clearance, c.accounting_clearance, 
           c.dept_head_clearance, c.final_clearance, a.course
    FROM tbladmission_addstudent a
    LEFT JOIN student_clearance c ON a.Admission_ID = c.student_id
";

if ($search_name !== '') {
    $sql .= " WHERE a.full_name LIKE '%" . $conn->real_escape_string($search_name) . "%'";
}

$students = $conn->query($sql);
?>

<style>
    a {
        color: #0b90d0;
        text-decoration: none;
        font-weight: bold;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Style for the search button */
    .search-button {
        background-color: #66b3ff;
        color: white;
        border: 1px solid #66b3ff;
        padding: 5px 15px;
        cursor: pointer;
        font-weight: bold;
        border-radius: 5px;
    }

    .search-button:hover {
        background-color: #4d99e6;
    }

    /* Style for the clear button */
    .clear-button {
        background-color: #ff6666;
        color: white;
        border: 1px solid #ff6666;
        padding: 5px 15px;
        cursor: pointer;
        font-weight: bold;
        border-radius: 5px;
    }

    .clear-button:hover {
        background-color: #e65c5c;
    }
</style>

<h2>Student Clearance</h2>

<!-- 🔍 Search by Name -->
<form method="GET" action="registrar.php" style="margin-bottom: 15px;">
    <input type="hidden" name="page" value="clearance">
    <label><strong>Search by Name:</strong></label>
    <input type="text" name="search_name" value="<?= htmlspecialchars($search_name) ?>" style="padding: 5px;">
    <button type="submit" class="search-button">Search</button>
    <a href="registrar.php?page=clearance" class="clear-button">Clear Search</a>
</form>

<!-- 📋 Clearance Table -->
<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">
    <thead style="background-color: #d9edf7;">
        <tr>
            <th>Name</th>
            <th>Course</th>
            <th>Library</th>
            <th>Accounting</th>
            <th>Dept. Head</th>
            <th>Final</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($students->num_rows === 0): ?>
            <tr><td colspan="7" style="text-align: center;">No students found.</td></tr>
        <?php else: ?>
            <?php while ($row = $students->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['full_name']) ?></td>
                <td><?= htmlspecialchars(isset($row['course']) ? $row['course'] : 'N/A') ?></td>

                <td><span class="<?= $row['library_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
                <?= isset($row['library_clearance']) ? $row['library_clearance'] : 'Pending' ?></span></td>

                <td><span class="<?= isset($row['accounting_clearance']) && $row['accounting_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
    <?= isset($row['accounting_clearance']) ? $row['accounting_clearance'] : 'Pending' ?></span></td>

<td><span class="<?= isset($row['dept_head_clearance']) && $row['dept_head_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
    <?= isset($row['dept_head_clearance']) ? $row['dept_head_clearance'] : 'Pending' ?></span></td>

<td><span class="<?= isset($row['final_clearance']) && $row['final_clearance'] === 'Cleared' ? 'cleared' : 'pending' ?>">
    <?= isset($row['final_clearance']) ? $row['final_clearance'] : 'Pending' ?></span></td>

                <td><a href="registrar.php?page=edit_clearance&id=<?= $row['Admission_ID'] ?>">Update</a></td>
            </tr>
            <?php endwhile; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- ✅ Status Styles -->
<style>
    .cleared {
        color: green;
        font-weight: bold;
    }
    .pending {
        color: red;
        font-weight: bold;
    }
</style>
