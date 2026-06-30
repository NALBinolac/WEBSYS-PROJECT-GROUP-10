<?php
/**
 * Process Volunteer Status Updates with Email Notifications
 */

session_start();
require_once '../config.php';
require_once '../includes/email-helper.php';

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success_message = "Action completed.";
$email_sent = false;

if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    $admin_notes = isset($_GET['notes']) ? $_GET['notes'] : '';
    $rejection_reason = isset($_GET['reason']) ? $_GET['reason'] : '';

    // Fetch current volunteer information
    $query = "SELECT fullname, email, status FROM volunteers WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $volunteer = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$volunteer) {
        die("Volunteer not found.");
    }

    $previous_status = $volunteer['status'];
    $volunteer_email = $volunteer['email'];
    $volunteer_name = $volunteer['fullname'];

    if ($action === 'approve') {
        $new_status = 'Approved';
        $query = "UPDATE volunteers SET status = ?, status_updated_at = NOW(), status_notified = 0 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $new_status, $id);
        $update_success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($update_success) {
            $email_sent = sendVolunteerStatusEmail($volunteer_email, $volunteer_name, 'Approved', '', $admin_notes);
            logVolunteerStatusChange($conn, $id, $previous_status, $new_status, $email_sent, $admin_notes);

            if ($email_sent) {
                $update_stmt = mysqli_prepare($conn, "UPDATE volunteers SET status_notified = 1 WHERE id = ?");
                mysqli_stmt_bind_param($update_stmt, "i", $id);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }
        }

        $success_message = "Volunteer approved successfully!" . ($email_sent ? " Email notification sent." : " Email notification failed.");

    } elseif ($action === 'reject') {
        $new_status = 'Rejected';
        $query = "UPDATE volunteers SET status = ?, rejection_reason = ?, status_updated_at = NOW(), status_notified = 0 WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $new_status, $rejection_reason, $id);
        $update_success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($update_success) {
            $email_sent = sendVolunteerStatusEmail($volunteer_email, $volunteer_name, 'Rejected', $rejection_reason, $admin_notes);
            logVolunteerStatusChange($conn, $id, $previous_status, $new_status, $email_sent, $admin_notes);

            if ($email_sent) {
                $update_stmt = mysqli_prepare($conn, "UPDATE volunteers SET status_notified = 1 WHERE id = ?");
                mysqli_stmt_bind_param($update_stmt, "i", $id);
                mysqli_stmt_execute($update_stmt);
                mysqli_stmt_close($update_stmt);
            }
        }

        $success_message = "Volunteer rejected." . ($email_sent ? " Email notification sent." : " Email notification failed.");

    } elseif ($action === 'delete') {
        $stmt = mysqli_prepare($conn, "DELETE FROM volunteers WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $success_message = "Volunteer record deleted.";
    }
}

header("Location: admin-volunteers.php?success=" . urlencode($success_message));
exit();
?>