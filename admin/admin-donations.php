<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fallback empty tracker data array if specific tables haven't been compiled yet
$donations = [];
try {
    $result = mysqli_query($conn, "SELECT * FROM donations ORDER BY id DESC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $donations[] = $row;
        }
    }
} catch (mysqli_sql_exception $e) { }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Management | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .sidebar a.active { background-color: #1b5e20; border-left: 5px solid #fff; font-weight: bold; }
        .main-content { flex: 1; padding: 40px; }
        
        .table-container { background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); overflow: hidden; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { background-color: #f1f3f5; padding: 16px 20px; border-bottom: 2px solid #dee2e6; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; text-align: left; }
        td { padding: 16px 20px; border-bottom: 1px solid #dee2e6; color: #495057; text-align: left; vertical-align: middle; }

        /* Karagdagang style para sa View Receipt Button */
        .btn-receipt {
            display: inline-block;
            background-color: #2e7d32;
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .btn-receipt:hover {
            background-color: #1b5e20;
        }
        .no-image {
            color: #999;
            font-style: italic;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1>Donation &amp; Support Management</h1>
        <p>Review organization partnerships, advisory group applications, shop transaction logs, and gift pledges.</p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Contributor Reference</th>
                        <th>Channel / Form Type</th>
                        <th>Pledge Details / Value</th>
                        <th>Proof of Payment</th> <th>Submission Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($donations)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #888;">No donor entries or support pipeline transmissions found yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($donations as $donation): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($donation['donor_name'] ?? 'Anonymous') ?></strong><br>
                                <span style="font-size: 0.8rem; color: #666;"><?= htmlspecialchars($donation['email'] ?? '') ?></span>
                            </td>
                            
                            <td>
                                <?= htmlspecialchars($donation['payment_channel'] ?? 'General Fund Pledge') ?><br>
                                <span style="font-size: 0.8rem; color: #888;"><?= htmlspecialchars($donation['purpose'] ?? '') ?></span>
                            </td>
                            
                            <td><strong>PHP <?= htmlspecialchars(number_format($donation['amount'] ?? 0, 2)) ?></strong></td>
                            
                            <td>
                                <?php if (!empty($donation['proof_path'])): ?>
                                    <a href="../<?= htmlspecialchars($donation['proof_path']) ?>" target="_blank" class="btn-receipt">View Receipt</a>
                                <?php else: ?>
                                    <span class="no-image">No Image</span>
                                <?php endif; ?>
                            </td>
                            
                            <td><?= htmlspecialchars($donation['created_at'] ?? 'N/A') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>