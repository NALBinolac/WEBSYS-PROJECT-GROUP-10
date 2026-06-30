<?php
$host = '127.0.0.1'; 
$port = '3306'; // Keeps it on your working XAMPP port
$dbname = 'ngo_db'; // 
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Connection successful!
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>