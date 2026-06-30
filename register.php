<?php
include 'config.php';

if(isset($_POST['register'])){

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];

    $password =
    password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    $sql =
    "INSERT INTO users
    (fullname,email,password)
    VALUES
    ('$fullname','$email','$password')";

    if(mysqli_query($conn,$sql)){

        header("Location: login.php");
        exit();

    }else{

        echo "Registration Failed";

    }
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Register</title>

<link rel="stylesheet" href="css/style.css">

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<section>

<div class="container">

<h2>Create Account</h2>

<form method="POST">

<input
type="text"
name="fullname"
placeholder="Full Name"
required>

<input
type="email"
name="email"
placeholder="Email"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button
type="submit"
name="register"
class="btn btn-primary">

Register

</button>

</form>

</div>

</section>

<?php include 'includes/footer.php'; ?>

</body>

</html>