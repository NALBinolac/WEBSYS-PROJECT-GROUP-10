<?php
session_start();
require_once '../config.php';

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch aggregate statistics with safe error containment
$user_count = 0;
$volunteer_count = 0;
$event_count = 0;
$module_count = 0;

try {
    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
    if($res) $user_count = mysqli_fetch_assoc($res)['total'];

    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM volunteers");
    if($res) $volunteer_count = mysqli_fetch_assoc($res)['total'];

    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM events");
    if($res) $event_count = mysqli_fetch_assoc($res)['total'];

    $res = mysqli_query($conn, "SELECT COUNT(*) as total FROM modules");
    if($res) $module_count = mysqli_fetch_assoc($res)['total'];
} catch (mysqli_sql_exception $e) {
    // Suppress missing tables until created in phpMyAdmin
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #1b5e20; color: white; font-weight: bold; }
        .sidebar a.active { border-left: 5px solid #fff; }
        
        .main-content { flex: 1; padding: 40px; }
        .main-content h1 { font-size: 2.2rem; color: #111; margin-bottom: 5px; font-weight: 700; }
        .main-content p { color: #666; margin-bottom: 30px; }
        
        /* Grid Layout for Analytics Cards */
        .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .card { background: white; padding: 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid #eef0f2; }
        .card h3 { font-size: 0.9rem; color: #888; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; }
        .card .value { font-size: 2rem; font-weight: 700; color: #2e7d32; }
        
        .welcome-box { background: linear-gradient(135deg, #2e7d32, #1b5e20); color: white; padding: 30px; border-radius: 8px; margin-bottom: 30px; }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <div class="welcome-box">
            <h2>Welcome Back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>!</h2>
            <p style="color: rgba(255,255,255,0.8); margin-bottom: 0; margin-top: 5px;">Use this control console to update landing modules, track metrics, and manage user sessions.</p>
        </div>

        <h1>Platform Overview</h1>
        <p>Real-time analytics status across the organization workspace.</p>

        <div class="metrics-grid">
            <div class="card">
                <h3>Total Accounts</h3>
                <div class="value"><?= $user_count ?></div>
            </div>
            <div class="card">
                <h3>Volunteers</h3>
                <div class="value"><?= $volunteer_count ?></div>
            </div>
            <div class="card">
                <h3>Scheduled Events</h3>
                <div class="value"><?= $event_count ?></div>
            </div>
            <div class="card">
                <h3>Course Modules</h3>
                <div class="value"><?= $module_count ?></div>
            </div>
        </div>
    </div>

</body>
</html>