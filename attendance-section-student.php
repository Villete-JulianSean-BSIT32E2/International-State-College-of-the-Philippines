<?php
//ATTENDANCE-SECTION-STUDENT.PHP
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$course = isset($_GET['course']) ? $_GET['course'] : '';
$grade = isset($_GET['grade']) ? $_GET['grade'] : '';

$course = $conn->real_escape_string($course);
$grade = $conn->real_escape_string($grade);

$search = isset($_GET['search']) ? $_GET['search'] : '';
$searchCondition = '';

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $searchCondition = "AND (StudentName LIKE '%$search%' OR StudentID LIKE '%$search%' OR email LIKE '%$search%')";
}

$studentsQuery = "SELECT DISTINCT 
                    StudentID,
                    StudentName,
                    email,
                    phoneno,
                    StudentStatus,
                    gender
                  FROM 
                    student_attendance_view
                  WHERE 
                    Course = '$course' 
                    AND CurrentGrade = '$grade'
                    $searchCondition
                  ORDER BY 
                    StudentName ASC";

$studentsResult = $conn->query($studentsQuery);

$statsQuery = "SELECT 
                COUNT(DISTINCT StudentID) as TotalStudents,
                SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) as TotalPresent,
                SUM(CASE WHEN Status = 0 THEN 1 ELSE 0 END) as TotalAbsent,
                SUM(CASE WHEN Status = 2 THEN 1 ELSE 0 END) as TotalLate
              FROM 
                student_attendance_view
              WHERE 
                Course = '$course' 
                AND CurrentGrade = '$grade'";

$statsResult = $conn->query($statsQuery);
$stats = $statsResult->fetch_assoc();
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
        <div class="nav-item active"><a href="attendance-dashboard.php" style="text-decoration: none; color: white;">Attendance</a></div>
    </div>

    <div class="flex-grow-1">
        <div class="container-fluid py-4">
            <button class="btn btn-dark d-md-none m-2 form-control mx-0" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                ☰ Menu
            </button>
            <div class="main-content">
                <div class="page-header mb-4">
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
                                    <h2 class="card-title mb-0"><?php echo htmlspecialchars($course) . " - " . htmlspecialchars($grade); ?> Students</h2>
                                    <div class="d-flex">
                                        <form method="get" action="" class="d-flex">
                                            <input type="hidden" name="course" value="<?php echo htmlspecialchars($course); ?>">
                                            <input type="hidden" name="grade" value="<?php echo htmlspecialchars($grade); ?>">
                                            <input type="text" id="search" name="search" class="form-control me-2" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search student">
                                            <button type="submit" class="btn" style="background-color:#fff; color: #13334D;">Search</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Section Statistics -->
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <div class="alert alert-info text-center" role="alert">
                                                <h5>Total Students</h5>
                                                <h3><?php echo $stats['TotalStudents']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="alert alert-success text-center" role="alert">
                                                <h5>Total Present</h5>
                                                <h3><?php echo $stats['TotalPresent']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="alert alert-danger text-center" role="alert">
                                                <h5>Total Absent</h5>
                                                <h3><?php echo $stats['TotalAbsent']; ?></h3>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="alert alert-warning text-center" role="alert">
                                                <h5>Total Late</h5>
                                                <h3><?php echo $stats['TotalLate']; ?></h3>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="overview-table">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Name</th>
                                                    <th scope="col">Student ID</th>
                                                    <th scope="col">Gender</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Email</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($studentsResult->num_rows > 0) {
                                                    while($row = $studentsResult->fetch_assoc()) {
                                                        $genderDisplay = ($row["gender"] == 'm') ? 'Male' : (($row["gender"] == 'f') ? 'Female' : 'Other');
                                                        
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($row["StudentName"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["StudentID"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($genderDisplay) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["StudentStatus"]) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                                        echo "<td>
                                                                <a href='attendance-student-profile.php?id=" . $row["StudentID"] . "' class='btn btn-primary'>View</a>
                                                            </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='6'>No students found in this section</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <button class="btn form-control mt-3" type="button" onclick="window.location.href='attendance-sections.php';">Back to Sections</button>
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