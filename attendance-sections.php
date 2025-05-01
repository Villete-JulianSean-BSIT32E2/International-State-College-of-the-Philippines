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
                <div class="mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header d-flex justify-content-between align-items-center px-4">
                            <h2 class="card-title mb-0">List of Sections</h2>
                            <div class="d-flex">
                                <input type="text" id="search" name="search" class="form-control me-2">
                                <button type="button" class="btn" style="background-color:#fff; color: #13334D;">Search</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="overview-table">
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th scope="col">Section</th>
                                            <th scope="col">Number of Student</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>BSIT 41A2</td>
                                            <td>30</td>
                                            <td><button class="btn btn-primary" onclick="window.location.href='attendance-section-student.php';">View</button></td>
                                        </tr>
                                    </tbody>
                                </table>
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