<?php
//ATTENDANCE-DASHBOARD.PHP
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalStudentsQuery = "SELECT COUNT(DISTINCT StudentID) as total FROM student_attendance_view";
$totalResult = $conn->query($totalStudentsQuery);
$totalStudents = 0;
if ($totalResult->num_rows > 0) {
    $row = $totalResult->fetch_assoc();
    $totalStudents = $row["total"];
}

$overviewQuery = "SELECT 
                    Date,
                    COUNT(DISTINCT StudentID) AS NumberOfStudents,
                    SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) AS Present,
                    SUM(CASE WHEN Status = 0 THEN 1 ELSE 0 END) AS Absent,
                    SUM(CASE WHEN Status = 2 THEN 1 ELSE 0 END) AS Late,
                    ROUND((SUM(CASE WHEN Status = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 2) AS AttendanceRate
                  FROM 
                    student_attendance_view
                  GROUP BY 
                    Date
                  ORDER BY
                    Date DESC";

$overviewResult = $conn->query($overviewQuery);

$courseBreakdownQuery = "SELECT 
                           Course, 
                           COUNT(DISTINCT StudentID) as StudentCount 
                         FROM 
                           student_attendance_view 
                         GROUP BY 
                           Course 
                         ORDER BY 
                           StudentCount DESC";
$courseBreakdownResult = $conn->query($courseBreakdownQuery);
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
</head>
<body>
    <div class="container py-4">
        <div class="page-header mb-4">
            <div class="container-fluid">
                <div class="row align-items-center gx-3">
                    <div class="col-12 col-md-7 col-lg-8 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img src="img/school_logo.png" alt="School Logo" class="school-logo" >
                            <h1 class="fw-bold mb-0">Maharlika College</h1>
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
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-3 h-100">
                            <div class="card-header">
                                <h2 class="card-title text-center mb-0">Total Students</h2>
                            </div>
                            <div class="card-body text-center">
                                <h1 class="TotalNumberofStudent"><?php echo $totalStudents; ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 rounded-3 h-100">
                            <div class="card-header">
                                <h2 class="card-title text-center mb-0">Course Distribution</h2>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Course</th>
                                            <th class="text-center">Student Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($courseBreakdownResult->num_rows > 0) {
                                            while($row = $courseBreakdownResult->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["Course"] . "</td>";
                                                echo "<td class='text-center'>" . $row["StudentCount"] . "</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='2'>No course data available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header">
                            <h2 class="card-title text-center mb-0">Attendance Overview</h2>
                        </div>
                        <div class="card-body">
                            <div class="overview-table">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">Date</th>
                                            <th scope="col">Number of Students</th>
                                            <th scope="col">Present</th>
                                            <th scope="col">Absent</th>
                                            <th scope="col">Late</th>
                                            <th scope="col">Attendance Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($overviewResult->num_rows > 0) {
                                            while($row = $overviewResult->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["Date"] . "</td>";
                                                echo "<td>" . $row["NumberOfStudents"] . "</td>";
                                                echo "<td>" . $row["Present"] . "</td>";
                                                echo "<td>" . $row["Absent"] . "</td>";
                                                echo "<td>" . $row["Late"] . "</td>";
                                                echo "<td>" . $row["AttendanceRate"] . "%</td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6'>No attendance data available</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <button class="btn form-control" type="button" onclick="window.location.href='attendance-sections.php';">View Record</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ATTENDANCE SUMMARY -->
                <div class="mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header">
                            <h2 class="card-title text-center mb-0">Today's Attendance</h2>
                        </div>
                        <div class="card-body">
                            <?php
                            $today = date('Y-m-d');
                            $todayQuery = "SELECT 
                                            Status, 
                                            COUNT(*) as Count 
                                          FROM 
                                            student_attendance_view 
                                          WHERE 
                                            Date = '$today' 
                                          GROUP BY 
                                            Status";
                            
                            $todayResult = $conn->query($todayQuery);
                            
                            $present = 0;
                            $absent = 0;
                            $late = 0;
                            
                            if ($todayResult->num_rows > 0) {
                                while($row = $todayResult->fetch_assoc()) {
                                    if ($row["Status"] == 1) {
                                        $present = $row["Count"];
                                    } else if ($row["Status"] == 0) {
                                        $absent = $row["Count"];
                                    } else if ($row["Status"] == 2) {
                                        $late = $row["Count"];
                                    }
                                }
                            }
                            
                            $todayTotalQuery = "SELECT COUNT(DISTINCT StudentID) as TodayTotal FROM student_attendance_view WHERE Date = '$today'";
                            $todayTotalResult = $conn->query($todayTotalQuery);
                            $todayTotal = 0;
                            
                            if ($todayTotalResult->num_rows > 0) {
                                $row = $todayTotalResult->fetch_assoc();
                                $todayTotal = $row["TodayTotal"];
                            }
                            
                            $attendanceRate = ($todayTotal > 0) ? round(($present / $todayTotal) * 100, 2) : 0;
                            ?>
                            
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="alert alert-success" role="alert">
                                        <h4>Present</h4>
                                        <h2><?php echo $present; ?></h2>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert alert-danger" role="alert">
                                        <h4>Absent</h4>
                                        <h2><?php echo $absent; ?></h2>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert alert-warning" role="alert">
                                        <h4>Late</h4>
                                        <h2><?php echo $late; ?></h2>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="alert alert-info" role="alert">
                                        <h4>Attendance Rate</h4>
                                        <h2><?php echo $attendanceRate; ?>%</h2>
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
$conn->close();
?>