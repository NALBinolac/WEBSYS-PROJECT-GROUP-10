<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['assignment_files'])) {
    
    $user_id = $_SESSION['user_id'];
    $module_number = intval($_POST['module_number']);

    $saved_file_names = [];
    $saved_file_paths = [];
    
    $total_files = count($_FILES['assignment_files']['name']);

    for ($i = 0; $i < $total_files; $i++) {
        if ($_FILES['assignment_files']['error'][$i] == 0) {
            $file_tmp = $_FILES['assignment_files']['tmp_name'][$i];
            $original_name = basename($_FILES['assignment_files']['name'][$i]);
            
            $clean_name = str_replace(" ", "_", $original_name);
            $target_filename = "user_" . $user_id . "_mod_" . $module_number . "_" . time() . "_" . $i . "_" . $clean_name;
            $destination = "submissions/" . $target_filename;

            if (move_uploaded_file($file_tmp, $destination)) {
                $saved_file_names[] = $original_name;
                $saved_file_paths[] = $destination;
            }
        }
    }

    if (!empty($saved_file_paths)) {
        $json_names = json_encode($saved_file_names);
        $json_paths = json_encode($saved_file_paths);

        $check_query = "SELECT id FROM submissions WHERE user_id = ? AND module_number = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "ii", $user_id, $module_number);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $query = "UPDATE submissions SET file_name = ?, file_path = ?, submitted_at = CURRENT_TIMESTAMP WHERE user_id = ? AND module_number = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssii", $json_names, $json_paths, $user_id, $module_number);
        } else {
            $query = "INSERT INTO submissions (user_id, module_number, file_name, file_path) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "iiss", $user_id, $module_number, $json_names, $json_paths);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: learning.php?upload=success");
            exit();
        } else {
            die("Database Error: Could not log your submission.");
        }
    } else {
        die("Upload Failed. No valid files were processed.");
    }
} else {
    die("No files detected.");
}
?>