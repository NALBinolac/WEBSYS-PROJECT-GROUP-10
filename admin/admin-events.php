<?php
session_start();
require_once '../config.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$events = [];
$table_error = false;

// Changed DESC to ASC so events display from nearest date to farthest date!
try {
    $query = "SELECT * FROM events ORDER BY date ASC";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
    }
} catch (mysqli_sql_exception $e) {
    $table_error = true; 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Events Management | Admin Panel</title>
    <style>
        body { display: flex; min-height: 100vh; margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .sidebar a.active { background-color: #1b5e20; border-left: 5px solid #fff; font-weight: bold; }
        .main-content { flex: 1; padding: 40px; }
        
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f1f1f1; color: #333; }
        .btn { padding: 6px 12px; background: #2e7d32; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
        .alert-warning { background-color: #fff3cd; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px; border: 1px solid #ffeeba; }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Events Management</h1>
            <a href="admin-add-event.php" class="btn">+ Add New Event</a>
        </div>

        <div style="margin-bottom: 20px; padding: 15px; background-color: #e8f5e9; border-radius: 5px; border-left: 4px solid #2e7d32;">
            <a href="../api/export-data.php?type=events" style="display: inline-block; background-color: #2e7d32; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold;">
                📥 Export Events as XML
            </a>
            <span style="color: #666; font-size: 0.9rem; margin-left: 15px;">Download all events in XML format</span>
        </div>

        <?php if ($table_error): ?>
            <div class="alert-warning">
                ⚠️ <strong>Database Table Missing:</strong> The 'events' table was not found in your database. Please run the SQL setup script in phpMyAdmin.
            </div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($events)): ?>
                    <tr><td colspan="4" style="text-align: center; color: #666;">No upcoming events found.</td></tr>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                    <tr>
                        <td><?= htmlspecialchars($event['title']) ?></td>
                        <td><?= htmlspecialchars($event['date']) ?></td>
                        <td><?= htmlspecialchars($event['venue']) ?></td>
                        <td>
                            <a href="admin-edit-event.php?id=<?= $event['id'] ?>" class="btn">Edit</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>