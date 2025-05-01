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
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header">
                            <h2 class="card-title text-center mb-0">Manual Entry</h2>
                        </div>
                        <div class="card-body text-center">
                            <div class="manual-input">
                                <input type="text" class="form-control mb-3" placeholder="Enter Student ID" aria-label="Student ID">
                                <button class="btn form-control" type="button">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header">
                            <h2 class="card-title text-center mb-0">Your Information</h2>
                        </div>
                        <div class="card-body text-center">
                            <p class="card-text">Please scan your card or enter your student ID manually.</p>
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