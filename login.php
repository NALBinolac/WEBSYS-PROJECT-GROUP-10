<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query the users table
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username']; 
            $_SESSION['role'] = $user['role']; // Save the role to the session!

            // Route the user based on their role
            if ($user['role'] === 'admin') {
                header("Location: admin/admin-dashboard.php");
                exit();
            } else {
                // Regular users/students go here
                header("Location: learning.php");
                exit();
            }
            
        } else {
            $error = "Invalid password configuration.";
        }
    } else {
        $error = "User Not Found"; 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Login - Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<section style="min-height: 80vh; display: flex; align-items: center; background-color: var(--light-gray); padding: 40px 0;">
    <div class="container" style="max-width: 500px;">
        
        <form action="login.php" method="POST" style="background: white; border-radius: 20px; padding: 40px; box-shadow: var(--shadow);">
            <h2 style="color: var(--primary-green); font-size: 2rem; margin-bottom: 25px; font-weight: 700;">Login</h2>
            
            <?php if (!empty($error)): ?>
                <div style="background-color: #ffcdd2; color: #b71c1c; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; text-align: center; font-size: 0.9rem;">
                    ⚠️ <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div style="margin-bottom: 20px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px; color: var(--text-black);">Email Address</label>
                <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 14px; border: 1px solid var(--border); border-radius: 10px; margin-bottom: 0;">
            </div>

            <div style="margin-bottom: 25px;">
                <label style="font-weight: 600; display: block; margin-bottom: 8px; color: var(--text-black);">Password</label>
                <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 14px; border: 1px solid var(--border); border-radius: 10px; margin-bottom: 0; box-sizing: border-box;">
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; font-weight: 700; border-radius: 10px; border: none; background-color: var(--primary-green); color: white; cursor: pointer; font-size: 1rem;">
                Sign In
            </button>
        </form>

        <p class="text-center" style="margin-top: 20px; text-align: center;">
            Don't have an account yet? <a href="signup.php" style="color: var(--primary-green); font-weight: 600; text-decoration: none;">Register here</a>
        </p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>