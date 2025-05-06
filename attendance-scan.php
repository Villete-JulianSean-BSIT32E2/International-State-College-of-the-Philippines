<?php
//ATTENDANCE-SCAN.PHP
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "iscpdb";

$message = '';
$messageType = '';
$studentInfo = null;
$action = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['studentId'])) {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $studentId = isset($_POST['studentId']) ? intval($_POST['studentId']) : 0;
        if ($studentId <= 0) {
            $message = "Invalid Student ID";
            $messageType = 'error';
        } else {
            //CHECKS IF STUDENT EXISTS
            $checkStudentSQL = "SELECT id FROM admission WHERE id = ?";
            $stmt = $conn->prepare($checkStudentSQL);
            $stmt->bind_param("i", $studentId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $message = "Student ID not found!";
                $messageType = 'error';
            } else {
                $currDate = date('Y-m-d');
                $currTime = date('Y-m-d H:i:s');

                //CHECKS IF ALREADY TOOK ATTENDANCE
                $checkSQL = "SELECT AttendanceID, Status FROM attendance WHERE id = ? AND Date = ?";
                $stmt = $conn->prepare($checkSQL);
                $stmt->bind_param("is", $studentId, $currDate);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    //IF STUDENT EXISTS, TAKE ATTENDANCE
                    $row = $result->fetch_assoc();
                    $attendanceId = $row['AttendanceID'];
                    $status = 1; // Present

                    //IF PROF MARKED STUDENT AS ABSENT, TURN TO PRESENT
                    //TODO: ADD LATE STATUS
                    if ($row['Status'] != 1) {
                        $updateSQL = "UPDATE attendance SET Status = ?, TimeIn = ? WHERE AttendanceID = ?";
                        $stmt = $conn->prepare($updateSQL);
                        $stmt->bind_param("isi", $status, $currTime, $attendanceId);

                        if ($stmt->execute()) {
                            $action = "update";
                            $message = "Attendance updated successfully!";
                            $messageType = 'update';
                        } else {
                            $message = "Error updating attendance: " . $stmt->error;
                            $messageType = 'error';
                        }
                    } else {
                        $message = "Student already marked present for today.";
                        $messageType = 'info';
                    }
                } else {
                    $status = 1;
                    $insertSQL = "INSERT INTO attendance (id, Date, Status, TimeIn) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($insertSQL);
                    $stmt->bind_param("isis", $studentId, $currDate, $status, $currTime);

                    if ($stmt->execute()) {
                        $action = "insert";
                        $message = "Attendance recorded successfully!";
                        $messageType = 'success';
                    } else {
                        $message = "Error recording attendance: " . $stmt->error;
                        $messageType = 'error';
                    }
                }

                if ($messageType == 'success' || $messageType == 'update' || $messageType == 'info') {
                    $infoSQL = "SELECT a.id as studentId, a.AttendanceID, a.Date as date, sav.StudentName,
                                CASE
                                    WHEN a.Status = 0 THEN 'Absent'
                                    WHEN a.Status = 1 THEN 'Present'
                                    WHEN a.Status = 2 THEN 'Late'
                                    ELSE 'Unknown'
                                END as status,
                                a.TimeIn as timeIn
                                FROM attendance a
                                INNER JOIN student_attendance_view sav ON a.AttendanceID = sav.AttendanceID
                                WHERE a.id = ? AND a.Date = ?";
                    
                    $stmt = $conn->prepare($infoSQL);
                    $stmt->bind_param("is", $studentId, $currDate);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $studentInfo = $result->fetch_assoc();
                    }
                }
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>International State College of the Philippines Attendance</title>
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
    body {
        font-family: 'Poppins', sans-serif;
    }
    .info-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }
    .info-card:hover {
        transform: translateY(-5px);
    }
    .card-header {
        background-color: #13334D;
        color: white;
        padding: 20px;
        font-weight: 600;
    }
    .student-info {
        padding: 25px;
    }
    .info-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 4px;
        font-weight: 500;
    }
    .info-value {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
    }
    .status-badge {
        padding: 8px 15px;
        border-radius: 50px;
        display: inline-block;
        font-weight: 600;
        margin-bottom: 15px;
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
    .time-info {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        margin-top: 10px;
    }
    .time-icon {
        font-size: 1.5rem;
        margin-right: 10px;
        color: #13334D;
    }
    .placeholder-message {
        padding: 40px 20px;
        text-align: center;
        color: #6c757d;
    }
    .placeholder-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #dee2e6;
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
                        <div class="col-6 col-md-5 mb-2">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-header">
                                    <h2 class="card-title text-center mb-0">Manual Entry</h2>
                                </div>
                                <div class="card-body text-center">
                                    <div class="manual-input">
                                        <form id="attendanceForm" method="post" action="">
                                            <input type="text" name="studentId" class="form-control mb-3" placeholder="Enter Student ID" aria-label="Student ID">
                                            <button class="btn btn-primary form-control" type="submit">Submit</button>
                                        </form>

                                        <?php if (!empty($message)): ?>
                                            <div class="alert alert-<?php
                                                echo ($messageType == 'success') ? 'success' :
                                                     (($messageType == 'update') ? 'info' : 
                                                     (($messageType == 'info') ? 'warning' : 'danger'));
                                            ?> mt-3" role="alert">
                                                <?php echo $message; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <div class="col-12 col-md-7 mb-6">
                                    <div class="card shadow-sm border-0 rounded-3">
                                        <div class="card-header text-center">
                                            <h2 class="mb-0">Student Information</h2>
                                        </div>
                                        
                                        <?php if($studentInfo) :?>
                                        <div class="card-body student-info">
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <div class="info-label">STUDENT ID</div>
                                                    <div class="info-value"><?php echo $studentInfo['studentId']; ?></div>
                                                </div>
                                                <div class="col-md-6 text-md-end">
                                                    <div class="info-label">ATTENDANCE DATE</div>
                                                    <div class="info-value"><?php echo date('F d, Y', strtotime($studentInfo['date'])); ?></div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <div class="info-label">STUDENT NAME</div>
                                                <div class="info-value"><?php echo $studentInfo['StudentName']; ?></div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <div class="info-label">STATUS</div>
                                                <div>
                                                    <span class="status-badge <?php 
                                                        echo ($studentInfo['status'] == 'Present') ? 'status-present' : 
                                                            (($studentInfo['status'] == 'Late') ? 'status-late' : 'status-absent'); 
                                                    ?>">
                                                        <?php echo $studentInfo['status']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="time-info d-flex align-items-center">
                                                <span class="time-icon">⏱️</span>
                                                <div>
                                                    <div class="info-label">TIME RECORDED</div>
                                                    <div class="info-value mb-0"><?php echo date('h:i:s A', strtotime($studentInfo['timeIn'])); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php else :?>
                                        <div class="card-body placeholder-message">
                                            <div class="placeholder-icon">🔍</div>
                                            <h4>No Information Available</h4>
                                            <p class="mb-0">Please scan your ID card or enter your student ID to view your attendance information.</p>
                                        </div>
                                        <?php endif; ?>
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

            const alerts = document.querySelectorAll('.alert');
            if (alerts.length > 0) {
                setTimeout(function() {
                    alerts.forEach(function(alert) {
                        alert.style.display = 'none';
                    });
                }, 3000);
            }
        });
    </script>
</body>
</html>