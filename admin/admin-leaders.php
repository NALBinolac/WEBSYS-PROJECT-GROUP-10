<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$leaders = [];
$query = "SELECT * FROM leaders ORDER BY id ASC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $leaders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leadership Roster | Admin Panel</title>
    <style>
        /* Same core styles as your news admin page */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333;}
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; min-height: 100vh; padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 15px 20px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .main-content { flex: 1; padding: 50px; }
        .grid-split { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .panel-box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem; }
        input[type="text"], select, textarea { width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; margin-bottom: 20px; font-family: inherit;}
        textarea { height: 120px; resize: vertical; }
        .btn { padding: 12px 24px; background-color: #2e7d32; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 1rem; transition: 0.2s;}
        .btn:hover { background-color: #1b5e20; }
        .btn-danger { background-color: #d32f2f; width: auto; padding: 6px 12px; font-size: 0.8rem; }
        .btn-danger:hover { background-color: #c62828; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-weight: 600;}
        .alert-danger { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: 600;}

        .feed-container { max-height: 500px; overflow-y: auto; padding-right: 10px; }
        .leader-card { display: flex; gap: 15px; background: white; border-radius: 8px; margin-bottom: 15px; padding: 15px; border: 1px solid #eaeaea; border-left: 4px solid #2e7d32; align-items: center; }
        .leader-img { width: 70px; height: 70px; object-fit: cover; border-radius: 50%; background: #eee; }
        .leader-info { flex: 1; }
        .leader-name { font-weight: bold; color: #333; font-size: 1rem; }
        .leader-role { font-size: 0.85rem; color: #666; margin-bottom: 8px; }
        #editor-bio { background: white; height: 150px; margin-bottom: 20px; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1 style="margin-bottom: 10px;">Leadership Roster</h1>
        <p style="color: #666; margin-bottom: 30px;">Manage the executive team and board members displayed on the About page.</p>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-success">Team member added successfully!</div>
        <?php endif; ?>
        <?php if(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert-success">Team member removed.</div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <div class="grid-split">
            <!-- Left Side: Add Leader Form -->
            <div>
                <div class="panel-box">
                    <h3 style="margin-bottom: 25px; color:#2e7d32;">+ Add Team Member</h3>
                    
                    <form action="admin-process-leaders.php" method="POST" enctype="multipart/form-data">
                        <label>Full Name</label>
                        <input type="text" name="name" required placeholder="e.g., Castle Reynera">
                        
                        <label>Official Title / Role</label>
                        <input type="text" name="role" required placeholder="e.g., National Convener">
                        
                        <label>Biography</label>
                        <div id="editor-bio"></div>
                        <textarea name="bio" id="bio" style="display:none;"></textarea>
                        
                        <label>Headshot Photo</label>
                        <input type="file" name="image" accept="image/*" required style="padding: 10px; background: #f8f9fa;">
                        
                        <button type="submit" name="action" value="add" class="btn">Add to Roster</button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Live Roster -->
            <div class="panel-box" style="border: 1px solid #e0e0e0; background: #fafafa; height: fit-content;">
                <h3 style="margin-bottom: 15px; color:#1b5e20;">Current Team</h3>
                
                <div class="feed-container">
                    <?php if (empty($leaders)): ?>
                        <p style="color: #888; font-style: italic; text-align: center; margin-top: 20px;">No leaders added yet.</p>
                    <?php else: ?>
                        <?php foreach ($leaders as $leader): ?>
                            <div class="leader-card">
                                <img src="../<?= htmlspecialchars($leader['image_path']) ?>" alt="Headshot" class="leader-img">
                                <div class="leader-info">
                                    <div class="leader-name"><?= htmlspecialchars($leader['name']) ?></div>
                                    <div class="leader-role"><?= htmlspecialchars($leader['role']) ?></div>
                                    
                                    <form action="admin-process-leaders.php" method="POST" onsubmit="return confirm('Remove this member?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="leader_id" value="<?= $leader['id'] ?>">
                                        <input type="hidden" name="image_path" value="<?= htmlspecialchars($leader['image_path']) ?>">
                                        <button type="submit" class="btn btn-danger">Remove</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const quillBio = new Quill('#editor-bio', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['clean']
                ]
            }
        });

        document.querySelector('form[action="admin-process-leaders.php"]').addEventListener('submit', function (e) {
            document.querySelector('#bio').value = quillBio.root.innerHTML;
            if (quillBio.getText().trim().length === 0) {
                e.preventDefault();
                alert('Please write a biography before submitting.');
            }
        });
    </script>

</body>
</html>