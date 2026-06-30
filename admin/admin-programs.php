<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Content Management | Admin Panel</title>
    <style>
        /* Keep all your existing CSS styles exactly the same! */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; }
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .sidebar a.active { background-color: #1b5e20; border-left: 5px solid #fff; font-weight: bold; }
        .main-content { flex: 1; padding: 40px; }
        .form-group { margin-bottom: 20px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #333; }
        select, textarea, input[type="file"] { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; }
        textarea { height: 150px; resize: vertical; }
        .btn { padding: 12px 24px; background-color: #2e7d32; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .btn:hover { background-color: #1b5e20; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-weight: 600;}
        .alert-danger { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: 600;}
        #editor-content { background: white; height: 200px; margin-bottom: 20px; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1>Website Content Management</h1>
        <p>Modify text components, leadership rosters, initiative profiles, and landing modules.</p>

        <!-- Dynamic Alerts -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-success">Section updated successfully!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <!-- Form points to the new processor file -->
        <form action="admin-process-content.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select Target Workspace Section</label>
                <select name="section_name" required>
                    <option value="homepage_hero">Landing Page Hero Text</option>
                    <option value="about_who_we_are">About Us - Who We Are</option>
                    <option value="about_impact">About Us - Our Impact Narrative</option>
                    <!-- We will handle Leadership separately as it requires multiple fields per person -->
                </select>
            </div>

            <div class="form-group">
                <label>Updated Description / Narrative Text</label>
                <div id="editor-content"></div>
                <!-- This hidden textarea is what actually gets submitted (no "required" here — validated in JS below, since hidden+required breaks submission in Chrome) -->
                <textarea name="content_text" id="content_text" style="display:none;"></textarea>
            </div>

            <div class="form-group">
                <label>Attach Supporting Media Assets (Photos / Video Features)</label>
                <input type="file" name="media_asset" accept="image/*">
            </div>

            <button type="submit" class="btn">Commit Section Updates</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const quillContent = new Quill('#editor-content', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Sync Quill content into the hidden textarea right before submit
        document.querySelector('form').addEventListener('submit', function (e) {
            const html = quillContent.root.innerHTML;
            document.querySelector('#content_text').value = html;
            if (quillContent.getText().trim().length === 0) {
                e.preventDefault();
                alert('Please enter some content before submitting.');
            }
        });
    </script>

</body>
</html>