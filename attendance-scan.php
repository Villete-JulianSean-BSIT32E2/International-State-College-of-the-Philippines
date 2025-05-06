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

    date_default_timezone_set('Asia/Manila');

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
                    $checkSQL = "SELECT AttendanceID, Status, TimeIn, TimeOut FROM attendance WHERE id = ? AND Date = ?";
                    $stmt = $conn->prepare($checkSQL);
                    $stmt->bind_param("is", $studentId, $currDate);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        //IF STUDENT EXISTS, CHECK FOR ATTENDANCE
                        $row = $result->fetch_assoc();
                        $attendanceId = $row['AttendanceID'];

                        if (!empty($row['TimeIn']) && empty($row['TimeOut'])) {
                            $status = 2; // LOGGED OUT
                            $updateSQL = "UPDATE attendance SET Status = ?, TimeOut = ? WHERE AttendanceID = ?";
                            $stmt = $conn->prepare($updateSQL);
                            $stmt->bind_param("isi", $status, $currTime, $attendanceId);

                            if ($stmt->execute()) {
                                $action = "update";
                                $message = "Sign out recorded successfully!";
                                $messageType = 'update';
                            } else {
                                $message = "Error updating attendance: " . $stmt->error;
                                $messageType = 'error';
                            }
                        } elseif (!empty($row['TimeOut'])) {
                            $message = "Student has already completed attendance for today.";
                            $messageType = 'info';
                        } else {
                            $status = 1; // Present
                            $updateSQL = "UPDATE attendance SET Status = ?, TimeIn = ? WHERE AttendanceID = ?";
                            $stmt = $conn->prepare($updateSQL);
                            $stmt->bind_param("isi", $status, $currTime, $attendanceId);

                            if ($stmt->execute()) {
                                $action = "update";
                                $message = "Sign in recorded successfully!";
                                $messageType = 'success';
                            } else {
                                $message = "Error updating attendance: " . $stmt->error;
                                $messageType = 'error';
                            }
                        }
                    } else {
                        $status = 1; // Present
                        $insertSQL = "INSERT INTO attendance(id, Date, Status, TimeIn) VALUES (?,?,?,?)";
                        $stmt = $conn->prepare($insertSQL);
                        $stmt->bind_param("isis", $studentId, $currDate, $status, $currTime);

                        if ($stmt->execute()) {
                            $action = "insert";
                            $message = "Sign in recorded successfully!";
                            $messageType = 'success';
                        } else {
                            $message = "Error recording attendance: " . $stmt->error;
                            $messageType = 'error';
                        }
                    }

                    if ($messageType == 'success' || $messageType == 'update' || $messageType == 'info') {
                        $infoSQL = "SELECT a.id as studentId, a.AttendanceID, a.Date as date, sav.StudentName,
                                    CASE
                                        WHEN a.Status = 0 THEN  'Absent'
                                        WHEN a.Status = 1 THEN 'Present'
                                        WHEN a.Status = 2 THEN 'Logged Out'
                                        ELSE 'Late'
                                    END as status,
                                    a.TimeIn as timeIn,
                                    a.TimeOut as timeOut
                                    FROM attendance a
                                    INNER JOIN student_attendance_view sav ON a.id = sav.StudentID
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
         <!-- QuaggaJS for barcode scanning -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
        <!-- CSS -->
        <link rel="stylesheet" href="attendance-css.css">
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
                        <div class="col-12 col-md-5 mb-4">
                            <ul class="nav nav-tabs" id="entryTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="manual-tab" data-bs-toggle="tab" data-bs-target="#manual" type="button" role="tab" aria-controls="manual" aria-selected="true">Manual Entry</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="webcam-tab" data-bs-toggle="tab" data-bs-target="#webcam" type="button" role="tab" aria-controls="webcam" aria-selected="false">Webcam Scanner</button>
                                </li>
                            </ul>
                            
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="manual" role="tabpanel" aria-labelledby="manual-tab">
                                    <div class="card shadow-sm border-0 rounded-3 rounded-top-0">
                                        <div class="card-header">
                                            <h2 class="card-title text-center mb-0">Manual Entry / RFID Scanner</h2>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="manual-input">
                                                <form id="attendanceForm" method="post" action="">
                                                    <div class="input-group mb-3">
                                                        <input type="text" name="studentId" id="studentIdInput" class="form-control" placeholder="Enter Student ID or Scan RFID Card" aria-label="Student ID" autofocus>
                                                        <button class="btn btn-primary" type="submit">Submit</button>
                                                    </div>
                                                    <div class="form-text text-start mb-3">
                                                        <small>* For RFID/Barcode scanners, just scan the card - form will submit automatically</small>
                                                    </div>
                                                </form>

                                                <div id="message-container">
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
                                </div>
                                
                                <div class="tab-pane fade" id="webcam" role="tabpanel" aria-labelledby="webcam-tab">
                                    <div class="card shadow-sm border-0 rounded-3 rounded-top-0">
                                        <div class="card-header">
                                            <h2 class="card-title text-center mb-0">Webcam Scanner</h2>
                                        </div>
                                        <div class="card-body text-center">
                                            <div class="mb-3">Use your device's camera to scan QR/barcodes</div>
                                            
                                            <div id="scanner-container" class="d-none">
                                                <div class="scanner-overlay"></div>
                                                <div class="scanner-laser"></div>
                                            </div>
                                            
                                            <button id="start-scanner" class="btn btn-success mb-3">
                                                <i class="bi bi-camera"></i> Start Camera
                                            </button>
                                            <button id="stop-scanner" class="btn btn-danger mb-3 d-none">
                                                <i class="bi bi-camera-video-off"></i> Stop Camera
                                            </button>
                                            
                                            <div id="scan-result" class="mt-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-7 mb-6">
                            <div class="card shadow-sm border-0 rounded-3">
                                <div class="card-header text-center">
                                    <h2 class="mb-0">Student Information</h2>
                                </div>
                                
                                <div id="StudentInfoCard">
                                <?php if($studentInfo) :?>
                                <div class="card-body student-info" id="StudentInfo">
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
                                                    (($studentInfo['status'] == 'Late') ? 'status-late' : 
                                                    (($studentInfo['status'] == 'Logged Out') ? 'status-loggedout' : 'status-absent')); 
                                            ?>">
                                                <?php echo $studentInfo['status']; ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="time-info d-flex align-items-center">
                                        <span class="time-icon">⏱️</span>
                                        <div>
                                            <div class="info-label">TIME IN</div>
                                            <div class="info-value"><?php echo date('h:i:s A', strtotime($studentInfo['timeIn'])); ?></div>
                                            
                                            <?php if(!empty($studentInfo['timeOut'])) : ?>
                                            <div class="info-label mt-2">TIME OUT</div>
                                            <div class="info-value mb-0"><?php echo date('h:i:s A', strtotime($studentInfo['timeOut'])); ?></div>
                                            <?php endif; ?>
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

    const studentInfo = document.getElementById('StudentInfoContent');
        if (studentInfo) {
            setTimeout(function() {
            studentInfo.classList.add('fade-out');

            setTimeout(function() {
                const studentInfoCard = document.getElementById('StudentInfoCard');
                studentInfoCard.innerHTML = `
                    <div class="card-body placeholder-message" id="placeholderContent">
                        <div class="placeholder-icon">🔍</div>
                        <h4>No Information Available</h4>
                        <p class="mb-0">Please scan your ID card or enter your student ID to view your attendance information.</p>
                    </div>
                    `;
                }, 500);
            }, 3000);
        }

    //RFID SCANNING
    const studentIdInput = document.getElementById('studentIdInput');
    let lastScanTime = 0;
    let inputBuffer = '';
    const SCAN_THRESHOLD = 100;

    studentIdInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('attendanceForm').submit();
            return;
        }

        const currTime = new Date().getTime();

        //IF MULTIPLE SCANS
        if (currTime - lastScanTime < SCAN_THRESHOLD) {
            inputBuffer += e.key;
        } else {
            inputBuffer = e.key;
        }

        lastScanTime = currTime;
    });

    //WEBCAM SCANNER -- FOR TESTING PURPOSES IF RFID WORKS WITHOUT TYPING
    //SINCE RFID HAS SAME LOGIC AS CAMERA
    //TODO: REMOVE AFTER INTEGRATION
    const startScannerBtn = document.getElementById('start-scanner');
    const stopScannerBtn = document.getElementById('stop-scanner');
    const scannerContainer = document.getElementById('scanner-container');
    const scanResult = document.getElementById('scan-result');

    let quaggaInitialized = false;

    startScannerBtn.addEventListener('click', function() {
        startScanner();
        this.classList.add('d-none');
        stopScannerBtn.classList.remove('d-none');
        scannerContainer.classList.remove('d-none');
    });

    stopScannerBtn.addEventListener('click', function() {
        stopScanner();
        this.classList.add('d-none');
        startScannerBtn.classList.remove('d-none');
        scannerContainer.classList.remove('d-none');
    });

    function startScanner() {
        if (quaggaInitialized) {
            Quagga.start();
            return;
        }

        Quagga.init({
            inputStream: {
                name: "Live",
                type: "LiveStream",
                target: scannerContainer,
                constraints: {
                    facingMode: "environment"
                }
            },
            decoder: {
                readers: [
                    "code_128_reader",
                    "qrcode",
                    "ean_reader",
                    "ean_8_reader",
                    "code_39_reader",
                    "code_39_vin_reader",
                    "codabar_reader",
                    "upc_reader",
                    "upc_e_reader",
                    "i2of5_reader",
                    "2of5_reader",
                    "code_93_reader"                    
                ]
            }
        }, function(err) {
            if (err) {
                console.error("Failed to initialize scanner: ", err);
                scanResult.innerHTML = "Camera access error: " + err.message;
                scanResult.style.color = "#dc3545";
                return;
            }

            quaggaInitialized = true;

            Quagga.start();

            scanResult.innerHTML = "Scanner is ready! Point camera at a barcode.";
            scanResult.style.color = "#28a745";
        });

        Quagga.onDetected(function(result) {
                const code = result.codeResult.code;
                
                const beep = new Audio("data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAAABMSVNUHAAAAElORk9JQ09QHwAAAENvcHlyaWdodCA6IGJlZXAgc291bmQAZGF0YU4GAACA/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gP+A/4D/gv+D/4X/h/+J/4z/jv+R/5X/mP+b/57/ov+n/6r/rf+x/7X/uf+8/8H/xf/I/8z/0P/T/9b/2f/c/97/4f/j/+X/5//o/+n/6//r/+v/7P/r/+r/6f/o/+b/5P/i/9//3P/Y/9X/0f/N/8j/w/++/7n/s/+u/6n/ov+d/5f/kv+M/4b/gP96/3X/cP9q/2X/YP9c/1j/VP9Q/03/S/9J/0j/R/9G/0X/Rf9F/0b/R/9I/0n/S/9N/0//Uv9V/1j/W/9f/2P/Z/9s/3D/df96/3//hP+J/4//lP+a/5//pf+r/7D/tv+8/8H/x//M/9H/1//c/+H/5v/q/+7/8v/1//j/+//9////////////////////////////+//5//f/9P/x/+3/6v/m/+L/3f/Y/9L/zf/I/8L/vP+2/7H/q/+l/5//mf+U/47/iP+D/37/eP9z/27/av9m/2L/X/9b/1j/Vv9T/1H/T/9O/03/TP9L/0r/Sv9K/0r/Sv9L/0z/Tf9P/1D/Uv9U/1f/Wf9c/1//Yf9l/2j/bP9v/3P/d/97/3//g/+I/4z/kP+V/5r/nv+j/6j/rf+x/7b/u/+//8T/yP/N/9H/1f/Z/93/4f/k/+f/6v/t/+//8f/z//X/9v/3//j/+f/6//r/+v/6//n/+P/4//b/9f/z//H/7//t/+r/6P/l/+L/3//c/9j/1f/R/83/yv/G/8L/vv+6/7b/sv+u/6r/pv+i/57/mv+W/5L/jv+L/4f/g/+A/33/ef92/3P/cP9t/2r/aP9l/2P/Yf9f/13/W/9a/1j/V/9W/1X/VP9T/1P/U/9S/1L/Uv9R/1H/Uf9R/1L/Uv9T/1T/Vf9W/1f/WP9Z/1r/XP9d/17/YP9i/2P/Zf9n/2n/a/9t/2//cf9z/3X/d/95/3z/fv+A/4L/hP+H/4n/i/+N/5D/kv+U/5b/mP+b/53/n/+h/6P/pf+o/6r/rP+u/7D/sv+0/7b/uP+5/7v/vf+//8D/wv/E/8X/x//I/8n/y//M/83/zv/P/9D/0v/T/9T/1P/V/9b/1//X/9j/2P/Z/9n/2v/a/9v/2//b/9v/3P/c/9z/3P/c/9z/3P/c/9z/3P/c/9v/2//b/9r/2v/Z/9j/2P/X/9b/1f/U/9P/0v/R/9D/z//O/8z/y//K/8j/x//F/8T/wv/B/7//vv+8/7v/uf+4/7b/tf+z/7L/sP+v/63/rP+q/6n/p/+m/6T/o/+h/6D/nv+d/5v/mv+Y/5f/lv+U/5P/kf+Q/4//jf+M/4r/if+I/4f/hf+E/4P/gv+A/3//fv99/3z/e/96/3n/eP93/3b/df90/3P/cv9x/3D/cP9v/27/bf9s/2z/a/9q/2r/af9o/2j/Z/9n/2b/Zv9l/2X/ZP9k/2P/Y/9j/2L/Yv9i/2H/Yf9h/2D/YP9g/2D/YP9f/1//X/9f/1//X/9f/1//X/9f/1//X/9f/2D/YP9g/2D/YP9g/2H/Yf9h/2L/Yv9i/2P/Y/9j/2T/ZP9l/2X/Zv9m/2f/Z/9o/2j/af9p/2r/a/9r/2z/bP9t/27/b/9v/3D/cf9x/3L/c/90/3X/df92/3f/eP95/3n/ev97/3z/ff9+/37/f/+A/4H/gv+D/4T/hf+F/4b/h/+I/4n/iv+L/4z/jf+O/47/j/+Q/5H/kv+T/5T/lf+W/5f/mP+Z/5r/m/+c/53/nv+f/6D/of+i/6P/pP+l/6b/p/+o/6n/qv+r/6z/rf+u/6//sP+x/7L/s/+0/7X/tv+3/7j/uf+6/7v/vP+9/77/v//A/8H/wv/D/8T/xf/G/8f/yP/J/8r/y//M/83/zv/P/9D/0f/S/9P/1P/V/9b/1//Y/9n/2v/b/9z/3f/e/9//4P/h/+L/4//k/+X/5v/n/+j/6f/q/+v/");
                beep.play();
                
                scanResult.innerHTML = "Scanned Code: " + code;
                
                studentIdInput.value = code;
                
                stopScanner();
                stopScannerBtn.classList.add('d-none');
                startScannerBtn.classList.remove('d-none');
                scannerContainer.classList.add('d-none');
                
                setTimeout(function() {
                    document.getElementById('attendanceForm').submit();
                }, 500);
            });
        }
        
        function stopScanner() {
            if (quaggaInitialized) {
                Quagga.stop();
            }
        }
        
        document.getElementById('webcam-tab').addEventListener('click', function() {
            document.getElementById('manual-tab').addEventListener('click', function() {
                setTimeout(function() {
                    studentIdInput.focus();
                }, 100);
            });
        });
        //END OF WEBCAM SCANNER

        studentIdInput.focus();      
    });
</script>
    </body>
    </html>