<?php
session_start();
include 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $error = "This email is already registered.";
    } else {
        // Hash the password safely
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Fixed column targeting to use 'username'
        $insert_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt2 = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt2, "sss", $username, $email, $hashed_password);

        if (mysqli_stmt_execute($stmt2)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['user_name'] = $username;

            header("Location: learning.php");
            exit();
        } else {
            $error = "Registration failed. Please check your database connection.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration - Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<section style="min-height: 85vh; display: flex; align-items: center; background-color: var(--light-gray);">
    <div class="container" style="max-width: 500px;">
        <h2 class="section-title">Create Account</h2>
        <p class="section-subtitle text-center">Register to track your modules progression.</p>

        <?php if (!empty($error)): ?>
            <div style="background-color: #ffcdd2; color: #b71c1c; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-weight: 600; text-align: center;">
                ⚠️ <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <label style="font-weight: 600; display: block; margin-bottom: 5px;">Username / Full Name</label>
            <input type="text" name="username" placeholder="Juan Dela Cruz" required>

            <label style="font-weight: 600; display: block; margin-bottom: 5px;">Email Address</label>
            <input type="email" name="email" placeholder="juan@university.edu.ph" required>

            <label style="font-weight: 600; display: block; margin-bottom: 5px;">Password</label>
            <input type="password" name="password" placeholder="Create password" required>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px; border-radius: 10px;">
                Register & Open Portal
            </button>
        </form>

        <p class="text-center" style="margin-top: 20px;">
            Already have an account? <a href="login.php" class="edit-btn">Log in here</a>
        </p>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>