<?php
header('Content-Type: application/json');
require_once '../config.php'; // Gamitin ang existing connection file ng project

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $messageId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $newStatus = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

    $allowedStatuses = ['unread', 'read', 'replied'];
    
    if (!$messageId || !in_array($newStatus, $allowedStatuses)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid parameters provided.'
        ]);
        exit;
    }

    try {
        // I-update ang status gamit ang prepared statement para sa seguridad
        $sql = "UPDATE contact_messages SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $messageId);

        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Status successfully updated to ' . $newStatus
            ]);
        } else {
            throw new Exception("Failed to execute database update.");
        }

        $stmt->close();

    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid Request Method.'
    ]);
}

$conn->close();
?>