<?php
session_start();
require_once 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name    = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email   = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);

    if (!$name || !$email || !$subject || !$message) {
        header("Location: about.php?status=error&msg=Please fill up all fields.#contact");
        exit;
    }

    try {
        // Isinama na ang subject column dito
        $sql = "INSERT INTO contact_messages (name, email, subject, message, status, created_at) VALUES (?, ?, ?, ?, 'unread', NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $subject, $message);

        if ($stmt->execute()) {
            header("Location: about.php?status=success#contact");
        } else {
            throw new Exception("Failed to save message.");
        }
        $stmt->close();

    } catch (Exception $e) {
        header("Location: about.php?status=error&msg=Database error occurred.#contact");
    }

} else {
    header("Location: about.php");
}
$conn->close();
?>