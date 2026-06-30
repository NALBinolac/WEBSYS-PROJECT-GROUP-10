<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'] ?? '';

    // --- ADD NEW ARTICLE ---
    if ($action === 'add') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $date = mysqli_real_escape_string($conn, $_POST['article_date']);
        
        // FIX HERE: Grab the raw text instead of running it through real_escape_string
        $summary = $_POST['summary']; 
        
        $image_path = "";

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $target_dir = "../uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $filename = time() . "_" . basename($_FILES['cover_image']['name']);
            $destination = $target_dir . $filename;
            
            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $destination)) {
                $image_path = "uploads/" . $filename; 
            } else {
                header("Location: admin-news.php?error=Image Upload Failed");
                exit();
            }
        }

        $query = "INSERT INTO news_articles (title, summary, image_path, article_date) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssss", $title, $summary, $image_path, $date);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: admin-news.php?status=success");
        } else {
            header("Location: admin-news.php?error=Database Error");
        }
        exit();
    }
    
    // --- DELETE ARTICLE ---
    elseif ($action === 'delete') {
        $id = intval($_POST['article_id']);
        $image_to_delete = "../" . $_POST['image_path'];

        $query = "DELETE FROM news_articles WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            // Server space management: delete the actual image file
            if (file_exists($image_to_delete) && !empty($_POST['image_path'])) {
                unlink($image_to_delete);
            }
            header("Location: admin-news.php?status=deleted");
        } else {
            header("Location: admin-news.php?error=Could not delete article");
        }
        exit();
    }
} else {
    header("Location: admin-news.php");
    exit();
}
?>