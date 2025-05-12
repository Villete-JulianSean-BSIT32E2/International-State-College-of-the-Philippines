<?php
//ATTENDANCE-SECTIONS.PHP
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$coursesQuery = "SELECT 
                   Course, 
                   CurrentGrade,
                   COUNT(DISTINCT StudentID) as StudentCount 
                 FROM 
                   student_attendance_view 
                 GROUP BY 
                   Course, CurrentGrade 
                 ORDER BY 
                   Course ASC, CurrentGrade ASC";

$coursesResult = $conn->query($coursesQuery);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = '';

if (!empty($search)) {
    $searchCondition = "WHERE Course LIKE '%$search%' OR CurrentGrade LIKE '%$search%'";
    
    $coursesQuery = "SELECT 
                      Course, 
                      CurrentGrade,
                      COUNT(DISTINCT StudentID) as StudentCount 
                    FROM 
                      student_attendance_view 
                    $searchCondition
                    GROUP BY 
                      Course, CurrentGrade 
                    ORDER BY 
                      Course ASC, CurrentGrade ASC";
    
    $coursesResult = $conn->query($coursesQuery);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maharlika Student Attendance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Type -->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="attendance-css.css">
    <style>
    .sidebar {
      width: 240px;
      background-color: #13334D;
      color: white;
      height: 100vh;
      padding: 1rem;
    }

    .sidebar img {
      width: 80px;
      margin-bottom: 1rem;
    }

    .nav-item {
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .nav-item.active,
    .nav-item:hover {
      background-color: #3e6df3;
    }
    </style>
</head>
<body>
<div class="d-flex">
    <div class="sidebar collapse d-md-block" id="sidebarMenu">
        <img src="logo.png" style="width: 100px; height: auto; border-radius: 50%;" alt="Logo" />
        <div class="nav-item">Dashboard</div>
        <div class="nav-item"><a href="index.html" style="text-decoration: none; color: white;">Home</a></div>
        <div class="nav-item">Registrar</div>
        <div class="nav-item">Cashier</div>
        <div class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="attendanceDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; color: white;">
                Attendance
            </a>
            <ul class="dropdown-menu bg-dark" aria-labelledby="attendanceDropdown">
                <li><a class="dropdown-item text-white" href="attendance-scan.php">Scan</a></li>
                <li><a class="dropdown-item text-white" href="attendance-dashboard.php">Dashboard</a></li>
            </ul>
        </div>
    </div>

    <div class="flex-grow-1">
        <div class="container-fluid py-4">
        <button class="btn btn-dark d-md-none m-2 form-control mx-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
            ☰ Menu
        </button>
            <div class="main-content">
                <div class="page-header py-3 px-0 rounded-2 mb-4">
                    <div class="container-fluid">
                        <div class="row align-items-center gx-3">
                            <div class="col-12 col-md-7 col-lg-8 mb-3 mb-md-0">
                                <div class="d-flex align-items-center">
                                    <img src="logo.png" alt="School Logo" style="width: 100px; height: auto; border-radius: 50%;" class="me-3" />
                                    <h1 class="fw-bold mb-0">International State College of the Philippines</h1>
                                </div>
                            </div>
                            
                            <div class="col-12 col-md-5 col-lg-4">
                                <div class="d-flex flex-column align-items-start align-items-md-end">
                                    <div id="current-date" class="mb-1 fs-6"></div>
                                    <div id="current-time" class="fs-6"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="content">
                        <div class="mb-4">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-header d-flex justify-content-between align-items-center px-4">
                                    <h2 class="card-title mb-0">Classes & Sections</h2>
                                    <div class="d-flex">
                                        <form method="get" action="" class="d-flex">
                                            <input type="text" id="search" name="search" class="form-control me-2" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by course or year">
                                            <button type="submit" class="btn" style="background-color:#fff; color: #13334D;">Search</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="overview-table">
                                        <table class="table text-center">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Course</th>
                                                    <th scope="col">Year Level</th>
                                                    <th scope="col">Number of Students</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($coursesResult->num_rows > 0) {
                                                    while($row = $coursesResult->fetch_assoc()) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["Course"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["CurrentGrade"]) . "</td>";
                                                        echo "<td>" . $row["StudentCount"] . "</td>";
                                                        echo "<td>
                                                                <a href='attendance-section-student.php?course=" . urlencode($row["Course"]) . "&grade=" . urlencode($row["CurrentGrade"]) . "' class='btn btn-primary'>View</a>
                                                            </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='4'>No sections found</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <button class="btn form-control mt-3" type="button" onclick="window.location.href='attendance-dashboard.php';">Back to Dashboard</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentDateElement = document.getElementById('current-date');
            const currentTimeElement = document.getElementById('current-time');
            
            function updateDateTime() {
                const now = new Date();
                const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
                
                currentDateElement.textContent = now.toLocaleDateString('en-US', dateOptions);
                currentTimeElement.textContent = now.toLocaleTimeString('en-US', timeOptions);
            }
            
            updateDateTime();
            setInterval(updateDateTime, 1000);
        });
    </script>
</body>
</html>

<?php
// Close connection
$conn->close();
?>