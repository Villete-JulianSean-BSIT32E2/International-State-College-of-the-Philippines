<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admission Error</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .error-message {
            color: red;
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-message">
        An error occurred during submission.
    </div>
    <p><?php echo isset($_SESSION['error']) ? $_SESSION['error'] : 'Unknown error'; ?></p>
    <p>Please try again or contact support.</p>
</body>
</html>