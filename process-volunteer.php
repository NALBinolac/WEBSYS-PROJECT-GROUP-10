<?php
include 'config.php';

$fullname     = $_POST['fullname'];
$email        = $_POST['email'];
$skills       = $_POST['skills'];
$interests    = $_POST['interests'];
$availability = $_POST['availability'];

// 1. Use placeholders (?) instead of raw variables
$sql = "INSERT INTO volunteers (fullname, email, skills, interests, availability) VALUES (?, ?, ?, ?, ?)";

// 2. Prepare the statement
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // 3. Bind the variables ("sssss" means 5 strings)
    mysqli_stmt_bind_param($stmt, "sssss", $fullname, $email, $skills, $interests, $availability);
    
    // 4. Execute it safely
    mysqli_stmt_execute($stmt);
    
    mysqli_stmt_close($stmt);
}

header("Location: volunteer.php?success=applied");
exit();
?>