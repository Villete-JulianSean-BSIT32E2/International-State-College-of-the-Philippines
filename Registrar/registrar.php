<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrar ERP System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="d-flex">
        <?php include("header.php"); ?>

        <div class="main-content p-4">
            <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

$page_file = "pages/$page.php";


if (file_exists($page_file)) {
    include $page_file;
} else {
    echo "<h2>404 - Page '$page' not found.</h2>";
}



            ?>

            <footer class="text-center mt-5">
                <p class="text-muted">© 2025 International State College of the Philippines – Registrar’s Office</p>
            </footer>
        </div>
    </div>
</body>
</html>
