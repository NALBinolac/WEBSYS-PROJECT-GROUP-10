<?php
session_start();
require_once '../config.php'; 

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch all users using MySQLi
$query = "SELECT id, username, fullname, email, role, created_at FROM users ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Management | Admin Panel</title>
    <style>
        /* Basic Layout to match your screenshot */
        body { display: flex; min-height: 100vh; margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .sidebar a.active { background-color: #1b5e20; border-left: 5px solid #fff; font-weight: bold; }
        .main-content { flex: 1; padding: 40px; }
        
        /* Table Styles */
        table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f1f1f1; color: #333; }
        .btn { padding: 6px 12px; background: #2e7d32; color: white; text-decoration: none; border-radius: 4px; font-size: 0.9rem; }
        .btn-danger { background: #d32f2f; }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">

    <h1>User Management</h1>
    <p>Manage all registered accounts and their roles.</p>

    <div style="margin-bottom: 20px; padding: 15px; background-color: #e8f5e9; border-radius: 5px; border-left: 4px solid #2e7d32;">
    <a href="../api/export-data.php?type=users" style="display: inline-block; background-color: #2e7d32; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; font-weight: bold; cursor: pointer;">
        Export Users as XML
    </a>
    <span style="color: #666; font-size: 0.9rem; margin-left: 15px;">Download all users in XML format</span>
</div>

<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
         <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($user = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['fullname'] ?: 'Not Provided') ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td style="text-transform: capitalize;"><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <a href="admin-edit-user.php?id=<?= $user['id'] ?>" class="btn">Edit</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align: center; color: #888; padding: 30px;">No registered users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>