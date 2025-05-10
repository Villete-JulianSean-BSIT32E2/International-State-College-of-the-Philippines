<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statement of Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .nav-item {
            margin: 10px 0;
            padding: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .nav-item:hover {
            background: #4267b2;
            cursor: pointer;
        }

        .active {
            background: #5990d4;
        }

        .main {
            flex: 1;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .logos-topbar img {
            height: 40px;
        }

        .search-container {
            position: relative;
        }

        .search-container input {
            padding: 8px 30px 8px 30px;
            border-radius: 20px;
            border: 1px solid #ccc;
            outline: none;
        }

        .search-container .search-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 16px;
            color: #888;
        }

        .logout-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
        }

        .statement-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .info-box {
            background-color: #f0f0f0;
            padding: 15px;
            width: 48%;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .statement-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .statement-table th, .statement-table td {
            border: 1px solid #999;
            padding: 8px;
            text-align: center;
        }

        .statement-table th {
            background-color: #003366;
            color: white;
        }

        .footer-total {
            background-color: #003366;
            color: white;
            text-align: right;
            padding: 10px 150px 10px 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div style="width: 200px; height: 127vh; background: #112D4E; float: left; color: white; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
        <div style="padding: 20px; text-align: center;">
            <img src="ISCPLogo.png" alt="Logo" style="width: 100px; height: auto; border-radius: 50%;">
        </div>
        <div style="padding: 10px;">
            <div class="nav-item">
                <i class="mdi mdi-monitor-dashboard"></i> <span>Dashboard</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-peso-sign"></i> <span>Tuition</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-file-invoice-dollar"></i> <span>Manage Invoice/Payments</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-file-invoice"></i> <span>Receivables</span>
            </div>
            <div class="nav-item active">
                <i class="fas fa-file-alt"></i> <span>Statement of Account</span>
            </div>
            <div class="nav-item">
                <i class="fas fa-list"></i> <span>Summary</span>
            </div>
        </div>
    </div>
    <div style="padding: 20px; border-top: 1px solid #ffffff30;">
        <div style="display: flex; align-items: center; justify-content: space-between; padding: 10px 0;">
            <span>Features</span>
            <span style="background: #FFD700; color: black; padding: 2px 5px; border-radius: 12px; font-size: 10px;">NEW</span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div style="margin-left: 200px; background: white; min-height: 92.5vh; flex-grow: 1; padding: 30px; overflow-y: auto;">
    <!-- Topbar with logos and search -->
    <div class="topbar">
        <div class="topbar-left">
            <div class="logos-topbar">
                <img src="Lyceum.png" alt="Logo 1">
                <img src="ISCPLogo.png" alt="Logo 2">
                <img src="CCS LOGO.png" alt="Logo 3">
            </div>
        </div>
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="search-container">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search...">
            </div>
            <div class="notification-bell">🔔</div>
            <button class="logout-btn">Log out</button>
        </div>
    </div>

    <div class="statement-title">Statement of Account</div>

    <div class="info-section">
        <div class="info-box">
            <strong>Bill To:</strong><br>
            [Name]<br>
            [Company Name]<br>
            [Street Address]<br>
            [City, ST ZIP Code]<br>
            [Phone]
        </div>
        <div class="info-box">
            <strong>Account Summary</strong><br>
            Date: April 15, 2025<br>
            Statement #: 1000<br>
            Customer ID: ABC12345<br>
            Page 1 of 1<br><br>
            Previous Balance: ₱<br>
            Credits: ₱<br>
            New Charges: ₱<br>
            Total Balance Due: ₱<br>
            Payment Due Date: _______
        </div>
    </div>

    <table class="statement-table">
        <thead>
        <tr>
            <th>Date</th>
            <th>Invoice #</th>
            <th>Description</th>
            <th>Charges</th>
            <th>Credits</th>
            <th>Line Total</th>
        </tr>
        </thead>
        <tbody>
        <tr><td colspan="6" style="height: 300px;"></td></tr>
        </tbody>
    </table>

    <div class="footer-total">Account Current Balance</div>
</div>

</body>
</html>
