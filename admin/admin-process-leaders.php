<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';

    // --- ADD LEADER ---
    if ($action === 'add') {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $bio = $_POST['bio'];
        $image_path = "";

        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

            $filename = time() . "_" . basename($_FILES['image']['name']);
            $destination = $target_dir . $filename;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                $image_path = "uploads/" . $filename; 
            } else {
                header("Location: admin-leaders.php?error=Image Upload Failed");
                exit();
            }
        }

        $query = "INSERT INTO leaders (name, role, bio, image_path) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $role, $bio, $image_path);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: admin-leaders.php?status=success");
        } else {
            header("Location: admin-leaders.php?error=Database Error");
        }
        exit();
    }
    
    // --- DELETE LEADER ---
    elseif ($action === 'delete') {
        $id = intval($_POST['leader_id']);
        $image_to_delete = "../" . $_POST['image_path'];

        $query = "DELETE FROM leaders WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            if (file_exists($image_to_delete) && !empty($_POST['image_path'])) {
                unlink($image_to_delete);
            }
            header("Location: admin-leaders.php?status=deleted");
        }
        exit();
    }
}
?>