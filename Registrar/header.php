<style>
    
    .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px; /* Adjust width as needed */
    height: 100%;
    background-color: #2b81bb;
    color: white;
    padding-top: 20px;
}

.sidebar .nav-item {
    margin-bottom: 15px;
}

.sidebar .nav-link {
    color: white;
    font-size: 16px;
    text-decoration: none;
}

.sidebar .nav-link:hover {
    background-color: #495057;
}
</style>

<div class="sidebar p-3">
    <div class="text-center mb-4">
        <img src="assets/icons/logo.jpg" alt="Logo" width="80">
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="registrar.php?page=dashboard" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/home.png" width="20"> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="registrar.php?page=student_records" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/folder.png" width="20"> Student Records
            </a>
        </li>
        <li class="nav-item">
            <a href="registrar.php?page=enrollment_management" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/form.png" width="20"> Enrollment Management
            </a>
        </li>
        <li class="nav-item">
            <a href="registrar.php?page=grades_transcripts" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/document.png" width="20"> Grades & Transcripts
            </a>
        </li>
        <li class="nav-item">
            <a href="registrar.php?page=class_schedules" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/calendar.png" width="20"> Class Schedules
            </a>
        </li>
        <li class="nav-item">
            <a href="registrar.php?page=clearance" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/check.png" width="20"> Clearance
            </a>
        </li>
        <li class="nav-item">
            <a href="registrar.php?page=reports" class="nav-link text-white d-flex align-items-center gap-2">
                <img src="assets/icons/report.png" width="20"> Reports
            </a>
        </li>
    </ul>
</div>
