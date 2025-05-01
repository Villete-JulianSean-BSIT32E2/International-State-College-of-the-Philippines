<!DOCTYPE html>
<html>
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
                            <h1 class="fw-bold mb-0" style="font-size: 30px;">Maharlika College</h1>
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
                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header">
                            <h2 style="text-align: center; font-size: 20px; margin: 0;">Student Profile</h2>
                        </div>
                        <div>
                            <div>
                                <table>
                                    <tbody">
                                        <tr>
                                            <td style="padding: 20px; font-size: 18px;">Student No:</td>
                                            <td style="padding: 20px; font-size: 18px;">2718-21</td>
                                        </tr>
                                        <tr>
                                        	<td style="padding: 20px; font-size: 18px;">Name:</td>
                                            <td style="padding: 20px; font-size: 18px;">Ferdinand II Dangaran</td>
                                        </tr>
                                        <tr>
                                        	<td style="padding: 20px; font-size: 18px;">Course & Section:</td>
                                            <td style="padding: 20px; font-size: 18px;">BSIT-42A2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="manual-input">
                                <button class="btn form-control" type="button" style="margin-top: 50px;">Generate Report</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header">
                            <h2 style="text-align: center; font-size: 20px; margin: 0;">Attendance Record</h2>
                        </div>
                        <div class="card-body">
                            <div class="overview-table">
                                <table class="table text-center" style="font-size: 18px;">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Time In</th>
                                            <th>Time Out</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>April 30, 2025</td>
                                            <td>9:30:25 AM</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>April 29, 2025</td>
                                            <td>9:30:13 AM</td>
                                            <td>12:30:23 PM</td>
                                        </tr>
                                    </tbody>
                                </table>
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