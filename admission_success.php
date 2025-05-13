<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admission Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .success-message {
            color: green;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .return-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #0b2a5b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .return-link:hover {
            background-color: #1a3d7c;
        }
    </style>
</head>
<body>
    <div class="success-message">
        Your admission form has been submitted successfully!
    </div>
    <p>Thank you for your application. We will contact you soon.</p>
    
    <a href="admission.php" class="return-link">Return to Admission Portal</a>
</body>
</html>