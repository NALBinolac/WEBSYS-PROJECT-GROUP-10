<?php
session_start();
require_once '../config.php';

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['module_number'])) {
    $module_number = intval($_GET['module_number']);

    $find_query = "SELECT file_path FROM modules WHERE module_number = ?";
    $stmt = mysqli_prepare($conn, $find_query);
    mysqli_stmt_bind_param($stmt, "i", $module_number);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        $disk_path = "../" . $row['file_path'];
        if (file_exists($disk_path)) {
            unlink($disk_path);
        }
    }

    $delete_query = "DELETE FROM modules WHERE module_number = ?";
    $del_stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($del_stmt, "i", $module_number);
    
    if (mysqli_stmt_execute($del_stmt)) {
        header("Location: admin-modules.php?status=deleted");
        exit();
    } else {
        header("Location: admin-modules.php?error=Database could not wipe module entry.");
        exit();
    }
} else {
    header("Location: admin-modules.php");
    exit();
}
?>