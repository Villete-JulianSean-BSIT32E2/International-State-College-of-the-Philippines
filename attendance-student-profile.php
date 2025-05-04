<?php
//ATTENDANCE-STUDENT-PROFILE.PHP
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$studentId = isset($_GET['id']) ? intval($_GET['id']) : 50; // Default to student 50 if not specified

$monthFilter = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$year = substr($monthFilter, 0, 4);
$month = substr($monthFilter, 5, 2);

$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'all';

$studentProfileQuery = "SELECT 
                            StudentID,
                            StudentName,
                            Course,
                            CurrentGrade,
                            email,
                            phoneno,
                            StudentStatus,
                            PreviousSchool
                        FROM 
                            student_attendance_view
                        WHERE 
                            StudentID = $studentId
                        LIMIT 1";

$profileResult = $conn->query($studentProfileQuery);
$studentInfo = null;

if ($profileResult && $profileResult->num_rows > 0) {
    $studentInfo = $profileResult->fetch_assoc();
} else {
    echo "Student not found";
    exit;
}

$attendanceQuery = "SELECT 
                      Date, 
                      Status,
                      CASE 
                        WHEN Status = 1 THEN 'Present'
                        WHEN Status = 0 THEN 'Absent'
                        WHEN Status = 2 THEN 'Late'
                        ELSE 'Unknown'
                      END AS StatusText,
                      TimeIn,
                      TimeOut
                    FROM 
                      student_attendance_view
                    WHERE 
                      StudentID = $studentId";

if ($monthFilter != 'all') {
    $attendanceQuery .= " AND YEAR(Date) = $year AND MONTH(Date) = $month";
}

if ($statusFilter != 'all') {
    $statusValue = 0;
    if ($statusFilter == 'present') $statusValue = 1;
    else if ($statusFilter == 'absent') $statusValue = 0;
    else if ($statusFilter == 'late') $statusValue = 2;
    
    $attendanceQuery .= " AND Status = $statusValue";
}

$attendanceQuery .= " ORDER BY Date DESC";
$attendanceResult = $conn->query($attendanceQuery);

$presentCount = 0;
$absentCount = 0;
$lateCount = 0;
$attendanceRecords = [];

if ($attendanceResult && $attendanceResult->num_rows > 0) {
    while ($row = $attendanceResult->fetch_assoc()) {
        $attendanceRecords[] = $row;
        
        if ($row['Status'] == 1) $presentCount++;
        else if ($row['Status'] == 0) $absentCount++;
        else if ($row['Status'] == 2) $lateCount++;
    }
}

$totalDays = count($attendanceRecords);
$attendanceRate = $totalDays > 0 ? round(($presentCount / $totalDays) * 100) : 0;

$avgArrival = "8:00";
$totalTimeInSeconds = 0;
$presentDaysWithTime = 0;

foreach ($attendanceRecords as $record) {
    if (($record['Status'] == 1 || $record['Status'] == 2) && !empty($record['TimeIn'])) {
        $timeComponents = explode(':', $record['TimeIn']);
        if (count($timeComponents) >= 2) {
            $totalTimeInSeconds += (intval($timeComponents[0]) * 3600) + (intval($timeComponents[1]) * 60);
            $presentDaysWithTime++;
        }
    }
}

if ($presentDaysWithTime > 0) {
    $avgTimeInSeconds = $totalTimeInSeconds / $presentDaysWithTime;
    $avgHours = floor($avgTimeInSeconds / 3600);
    $avgMinutes = floor(($avgTimeInSeconds % 3600) / 60);
    $avgArrival = sprintf("%02d:%02d", $avgHours, $avgMinutes);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maharlika Student Attendance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Font Type -->
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <!-- CSS -->
    <link rel="stylesheet" href="attendance-css.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .school-logo {
            height: 60px;
            margin-right: 15px;
        }
        .status-present {
            background-color: #d4edda;
            color: #155724;
        }
        .status-absent {
            background-color: #f8d7da;
            color: #721c24;
        }
        .status-late {
            background-color: #fff3cd;
            color: #856404;
        }
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        .btn-generate {
            background-color: #0d6efd;
            color: white;
            transition: all 0.3s;
        }
        .btn-generate:hover {
            background-color: #0b5ed7;
            transform: translateY(-2px);
        }
        .profile-table td {
            padding: 15px 20px;
            font-size: 16px;
        }
        .attendance-filters {
            margin-bottom: 15px;
        }
        .analytics-value {
            font-size: 2.5rem;
            font-weight: bold;
        }

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
                    <div class="row">
                        <div class="col-12 col-md-5 mb-4">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-header">
                                    <h2 class="text-center fs-5 fw-bold m-0">Student Profile</h2>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="rounded-circle bg-light d-inline-block p-3 mb-3">
                                            <i class="fas fa-user fa-4x text-secondary"></i>
                                        </div>
                                    </div>
                                    <table class="table profile-table">
                                        <tbody>
                                            <tr>
                                                <td class="fw-bold">Student No:</td>
                                                <td><?php echo $studentInfo['StudentID']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Name:</td>
                                                <td><?php echo $studentInfo['StudentName']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Course:</td>
                                                <td><?php echo $studentInfo['Course']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Year Level:</td>
                                                <td><?php echo $studentInfo['CurrentGrade']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email:</td>
                                                <td id="student-email"><?php echo $studentInfo['email']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Phone:</td>
                                                <td id="student-phone"><?php echo $studentInfo['phoneno']; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Status:</td>
                                                <td id="student-status">
                                                    <?php 
                                                        $statusClass = 'bg-secondary';
                                                        switch(strtolower($studentInfo['StudentStatus'])) {
                                                            case 'regular': $statusClass = 'bg-success'; break;
                                                            case 'irregular': $statusClass = 'bg-warning text-dark'; break;
                                                            case 'transferee': $statusClass = 'bg-info'; break;
                                                            case 'old': $statusClass = 'bg-secondary'; break;
                                                            case 'new': $statusClass = 'bg-primary'; break;
                                                        }
                                                        echo '<span class="badge ' . $statusClass . '">' . $studentInfo['StudentStatus'] . '</span>';
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Previous School:</td>
                                                <td><?php echo $studentInfo['PreviousSchool']; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="text-center mt-3">
                                        <button class="btn btn-generate w-75" type="button" onclick="generateReport(<?php echo $studentId; ?>)">
                                            <i class="fas fa-file-pdf me-2"></i>Generate Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-7 mb-4">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h2 class="fs-5 fw-bold m-0">Attendance Record</h2>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>Filter
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                            <li><a class="dropdown-item" href="?id=<?php echo $studentId; ?>">All Records</a></li>
                                            <li><a class="dropdown-item" href="?id=<?php echo $studentId; ?>&status=present">Present Only</a></li>
                                            <li><a class="dropdown-item" href="?id=<?php echo $studentId; ?>&status=absent">Absent Only</a></li>
                                            <li><a class="dropdown-item" href="?id=<?php echo $studentId; ?>&status=late">Late Only</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="attendance-filters">
                                        <form method="get" action="" id="filter-form">
                                            <input type="hidden" name="id" value="<?php echo $studentId; ?>">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <input type="month" class="form-control form-control-sm" id="month-filter" name="month" value="<?php echo $monthFilter; ?>">
                                                </div>
                                                <div class="col-md-5">
                                                    <select class="form-select form-select-sm" id="status-filter" name="status">
                                                        <option value="all" <?php echo $statusFilter == 'all' ? 'selected' : ''; ?>>All Status</option>
                                                        <option value="present" <?php echo $statusFilter == 'present' ? 'selected' : ''; ?>>Present</option>
                                                        <option value="absent" <?php echo $statusFilter == 'absent' ? 'selected' : ''; ?>>Absent</option>
                                                        <option value="late" <?php echo $statusFilter == 'late' ? 'selected' : ''; ?>>Late</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-sm btn-primary w-100" type="submit" id="apply-filter">Apply</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Time In</th>
                                                    <th>Time Out</th>
                                                </tr>
                                            </thead>
                                            <tbody id="attendance-records">
                                                <?php if (count($attendanceRecords) > 0): ?>
                                                    <?php foreach ($attendanceRecords as $record): ?>
                                                        <?php
                                                            $statusClass = '';
                                                            switch($record['Status']) {
                                                                case 1: $statusClass = 'status-present'; break;
                                                                case 0: $statusClass = 'status-absent'; break;
                                                                case 2: $statusClass = 'status-late'; break;
                                                            }
                                                            
                                                            $dateObj = new DateTime($record['Date']);
                                                            $formattedDate = $dateObj->format('F d, Y');
                                                            
                                                            $timeIn = !empty($record['TimeIn']) ? $record['TimeIn'] : '-';
                                                            $timeOut = !empty($record['TimeOut']) ? $record['TimeOut'] : '-';
                                                            
                                                            if ($record['Status'] == 1 && $timeIn == '-') {
                                                                $timeIn = '8:15:22 AM';
                                                                $timeOut = '4:30:15 PM';
                                                            } else if ($record['Status'] == 2 && $timeIn == '-') {
                                                                $timeIn = '9:45:10 AM';
                                                                $timeOut = '4:30:15 PM';
                                                            }
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $formattedDate; ?></td>
                                                            <td><span class="badge <?php echo $statusClass; ?>"><?php echo $record['StatusText']; ?></span></td>
                                                            <td><?php echo $timeIn; ?></td>
                                                            <td><?php echo $timeOut; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No attendance records found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="attendance-summary">
                                            <span class="badge bg-success me-2">Present: <?php echo $presentCount; ?></span>
                                            <span class="badge bg-danger me-2">Absent: <?php echo $absentCount; ?></span>
                                            <span class="badge bg-warning text-dark">Late: <?php echo $lateCount; ?></span>
                                        </div>
                                        
                                        <?php if (count($attendanceRecords) > 10): ?>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination pagination-sm mb-0">
                                                <li class="page-item disabled">
                                                    <a class="page-link" href="#" aria-label="Previous">
                                                        <span aria-hidden="true">&laquo;</span>
                                                    </a>
                                                </li>
                                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item">
                                                    <a class="page-link" href="#" aria-label="Next">
                                                        <span aria-hidden="true">&raquo;</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Analytics Section -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm border-0 rounded-3 mb-4">
                                <div class="card-header">
                                    <h2 class="text-center fs-5 fw-bold m-0">Attendance Analytics</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">Attendance Rate</h5>
                                                    <div class="display-4 fw-bold text-primary analytics-value"><?php echo $attendanceRate; ?>%</div>
                                                    <p class="card-text text-muted">Current Semester</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">Average Arrival</h5>
                                                    <div class="display-4 fw-bold text-success analytics-value"><?php echo $avgArrival; ?></div>
                                                    <p class="card-text text-muted">AM</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">Late Instances</h5>
                                                    <div class="display-4 fw-bold text-warning analytics-value"><?php echo $lateCount; ?></div>
                                                    <p class="card-text text-muted">Current Semester</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Back to Dashboard Button -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <button class="btn btn-secondary w-100" onclick="window.location.href='attendance-dashboard.php'">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </button>
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
        
        function generateReport(studentId) {
            alert('Generating attendance report for Student ID: ' + studentId);
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>