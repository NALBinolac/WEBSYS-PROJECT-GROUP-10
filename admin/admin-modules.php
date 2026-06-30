<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
    header("Location: ../login.php");
    exit(); 
}

// DITO NATIN INAYOS! Gagamitin natin ang u.username dahil 'yan ang column name sa database mo.
$submissions_query = "SELECT s.*, u.username AS student_name 
                      FROM submissions s 
                      LEFT JOIN users u ON s.user_id = u.id 
                      ORDER BY s.submitted_at DESC";
$submissions_result = mysqli_query($conn, $submissions_query);

$modules_query = "SELECT * FROM modules ORDER BY module_number ASC";
$modules_result = mysqli_query($conn, $modules_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Platform Management</title>
    <style>
        * { box-sizing: border-box; }
        body { display: flex; min-height: 100vh; margin: 0; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .sidebar a.active { background-color: #1b5e20; border-left: 5px solid #fff; font-weight: bold; }

        .main-content { flex: 1; padding: 40px; background-color: #ffffff; box-sizing: border-box; }
        .dashboard-layout { display: flex; gap: 30px; margin-bottom: 40px; }
        .form-panel { flex: 1.2; background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #eef0f2; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        .feed-panel { flex: 0.8; background: #ffffff; padding: 25px; border-radius: 8px; border: 1px solid #eef0f2; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
        h1 { font-size: 2.2rem; font-weight: 700; color: #000000; margin: 0 0 5px 0; }
        p.subtitle { color: #666666; margin: 0 0 30px 0; font-size: 0.95rem; }
        h3 { margin-top: 0; font-size: 1.2rem; color: #111827; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 5px; color: #374151; }
        .form-group input[type="text"], .form-group select { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { width: 100%; background-color: #2e7d32; color: white; padding: 12px; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 1rem; margin-top: 10px; }
        .btn-submit:hover { background-color: #1b5e20; }
        
        /* Student Activity Feed Style */
        .submission-card { border-left: 4px solid #2e7d32; background: #f9f9f9; padding: 15px; border-radius: 4px; margin-bottom: 15px; border-top: 1px solid #eeeeee; border-right: 1px solid #eeeeee; border-bottom: 1px solid #eeeeee; }
        .student-header { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .avatar { width: 35px; height: 35px; background: #dcfce7; color: #15803d; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; }
        .student-meta h4 { margin: 0; font-size: 0.95rem; color: #111827; }
        .student-meta span { font-size: 0.75rem; color: #666666; }
        .file-badge { background: #ffffff; border: 1px solid #dddddd; padding: 8px 12px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center; margin-top: 5px; font-size: 0.85rem; }
        .btn-download { background: #ffffff; border: 1px solid #2e7d32; color: #2e7d32; padding: 4px 10px; border-radius: 4px; text-decoration: none; font-size: 0.8rem; font-weight: 600; }
        .btn-download:hover { background: #2e7d32; color: white; }
        .mod-tag { background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; }

        /* Inventory List Style */
        .inventory-section { margin-top: 20px; }
        .inventory-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eeeeee; }
        .inventory-meta h5 { margin: 0; font-size: 1rem; color: #111827; }
        .inventory-meta p { margin: 3px 0 0 0; font-size: 0.85rem; color: #666666; }
        .action-btns { display: flex; gap: 8px; }
        .btn-edit { background-color: #2e7d32; color: white; padding: 6px 14px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; text-decoration: none; }
        .btn-delete { background-color: #c62828; color: white; padding: 6px 14px; border: none; border-radius: 4px; cursor: pointer; font-size: 0.85rem; text-decoration: none; }
    </style>
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1>Learning Platform Management</h1>
        <p class="subtitle">Deploy core text records, tracking assessments, quizzes, and digital worksheets for workspace students.</p>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] === 'success'): ?>
                <div style="background:#d4edda; color:#155724; padding:12px; border-radius:5px; margin-bottom:20px; font-weight:600;">
                    ✅ Successfully uploaded <?= htmlspecialchars($_GET['count'] ?? '') ?> module(s)!
                </div>
            <?php elseif ($_GET['status'] === 'partial'): ?>
                <div style="background:#fff3cd; color:#856404; padding:12px; border-radius:5px; margin-bottom:20px; font-weight:600;">
                    ⚠️ Uploaded <?= htmlspecialchars($_GET['count'] ?? '') ?> module(s), but some had issues: <?= htmlspecialchars($_GET['errors'] ?? '') ?>
                </div>
            <?php elseif ($_GET['status'] === 'deleted'): ?>
                <div style="background:#d4edda; color:#155724; padding:12px; border-radius:5px; margin-bottom:20px; font-weight:600;">
                    ✅ Module deleted successfully!
                </div>
            <?php elseif ($_GET['status'] === 'error'): ?>
                <div style="background:#f8d7da; color:#721c24; padding:12px; border-radius:5px; margin-bottom:20px; font-weight:600;">
                    ❌ Upload failed: <?= htmlspecialchars($_GET['errors'] ?? '') ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="dashboard-layout">
            <div class="form-panel">
                <h3 style="color: #2e7d32;">+ Upload / Edit Educational Material</h3>
                <p style="font-size: 0.85rem; color: #856404; background: #fff3cd; padding: 8px; border-radius: 4px; margin-bottom: 20px;">
                    💡 Note: Typing an existing Module Number will overwrite/edit that specific module record! You can add multiple modules below and upload them all at once.
                </p>
                <form action="admin-process-module.php" method="POST" enctype="multipart/form-data" id="batch-module-form">
                    <div id="module-rows">
                        <div class="module-row" style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 15px; margin-bottom: 15px; position: relative;">
                            <div class="form-group">
                                <label>Module Number</label>
                                <input type="number" name="module_number[]" placeholder="e.g., 1" required style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;">
                            </div>
                            <div class="form-group">
                                <label>Module / Assignment Title</label>
                                <input type="text" name="title[]" placeholder="e.g., Intro to Sustainable Urban Farming" required>
                            </div>
                            <div class="form-group">
                                <label>Resource Type Classification</label>
                                <select name="type[]">
                                    <option value="Required Core Reading Link">Required Core Reading Link</option>
                                    <option value="Quiz Blueprint File">Quiz Blueprint File</option>
                                    <option value="Practical Project Sheet">Practical Project Sheet</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Upload File Asset (PDF only)</label>
                                <input type="file" name="module_pdf[]" accept="application/pdf" required>
                            </div>
                            <button type="button" class="remove-row-btn" onclick="removeModuleRow(this)" style="display:none; background:#c62828; color:white; border:none; padding:6px 12px; border-radius:4px; font-size:0.8rem; cursor:pointer; position:absolute; top:10px; right:10px;">Remove</button>
                        </div>
                    </div>

                    <button type="button" onclick="addModuleRow()" style="width:100%; background:#1565c0; color:white; padding:10px; border:none; border-radius:4px; font-weight:600; cursor:pointer; margin-bottom:10px;">+ Add Another Module</button>
                    <button type="submit" class="btn-submit">Deploy All to Platform</button>
                </form>

                <script>
                function addModuleRow() {
                    const rows = document.getElementById('module-rows');
                    const newRow = rows.children[0].cloneNode(true);
                    // Clear values in the cloned row
                    newRow.querySelectorAll('input[type="number"], input[type="text"]').forEach(el => el.value = '');
                    newRow.querySelectorAll('input[type="file"]').forEach(el => el.value = '');
                    newRow.querySelector('select').selectedIndex = 0;
                    newRow.querySelector('.remove-row-btn').style.display = 'inline-block';
                    rows.appendChild(newRow);
                    updateRemoveButtons();
                }
                function removeModuleRow(btn) {
                    btn.closest('.module-row').remove();
                    updateRemoveButtons();
                }
                function updateRemoveButtons() {
                    const rows = document.querySelectorAll('.module-row');
                    rows.forEach((row, index) => {
                        const removeBtn = row.querySelector('.remove-row-btn');
                        removeBtn.style.display = rows.length > 1 ? 'inline-block' : 'none';
                    });
                }
                </script>
            </div>

            <div class="feed-panel">
                <h3>Student Activity Overview</h3>
                <p style="font-size: 0.85rem; color: #666666; margin-bottom: 20px;">Recent assignments submitted by your enrolled users.</p>
                
        <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
        <?php if ($submissions_result && mysqli_num_rows($submissions_result) > 0): ?>
        <?php while($sub = mysqli_fetch_assoc($submissions_result)): 
            $file_names = json_decode($sub['file_name'], true) ?: [$sub['file_name']];
            $file_paths = json_decode($sub['file_path'], true) ?: [$sub['file_path']];
            $display_name = !empty($file_names) ? $file_names[0] : 'View Submission';
            $download_link = !empty($file_paths) ? '../' . $file_paths[0] : '#';
            
            // Kukunin nito ang eksaktong pangalan mula sa database nang hindi nangingialam sa file name
            $final_name = !empty($sub['student_name']) ? $sub['student_name'] : 'Deleted/Unknown User';
        ?>
            <div class="submission-card">
                <div class="student-header">
                    <div class="avatar">
                        <?php echo strtoupper(substr($final_name, 0, 1)); ?>
                    </div>
                    <div class="student-meta" style="flex-grow: 1;">
                        <!-- LALABAS NA DITO ANG "Niña Angela Binolac" O KUNG SINO MAN -->
                        <h4><?php echo htmlspecialchars($final_name); ?></h4>
                        <span><?php echo date('M d, Y • g:i A', strtotime($sub['submitted_at'])); ?></span>
                    </div>
                    <span class="mod-tag">MODULE <?php echo htmlspecialchars($sub['module_number']); ?></span>
                </div>
                <div class="file-badge">
                    <span style="max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        📄 <?php echo htmlspecialchars($display_name); ?>
                    </span>
                    <a href="<?php echo htmlspecialchars($download_link); ?>" class="btn-download" download>Download</a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div style="text-align: center; color: #999999; padding: 40px; border: 1px dashed #dddddd; border-radius: 4px;">
            Walang nahanap na mga pagsusumite sa kasalukuyan.
        </div>
    <?php endif; ?>
</div>
            </div>
        </div>

        <div class="inventory-section">
            <h3 style="color: #2e7d32; border-bottom: 2px solid #2e7d32; padding-bottom: 8px;">Active Modules Inventory</h3>
            <?php if ($modules_result && mysqli_num_rows($modules_result) > 0): ?>
                <?php while($mod = mysqli_fetch_assoc($modules_result)): ?>
                    <div class="inventory-item">
                        <div class="inventory-meta">
                            <h5>M<?php echo htmlspecialchars($mod['module_number']); ?>: <?php echo htmlspecialchars($mod['title']); ?></h5>
                            <p>Type: <?php echo htmlspecialchars($mod['type']); ?></p>
                        </div>
                        <div class="action-btns">
                            <a href="admin-modules.php?edit=<?php echo $mod['module_number']; ?>" class="btn-edit">Edit</a>
                            <a href="admin-delete-module.php?module_number=<?php echo $mod['module_number']; ?>" class="btn-delete" onclick="return confirm('Sigurado ka bang nais mong burahin ang module na ito?')">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: #666666; padding: 20px 0;">Walang idinagdag na module sa imbentaryo.</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>