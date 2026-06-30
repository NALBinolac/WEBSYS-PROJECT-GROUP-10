<?php
session_start();

// Determine where the logout was triggered from
$context = $_GET['ctx'] ?? '';

session_unset();
session_destroy();

if ($context === 'admin') {
    header("Location: admin/admin-login.php");
} else {
    header("Location: index.php");
}
exit();
?>