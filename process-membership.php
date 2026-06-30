<?php

include 'config.php';

$fullname = $_POST['fullname'];
$email = $_POST['email'];
$interest = $_POST['interest'];
$motivation = $_POST['motivation'];

$sql = "INSERT INTO memberships
(fullname, email, interest, motivation)
VALUES
('$fullname', '$email', '$interest', '$motivation')";

if(mysqli_query($conn, $sql)){
    echo "Membership Application Submitted!";
}else{
    echo "Error: " . mysqli_error($conn);
}

?>