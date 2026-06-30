<?php
session_start();
include '../config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_input = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password_input, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            
            header("Location: admin-dashboard.php");
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Database Error: Walang nahanap na admin user.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Portal Login - Youth for Just Food Systems</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .login-section { padding: 80px 20px; display: flex; justify-content: center; background: #f4f4f4; min-height: 80vh; }
        .login-card { background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        input { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #2e7d32; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: 700; margin-top: 10px; }
        .error { color: #d32f2f; font-weight: 600; margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body>

<section class="login-section">
    <div class="login-card">
        <h2>🔒 Admin Portal</h2>
        <p style="margin-bottom: 20px; color: #555;">Authorized personnel only.</p>
        
        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" action="admin-login.php">
            <label>Email Address</label>
            <input type="email" name="email" required>
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <button type="submit">Sign In to Dashboard</button>
        </form>
    </div>
</section>

</body>
</html>