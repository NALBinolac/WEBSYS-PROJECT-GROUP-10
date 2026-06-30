<?php
session_start();
require_once '../config.php';

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$error = "";
$success = "";

if (!isset($_GET['id'])) {
    header("Location: admin-events.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $date = $_POST['date'];
    
    $start_time_raw = $_POST['start_time'];
    $end_time_raw = $_POST['end_time'];
    $start_formatted = date("g:i A", strtotime($start_time_raw));
    $end_formatted = date("g:i A", strtotime($end_time_raw));
    $time_range = $start_formatted . ' - ' . $end_formatted;

    $venue = $_POST['venue'];
    $category = $_POST['category'];
    $description = $_POST['description'];

    // Update query with all 6 columns
    $update_query = "UPDATE events SET title = ?, date = ?, time_range = ?, venue = ?, category = ?, description = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $title, $date, $time_range, $venue, $category, $description, $id);

    if (mysqli_stmt_execute($stmt)) {
        $success = "Event details updated successfully!";
    } else {
        $error = "Failed to update event.";
    }
}

// Fetch Current Event Data
$query = "SELECT * FROM events WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$event = mysqli_fetch_assoc($result);

if (!$event) {
    die("Event not found in the database.");
}

// Break the saved time_range string back into start and end times for the HTML pickers
$start_val = '';
$end_val = '';
if (!empty($event['time_range']) && strpos($event['time_range'], ' - ') !== false) {
    $times = explode(' - ', $event['time_range']);
    // Convert "9:00 AM" back to "09:00" for the HTML input
    $start_val = date('H:i', strtotime($times[0])); 
    $end_val = date('H:i', strtotime($times[1]));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; background-color: #f8f9fa; color: #333; }
        
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; font-weight: 600; letter-spacing: 0.5px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 14px 24px; transition: all 0.2s ease-in-out; font-weight: 500; }
        .sidebar a:hover { background-color: #1b5e20; padding-left: 28px; }
        .sidebar a.active { background-color: #1b5e20; border-left: 4px solid #fff; color: white; }
        
        .main-content { flex: 1; padding: 50px; }
        
        .form-group { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.04); border: 1px solid #eaeaea; margin-top: 10px; }
        
        .page-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start; max-width: 1200px; }
        
        .info-card { background: linear-gradient(135deg, #e8f5e9, #c8e6c9); padding: 30px; border-radius: 12px; color: #1b5e20; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .info-card h3 { margin-bottom: 15px; font-size: 1.1rem; }
        .info-card ul { margin-left: 20px; font-size: 0.95rem; line-height: 1.6; }
        
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #495057; font-size: 0.95rem; }
        input, select, textarea { width: 100%; padding: 14px 16px; border: 1px solid #ced4da; border-radius: 8px; font-size: 1rem; margin-bottom: 24px; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; color: #495057; background-color: #fff; font-family: inherit;}
        textarea { resize: vertical; min-height: 100px; }
        input:focus, select:focus, textarea:focus { border-color: #80bdff; outline: 0; box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25); }
        
        .btn-container { display: flex; gap: 12px; margin-top: 10px; }
        .btn { padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; text-decoration: none; font-size: 1rem; transition: all 0.2s ease-in-out; display: inline-flex; align-items: center; justify-content: center; }
        .btn-save { background-color: #2e7d32; color: white; box-shadow: 0 4px 6px rgba(46, 125, 50, 0.2); }
        .btn-save:hover { background-color: #1b5e20; transform: translateY(-1px); box-shadow: 0 6px 8px rgba(46, 125, 50, 0.3); }
        .btn-back { background-color: #f8f9fa; color: #495057; border: 1px solid #ced4da; }
        .btn-back:hover { background-color: #e2e6ea; border-color: #dae0e5; }
        
        .alert { padding: 16px 20px; border-radius: 8px; margin-bottom: 25px; font-weight: 500; font-size: 0.95rem; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        #editor-description { background: white; height: 150px; margin-bottom: 24px; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1 style="font-size: 2.2rem; color: #111; margin-bottom: 5px;">Edit Event</h1>
        <p style="color: #666; margin-bottom: 30px;">Update details for this scheduled event.</p>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="page-layout">
            <div class="form-group">
                <form action="admin-edit-event.php?id=<?= $id ?>" method="POST">
                    
                    <label>Event Title</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div>
                            <label>Event Date</label>
                            <input type="date" name="date" value="<?= htmlspecialchars($event['date']) ?>" required>
                        </div>
                        <div>
                            <label>Time Range</label>
                            <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 24px;">
                                <input type="time" name="start_time" value="<?= $start_val ?>" required style="margin-bottom: 0;">
                                <span style="color: #666; font-weight: 600;">to</span>
                                <input type="time" name="end_time" value="<?= $end_val ?>" required style="margin-bottom: 0;">
                            </div>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 15px;">
                        <div>
                            <label>Venue / Location</label>
                            <input type="text" name="venue" value="<?= htmlspecialchars($event['venue']) ?>" required>
                        </div>
                        <div>
                            <label>Category Tag</label>
                            <select name="category" required>
                                <option value="Festival" <?= ($event['category'] == 'Festival') ? 'selected' : '' ?>>Festival</option>
                                <option value="Workshop" <?= ($event['category'] == 'Workshop') ? 'selected' : '' ?>>Workshop</option>
                                <option value="Discussion" <?= ($event['category'] == 'Discussion') ? 'selected' : '' ?>>Discussion</option>
                                <option value="Campaign" <?= ($event['category'] == 'Campaign') ? 'selected' : '' ?>>Campaign</option>
                            </select>
                        </div>
                    </div>

                    <label>Event Description</label>
                    <div id="editor-description"></div>
                    <textarea name="description" id="description" style="display:none;"></textarea>
                    <!-- Seed field carries the existing saved value so JS can load it into Quill on page load -->
                    <textarea id="description_seed" style="display:none;"><?= htmlspecialchars($event['description'] ?? '') ?></textarea>
                    <div class="btn-container">
                        <a href="admin-events.php" class="btn btn-back">← Back to Events</a>
                        <button type="submit" class="btn btn-save">Save Changes</button>
                    </div>
                </form>
            </div>

            <div class="info-card">
                <h3>🔄 Editing Protocol</h3>
                <p style="margin-bottom: 10px; font-size: 0.95rem;">Keep in mind when updating events:</p>
                <ul>
                    <li>If the venue changes, ensure registered volunteers are notified.</li>
                    <li>Changing the date might cause conflicts with other scheduled tasks.</li>
                    <li>Updates will reflect immediately on the main website.</li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script>
        const quillDescription = new Quill('#editor-description', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        // Load the existing saved description into Quill.
        // Old events were saved as plain text, newer ones (saved via Quill) are already HTML.
        const seedText = document.querySelector('#description_seed').value;
        if (seedText.includes('<')) {
            // Already HTML (previously edited/saved with the rich text editor)
            quillDescription.root.innerHTML = seedText;
        } else {
            // Legacy plain text — split into paragraphs and escape safely
            const paragraphs = seedText.split(/\r?\n+/).filter(p => p.trim().length > 0);
            const escape = s => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            const html = paragraphs.map(p => '<p>' + escape(p) + '</p>').join('');
            quillDescription.root.innerHTML = html || '<p><br></p>';
        }

        document.querySelector('form[action^="admin-edit-event.php"]').addEventListener('submit', function (e) {
            document.querySelector('#description').value = quillDescription.root.innerHTML;
            if (quillDescription.getText().trim().length === 0) {
                e.preventDefault();
                alert('Please describe the event before submitting.');
            }
        });
    </script>

</body>
</html>