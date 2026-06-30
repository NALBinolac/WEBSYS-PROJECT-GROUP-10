<?php

include 'config.php';

// Siguraduhing nanggaling sa POST request ang access
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $support_type = $_POST['support_type'];

    if ($support_type == "donation") {

        $donor_name = $_POST['donor_name'];
        $email = $_POST['email'];
        $amount = $_POST['amount'];
        $payment_channel = $_POST['payment_channel'];
        $purpose = $_POST['purpose'];

        // --- SIMULA NG FILE UPLOAD LOGIC ---
        $proof_path = ""; // Default value kung walang na-upload (o pwedeng gawing required)

        if (isset($_FILES['proof_of_payment']) && $_FILES['proof_of_payment']['error'] == 0) {
            
            $target_dir = "uploads/receipts/"; // Dito mase-save ang mga larawan
            
            // Awtomatikong gawin ang folder kung hindi pa ito umiiral sa iyong server
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0757, true);
            }

            $file_name = $_FILES['proof_of_payment']['name'];
            $file_size = $_FILES['proof_of_payment']['size'];
            $file_tmp  = $_FILES['proof_of_payment']['tmp_name'];
            
            // Kunin ang file extension (hal. jpg, png)
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = array("jpg", "jpeg", "png");

            // Siyasatin kung valid ang extension
            if (!in_array($file_ext, $allowed_extensions)) {
                die("Error: Paumanhin, JPG, JPEG, at PNG lamang ang tinatanggap na file format.");
            }

            // Siyasatin ang laki ng file (Limit: 5MB)
            if ($file_size > 5 * 1024 * 1024) {
                die("Error: Masyadong malaki ang file. Dapat ay hindi hihigit sa 5MB.");
            }

            // Gumawa ng natatanging pangalan (Unique Name) para hindi mag-overwrite ng kaparehong file name
            $new_file_name = "proof_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $file_ext;
            $target_file = $target_dir . $new_file_name;

            // I-transfer ang file mula temp storage papunta sa permanent folder
            if (move_uploaded_file($file_tmp, $target_file)) {
                $proof_path = $target_file; // Ito ang papasok sa database
            } else {
                die("Error: Nagkaroon ng problema sa pag-save ng imahe.");
            }
        } else {
            die("Error: Obligado ang pag-upload ng Proof of Payment screenshot.");
        }
        // --- WAKAS NG FILE UPLOAD LOGIC ---


        // LIGTAS NA SQL: Gamit ang Prepared Statements para iwas SQL Injection
        $sql = "INSERT INTO donations (donor_name, email, amount, payment_channel, purpose, proof_path) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        if ($stmt = mysqli_prepare($conn, $sql)) {
            // I-bind ang mga parameters ("ssdsss" nangangahulugang: string, string, double/decimal, string, string, string)
            mysqli_stmt_bind_param($stmt, "ssdsss", $donor_name, $email, $amount, $payment_channel, $purpose, $proof_path);
            
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                mysqli_close($conn);
                
                header("Location: support.php?success=donation");
                exit();
            } else {
                echo "Error sa pag-execute: " . mysqli_stmt_error($stmt);
            }
        } else {
            echo "Error sa pag-prepare: " . mysqli_error($conn);
        }
    }
}
?>