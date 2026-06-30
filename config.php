<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ngo_db";
$port = 3307;

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $dbname,
    $port
);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

function getSectionContent($section_name, $conn) {
    $query = "SELECT * FROM site_content WHERE section_name = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $section_name);
    mysqli_stmt_execute($stmt);
    return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
}

?>