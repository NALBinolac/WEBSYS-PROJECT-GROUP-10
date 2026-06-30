<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";
$success = "";

if (!isset($_GET['id'])) {
    header("Location: admin-users.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $update_query = "UPDATE users SET fullname = ?, email = ?, role = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssi", $fullname, $email, $role, $id);

    if (mysqli_stmt_execute($stmt)) {
        $success = "User account updated successfully!";
    } else {
        $error = "Failed to update user.";
    }
}

$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found in the database.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background-color: #f8f9fa; color: #333; }
        
        /* Sidebar */
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; font-weight: 600; letter-spacing: 0.5px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: all 0.2s ease-in-out; font-weight: 500; }
        .sidebar a:hover { background-color: #1b5e20; padding-left: 28px; }
        .sidebar a.active { background-color: #1b5e20; border-left: 4px solid #fff; color: white; }
        
        /* Main Layout */
        .main-content { flex: 1; padding: 50px; }
        
        /* Form Card Styling */
        .form-group { 
            background: white; 
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 8px 16px rgba(0,0,0,0.04); 
            max-width: 650px; 
            border: 1px solid #eaeaea; 
            margin-top: 10px;
        }
        
        /* Form Inputs */
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #495057; font-size: 0.95rem; }
        input, select { 
            width: 100%; 
            padding: 14px 16px; 
            border: 1px solid #ced4da; 
            border-radius: 8px; 
            font-size: 1rem; 
            margin-bottom: 24px; 
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            color: #495057;
            background-color: #fff;
        }
        
        /* Input Focus Glow */
        input:focus, select:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }
        
        /* Disabled Input Style */
        input:disabled {
            background-color: #e9ecef;
            opacity: 1;
            color: #6c757d;
        }
        
        /* Buttons */
        .btn-container { display: flex; gap: 12px; margin-top: 10px; }
        
        .btn { 
            padding: 12px 24px; 
            border: none; 
            border-radius: 6px; 
            font-weight: 600; 
            cursor: pointer; 
            text-decoration: none; 
            font-size: 1rem; 
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-save { background-color: #2e7d32; color: white; box-shadow: 0 4px 6px rgba(46, 125, 50, 0.2); }
        .btn-save:hover { background-color: #1b5e20; transform: translateY(-1px); box-shadow: 0 6px 8px rgba(46, 125, 50, 0.3); }
        
        .btn-back { background-color: #f8f9fa; color: #495057; border: 1px solid #ced4da; }
        .btn-back:hover { background-color: #e2e6ea; border-color: #dae0e5; }
        
        /* Alerts */
        .alert { padding: 16px 20px; border-radius: 8px; margin-bottom: 25px; max-width: 650px; font-weight: 500; font-size: 0.95rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1 style="font-size: 2.2rem; color: #111; margin-bottom: 5px;">Edit User Record</h1>
        <p style="color: #666; margin-bottom: 30px;">Update user details or change workspace access roles.</p>

        <?php if (!empty($success)): ?>
            <div class="alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="form-group">
            <form action="admin-edit-user.php?id=<?= $id ?>" method="POST">
                
                <label>Username (Cannot be changed)</label>
                <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled style="background-color: #e9ecef; cursor: not-allowed;">

                <label>Full Name</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>" required>

                <label>Email Address</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

                <label>Account Role</label>
                <select name="role" required>
                    <option value="user" <?= ($user['role'] === 'user') ? 'selected' : '' ?>>Standard User</option>
                    <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Administrator</option>
                </select>

                <div class="btn-container">
                    <a href="admin-users.php" class="btn btn-back">← Back to Users</a>
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>