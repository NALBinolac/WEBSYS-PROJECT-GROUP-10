<?php
session_start();
include 'config.php';

$is_logged_in = isset($_SESSION['user_id']);
$available_modules = [];
$total_modules = 0;

if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'] ?? 'User';

    $modules_query = "SELECT * FROM modules ORDER BY module_number ASC";
    $modules_result = mysqli_query($conn, $modules_query);
    if ($modules_result) {
        while ($row = mysqli_fetch_assoc($modules_result)) {
            $available_modules[] = $row;
        }
    }
    $total_modules = count($available_modules); 

    $progress_query = "SELECT COUNT(DISTINCT module_number) AS completed FROM submissions WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $progress_query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $progress_result = mysqli_stmt_get_result($stmt);
    $progress_data = mysqli_fetch_assoc($progress_result);
    $completed_count = $progress_data['completed'];

    $status_query = "SELECT module_number, file_name FROM submissions WHERE user_id = ?";
    $stmt2 = mysqli_prepare($conn, $status_query);
    mysqli_stmt_bind_param($stmt2, "i", $user_id);
    mysqli_stmt_execute($stmt2);
    $status_result = mysqli_stmt_get_result($stmt2);

    $user_submissions = [];
    while ($row = mysqli_fetch_assoc($status_result)) {
        $user_submissions[$row['module_number']] = json_decode($row['file_name'], true) ?: [$row['file_name']];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Portal - Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<?php if (!$is_logged_in): ?>

    <section class="hero learning-hero">
        <div class="hero-content">
            <h1>Empower Yourself with Food Systems Knowledge</h1>
            <p>Welcome to our interactive learning platform. Access our comprehensive training framework, download reference materials, and turn in assignments.</p>
            <div class="hero-buttons">
                <a href="login.php" class="hero-btn">Log In to Dashboard</a>
                <a href="signup.php" class="hero-btn-outline">Create Free Account</a>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <h2 class="section-title">What You'll Access Inside</h2>
            <p class="section-subtitle text-center">Get structural tools to build local food leadership initiatives.</p>
            
            <div class="card-container">
                <div class="card value-card">
                    <h3>📚 Core Curriculum</h3>
                    <p>Carefully structured curriculum focusing on agroecology and food sustainability.</p>
                </div>
                <div class="card value-card">
                    <h3>💾 PDF Downloads</h3>
                    <p>Save reading packages and interactive core study resources completely offline.</p>
                </div>
                <div class="card value-card">
                    <h3>📈 Progress Tracking</h3>
                    <p>Submit course assessments directly to your digital workspace dropzones.</p>
                </div>
            </div>
        </div>
    </section>

<?php else: ?>

    <section style="padding: 40px 0; background-color: var(--light-gray); border-bottom: 1px solid var(--border);">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <h2>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h2>
                <p>Track your assignments and access educational modules below.</p>
            </div>
            <div class="text-center">
                <span class="btn btn-primary" style="cursor: default; pointer-events: none; white-space: nowrap;">
                    Progress: <?php echo $completed_count; ?> / <?php echo $total_modules; ?> Modules Completed
                </span>
                <div style="margin-top: 10px;">
                    <a href="logout.php" class="delete-btn">Log Out</a>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <h2 class="section-title" style="text-align: left; margin-bottom: 35px; border-bottom: 3px solid var(--accent-yellow); padding-bottom: 10px;">Modules Hub</h2>
            
            <div class="card-container">
                <?php if (empty($available_modules)): ?>
                    <p style="color: #666; font-style: italic;">No learning modules have been deployed yet. Please check back later!</p>
                <?php else: ?>
                    <?php foreach ($available_modules as $module): 
                        $i = $module['module_number']; // Keep your $i variable logic for the dropzones
                    ?>
                        <div class="card resource-card" style="display: flex; flex-direction: column; justify-content: space-between; min-height: 380px;">
                            <div>
                                <h3>Module <?php echo htmlspecialchars($i); ?>: <?php echo htmlspecialchars($module['title']); ?></h3>
                                <hr style="border: 0; border-top: 1px solid var(--border); margin: 15px 0;">
                                
                                <p style="margin-bottom: 15px;">
                                    📄 <strong>Type:</strong> <span style="text-transform: capitalize;"><?php echo htmlspecialchars($module['type']); ?></span><br>
                                    
                                    <a href="<?php echo htmlspecialchars($module['file_path']); ?>" download class="edit-btn" style="display: inline-block; margin-top: 5px;">
                                        📥 Download Module <?php echo $i; ?> PDF
                                    </a>
                                </p>
                            </div>

                            <div style="background-color: var(--light-gray); padding: 15px; border-radius: 10px; border: 1px dashed #ccc; margin-top: 15px;">
                                <h4 style="margin: 0 0 8px 0; font-size: 1rem; color: var(--text-black);">Assignment Dropzone</h4>
                                
                                <?php if (isset($user_submissions[$i])): ?>
                                    <p style="color: var(--primary-green); font-weight: 700; margin: 0 0 10px 0;">✅ Status: Submitted</p>
                                    <div style="background: white; padding: 10px; border-radius: 6px; border: 1px solid #ddd;">
                                        <small style="color: #555; font-weight: 600; display: block; margin-bottom: 5px;">Attached Files:</small>
                                        <?php foreach($user_submissions[$i] as $file): ?>
                                            <small style="color: #666; display: block; word-break: break-all; margin-bottom: 3px;">📄 <?= htmlspecialchars($file) ?></small>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p style="color: #e65100; font-weight: 700; margin: 0 0 10px 0; font-size: 0.9rem;">⚠️ Status: Pending Submission</p>
                                    
                                    <form action="process-upload.php" method="POST" enctype="multipart/form-data" style="background: transparent; padding: 0; box-shadow: none; border-radius: 0;">
                                        <input type="hidden" name="module_number" value="<?php echo $i; ?>">
                                        <input type="file" name="assignment_files[]" multiple required style="padding: 5px; margin-bottom: 10px; font-size: 0.85rem; width: 100%;">
                                        <button type="submit" class="btn btn-primary" style="padding: 8px 20px; font-size: 0.85rem; border-radius: 10px; width: auto;">
                                            Upload Work
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>

</body>
</html>