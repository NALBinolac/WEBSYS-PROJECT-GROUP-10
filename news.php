<?php
session_start();
include 'config.php';

// Fetch all articles from the database, newest first
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News & Updates | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<section class="hero">
    <div class="hero-content">
        <h1>News & Updates</h1>
        <p>
            Stay informed about our latest programs, partnerships, campaigns, events, and achievements.
        </p>
    </div>
</section>

<section>
    <div class="container">
        <h2 class="section-title">Latest Stories</h2>
        <div class="search-filter">
            <div class="search-box">
                <input type="text" id="newsSearch" placeholder="Search news articles...">
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="card-container" id="newsContainer">
            
            <?php if (empty($articles)): ?>
                <p style="text-align: center; color: #666; width: 100%;">No articles published yet. Check back soon!</p>
            <?php else: ?>
                <?php foreach ($articles as $article): 
                    // Format the date nicely
                    $date_obj = new DateTime($article['article_date']);
                    $formatted_date = $date_obj->format('F j, Y');
                ?>
                    <div class="news-card">
                        <img src="<?= htmlspecialchars($article['image_path'] ?? 'images/default-news.jpg') ?>" alt="News Thumbnail">
                        
                        <div class="news-content">
                            <div class="news-date"><?= $formatted_date ?></div>
                            
                            <h3><?= htmlspecialchars($article['title']) ?></h3>
                            
                            <p><?= $article['summary'] ?></p>
                            
                            <br>
                            <a href="#" class="btn btn-primary">Read More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<section class="mission">
    <div class="container mission-content">
        <h2>In the News</h2>
        <p>
            Youth for Just Food Systems continues to receive recognition for empowering
            young leaders and advancing just food systems across communities.
        </p>
    </div>
</section>

<section class="cta">
    <div class="container">
        <h2>Stay Connected</h2>
        <p>
            Follow our journey and discover how youth are shaping sustainable,
            compassionate, and equitable food systems.
        </p>
        <br>
        <a href="about.php" class="btn btn-primary">Learn More</a>
        <a href="programs.php" class="btn btn-secondary">View Programs</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script>
const searchInput = document.getElementById('newsSearch');

searchInput.addEventListener('keyup', function(){
    let filter = searchInput.value.toLowerCase();
    let cards = document.querySelectorAll('.news-card');

    cards.forEach(function(card){
        let text = card.textContent.toLowerCase();
        if(text.includes(filter)){
            card.style.display = "block";
        }else{
            card.style.display = "none";
        }
    });
});
</script>

</body>
</html>