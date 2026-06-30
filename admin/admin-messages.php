<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inquiries & Messages | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333;}
        
        /* Sidebar (Same as your other pages) */
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; min-height: 100vh; padding-top: 20px; flex-shrink: 0; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 15px 24px; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background-color: #1b5e20; }
        .sidebar a.active { border-left: 5px solid #fff; font-weight: bold; }

        .main-content { flex: 1; padding: 50px; }
        
        /* Panel Style para maging consistent */
        .panel-box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        
        .messages-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .messages-table th { background-color: #f8f9fa; color: #333; font-weight: 600; padding: 15px; border-bottom: 2px solid #e9ecef; text-align: left; }
        .messages-table td { padding: 15px; border-bottom: 1px solid #eee; color: #555; }
        
        .btn-view { background-color: #2e7d32; color: #ffffff; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; font-weight: 600; }
        .btn-view:hover { background-color: #1b5e20; }
        
        .status-badge { padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
        .status-unread { background-color: #fff3cd; color: #856404; }
        .status-read { background-color: #d1ecf1; color: #0c5460; }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4); align-items: center; justify-content: center; }
        .modal-content { background-color: #ffffff; padding: 30px; border-radius: 8px; width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .message-body { background: #f9f9f9; padding: 15px; border-radius: 4px; margin-top: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-programs.php">Content Management</a>
    <a href="admin-news.php">News & Media</a>
    <a href="admin-leaders.php">Leadership Roster</a>
    <a href="admin-modules.php">Learning Platform</a>
    <a href="admin-volunteers.php">Volunteers/Members</a>
    <a href="admin-events.php">Events Management</a>
    <a href="admin-donations.php">Donations</a>
    <a href="admin-messages.php" class="active">Inquiries / Messages</a> 
    <a href="admin-users.php">User Management</a>
    <a href="../index.php" style="margin-top: auto; background-color: #1565c0; text-align: center;">Back to Main Website</a>
    <a href="../logout.php?ctx=admin" style="background-color: #b71c1c; text-align: center;">Logout</a>
</div>

<div class="main-content">
    <h1 style="margin-bottom: 10px;">Inquiries & Messages</h1>
    <p style="color: #666; margin-bottom: 30px;">Tingnan ang mga mensahe mula sa contact form ng website.</p>

    <div class="panel-box">
        <table class="messages-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Sender Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['subject'] ?? 'Walang Subject'); ?></td>
                            <td><span class="status-badge status-<?php echo strtolower($row['status']); ?>"><?php echo $row['status']; ?></span></td>
                            <td>
                                <button class="btn-view" onclick="openMessageModal(
                                    <?= (int)$row['id'] ?>,
                                    '<?= htmlspecialchars(addslashes($row['name'])) ?>',
                                    '<?= htmlspecialchars(addslashes($row['email'])) ?>',
                                    '<?= htmlspecialchars(addslashes($row['subject'] ?? 'Walang Subject')) ?>',
                                    '<?= htmlspecialchars(addslashes(nl2br($row['message']))) ?>'
                                )">View</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">Walang mensaheng natanggap.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Message View Modal -->
<div class="modal" id="messageModal">
    <div class="modal-content">
        <h2 id="modal-subject" style="color:#2e7d32; margin-bottom: 5px;"></h2>
        <p style="color:#666; font-size:0.9rem; margin-bottom: 15px;">
            From: <strong id="modal-name"></strong> (<span id="modal-email"></span>)
        </p>
        <div class="message-body" id="modal-message"></div>

        <div style="margin-top: 20px; display:flex; gap:10px; justify-content:flex-end;">
            <button type="button" onclick="closeMessageModal()" style="background:#6c757d; color:white; border:none; padding:10px 18px; border-radius:4px; cursor:pointer;">Close</button>
            <button type="button" id="modal-mark-read-btn" onclick="markAsRead()" style="background:#2e7d32; color:white; border:none; padding:10px 18px; border-radius:4px; cursor:pointer; font-weight:600;">Mark as Read</button>
        </div>
    </div>
</div>

<script>
let currentMessageId = null;

function openMessageModal(id, name, email, subject, message) {
    currentMessageId = id;
    document.getElementById('modal-subject').innerText = subject;
    document.getElementById('modal-name').innerText = name;
    document.getElementById('modal-email').innerText = email;
    document.getElementById('modal-message').innerHTML = message;
    document.getElementById('messageModal').style.display = 'flex';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}

function markAsRead() {
    if (!currentMessageId) return;

    fetch('../api/update-message-status.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + encodeURIComponent(currentMessageId) + '&status=read'
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Failed to update status: ' + data.message);
        }
    })
    .catch(err => alert('Error: ' + err));
}

// Close modal when clicking outside the box
document.getElementById('messageModal').addEventListener('click', function(e) {
    if (e.target === this) closeMessageModal();
});
</script>

</body>
</html>