<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$volunteers = [];
$result = mysqli_query($conn, "SELECT * FROM volunteers ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $volunteers[] = $row;
    }
}

$success_message = isset($_GET['success']) ? $_GET['success'] : '';

function statusBadge($status) {
    switch ($status) {
        case 'Approved':
            return "<span style='background:#d4edda;color:#155724;padding:5px 12px;border-radius:4px;font-weight:600;font-size:0.85rem;'>Approved</span>";
        case 'Rejected':
            return "<span style='background:#f8d7da;color:#721c24;padding:5px 12px;border-radius:4px;font-weight:600;font-size:0.85rem;'>Rejected</span>";
        default:
            return "<span style='background:#fff3cd;color:#856404;padding:5px 12px;border-radius:4px;font-weight:600;font-size:0.85rem;'>Pending</span>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Management | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .main-content { flex: 1; padding: 40px; }

        .alert-success {
            background: #d4edda; color: #155724; padding: 14px 20px;
            border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;
        }

        .table-container { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f1f3f5; padding: 16px 20px; border-bottom: 2px solid #dee2e6; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; text-align: left; }
        td { padding: 16px 20px; border-bottom: 1px solid #dee2e6; color: #495057; text-align: left; vertical-align: middle; }

        .btn { display: inline-block; border: none; padding: 6px 14px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; text-decoration: none; margin-right: 6px; margin-bottom: 4px; }
        .btn-approve { background-color: #2e7d32; color: white; }
        .btn-approve:hover { background-color: #1b5e20; }
        .btn-reject { background-color: #c62828; color: white; }
        .btn-reject:hover { background-color: #8e0000; }
        .btn-delete { background-color: #6c757d; color: white; }
        .btn-delete:hover { background-color: #545b62; }

        .reason-note { font-size: 0.78rem; color: #856404; margin-top: 4px; }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1>Volunteer &amp; Membership Management</h1>
        <p>Review applications and update status. Volunteers are automatically notified by email when status changes.</p>

        <?php if ($success_message): ?>
            <div class="alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Activities / Affiliation</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($volunteers)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #888;">No volunteer applications yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($volunteers as $v): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($v['fullname']) ?></strong><br>
                                <span style="font-size: 0.8rem; color: #666;"><?= htmlspecialchars($v['email']) ?></span>
                            </td>
                            <td>
                                <?= htmlspecialchars($v['activities'] ?? '') ?><br>
                                <span style="font-size: 0.8rem; color: #888;"><?= htmlspecialchars($v['affiliation'] ?? '') ?></span>
                            </td>
                            <td>
                                <?= statusBadge($v['status']) ?>
                                <?php if ($v['status'] === 'Rejected' && !empty($v['rejection_reason'])): ?>
                                    <div class="reason-note">Reason: <?= htmlspecialchars($v['rejection_reason']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars(date('M d, Y', strtotime($v['created_at']))) ?></td>
                            <td>
                                <a class="btn btn-approve" href="process-volunteer-status.php?action=approve&id=<?= $v['id'] ?>"
                                   onclick="return confirm('Approve this volunteer? An email will be sent.');">Approve</a>

                                <a class="btn btn-reject" href="#"
                                   onclick="return rejectVolunteer(<?= $v['id'] ?>);">Reject</a>

                                <a class="btn btn-delete" href="process-volunteer-status.php?action=delete&id=<?= $v['id'] ?>"
                                   onclick="return confirm('Permanently delete this record? This cannot be undone.');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function rejectVolunteer(id) {
            const reason = prompt("Reason for rejection (will be shown to the applicant and emailed):");
            if (reason === null) return false; // cancelled
            const url = "process-volunteer-status.php?action=reject&id=" + id + "&reason=" + encodeURIComponent(reason);
            window.location.href = url;
            return false;
        }
    </script>

</body>
</html>