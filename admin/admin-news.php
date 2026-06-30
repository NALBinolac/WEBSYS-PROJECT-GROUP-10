<?php
session_start();
require_once '../config.php';

// Security Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch existing articles
$articles = [];
$query = "SELECT * FROM news_articles ORDER BY article_date DESC, created_at DESC";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $articles[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>News & Media | Admin Panel</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { display: flex; min-height: 100vh; font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f6f9; color: #333;}
        .sidebar { width: 260px; background-color: #2e7d32; color: white; display: flex; flex-direction: column; min-height: 100vh; padding-top: 20px; box-shadow: 2px 0 10px rgba(0,0,0,0.1); }
        .sidebar h2 { text-align: center; font-size: 1.4rem; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 15px; font-weight: 600; letter-spacing: 0.5px; }
        .sidebar a { display: block; color: rgba(255,255,255,0.85); text-decoration: none; padding: 15px 20px; transition: 0.3s; }
        .sidebar a:hover { background-color: #1b5e20; }
        .sidebar a.active { background-color: #1b5e20; border-left: 5px solid #fff; font-weight: bold; }
        .nav-links { flex: 1; }
        .nav-footer { margin-bottom: 20px; }
        
        .main-content { flex: 1; padding: 50px; }
        .grid-split { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .panel-box { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-bottom: 30px; }
        label { display: block; font-weight: 600; margin-bottom: 8px; font-size: 0.95rem; color: #333;}
        input[type="text"], input[type="date"], select, textarea { width: 100%; padding: 12px; border: 1px solid #ced4da; border-radius: 6px; margin-bottom: 20px; font-family: inherit;}
        textarea { height: 120px; resize: vertical; }
        .btn { padding: 12px 24px; background-color: #2e7d32; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; font-size: 1rem; text-decoration: none; display: inline-block; text-align: center; transition: 0.2s;}
        .btn:hover { background-color: #1b5e20; }
        .btn-danger { background-color: #d32f2f; width: auto; padding: 6px 12px; font-size: 0.8rem; }
        .btn-danger:hover { background-color: #c62828; }
        
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; font-weight: 600;}
        .alert-danger { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #f5c6cb; font-weight: 600;}

        /* Feed Styles */
        .feed-container { max-height: 500px; overflow-y: auto; padding-right: 10px; }
        .feed-container::-webkit-scrollbar { width: 6px; }
        .feed-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
        .feed-container::-webkit-scrollbar-thumb { background: #c8e6c9; border-radius: 4px; }
        .article-card { display: flex; gap: 15px; background: white; border-radius: 8px; margin-bottom: 15px; padding: 15px; border: 1px solid #eaeaea; border-left: 4px solid #2e7d32; align-items: center; }
        .article-img { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; background: #eee; }
        .article-info { flex: 1; }
        .article-title { font-weight: bold; color: #333; margin-bottom: 5px; font-size: 1rem; }
        .article-date { font-size: 0.8rem; color: #888; display: block; margin-bottom: 8px; }
        #editor-summary { background: white; height: 150px; margin-bottom: 20px; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
</head>
<body>

    <?php include '../includes/admin-navbar.php'; ?>

    <div class="main-content">
        <h1 style="margin-bottom: 10px;">News & Media Feed</h1>
        <p style="color: #666; margin-bottom: 30px;">Publish updates, event highlights, and media features to the public news feed.</p>

        <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert-success">Article successfully published!</div>
        <?php endif; ?>
        <?php if(isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert-success">Article has been removed.</div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <div class="grid-split">
            <!-- Left Side: Upload Form -->
            <div>
                <div class="panel-box">
                    <h3 style="margin-bottom: 25px; color:#2e7d32;">+ Publish New Article</h3>
                    
                    <form action="admin-process-news.php" method="POST" enctype="multipart/form-data">
                        <label>Article Headline</label>
                        <input type="text" name="title" required placeholder="e.g., Youth Summit 2026 a Massive Success">
                        
                        <label>Date of Event / Publication</label>
                        <input type="date" name="article_date" required>
                        
                        <label>Short Summary</label>
                        <div id="editor-summary"></div>
                        <textarea name="summary" id="summary" style="display:none;"></textarea>
                        
                        <label>Cover Photo</label>
                        <input type="file" name="cover_image" accept="image/*" required style="padding: 10px; background: #f8f9fa;">
                        
                        <button type="submit" name="action" value="add" class="btn">Publish to Feed</button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Live Feed -->
            <div class="panel-box" style="border: 1px solid #e0e0e0; background: #fafafa; height: fit-content;">
                <h3 style="margin-bottom: 15px; color:#1b5e20;">Live Articles</h3>
                
                <div class="feed-container">
                    <?php if (empty($articles)): ?>
                        <p style="color: #888; font-style: italic; text-align: center; margin-top: 20px;">No articles published yet.</p>
                    <?php else: ?>
                        <?php foreach ($articles as $article): 
                            $date_obj = new DateTime($article['article_date']);
                            $formatted_date = $date_obj->format('F j, Y');
                        ?>
                            <div class="article-card">
                                <img src="../<?= htmlspecialchars($article['image_path']) ?>" alt="Thumbnail" class="article-img">
                                <div class="article-info">
                                    <div class="article-title"><?= htmlspecialchars($article['title']) ?></div>
                                    <span class="article-date">📅 <?= $formatted_date ?></span>
                                    
                                    <!-- Delete Button triggers a form submission to the processor -->
                                    <form action="admin-process-news.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="article_id" value="<?= $article['id'] ?>">
                                        <input type="hidden" name="image_path" value="<?= htmlspecialchars($article['image_path']) ?>">
                                        <button type="submit" class="btn btn-danger">Delete</button>
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
        const quillSummary = new Quill('#editor-summary', {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        document.querySelector('form[action="admin-process-news.php"]').addEventListener('submit', function (e) {
            document.querySelector('#summary').value = quillSummary.root.innerHTML;
            if (quillSummary.getText().trim().length === 0) {
                e.preventDefault();
                alert('Please write a summary before publishing.');
            }
        });
    </script>

</body>
</html>