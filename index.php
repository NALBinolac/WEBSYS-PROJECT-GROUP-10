<?php
session_start();
include 'config.php';

// Fetch Hero Text
$heroData = getSectionContent('homepage_hero', $conn);

// Fetch only the 3 most recent news articles for the homepage
$latestNews = [];
$newsQuery = "SELECT * FROM news_articles ORDER BY article_date DESC, created_at DESC LIMIT 3";
$newsResult = mysqli_query($conn, $newsQuery);
if ($newsResult) {
    while ($row = mysqli_fetch_assoc($newsResult)) {
        $latestNews[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Youth for Just Food Systems
    </title>

    <link rel="stylesheet"
          href="css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap"
          rel="stylesheet">

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO SECTION -->

<section class="hero">
    <div class="hero-content">
        <h1><?= $heroData['content_text'] ?? 'Building Youth-Powered Just Food Systems Together' ?></h1>
        
        <div class="hero-buttons">
            <a href="about.php" class="hero-btn">Learn More</a>
            <a href="get-involved.php" class="hero-btn-outline">Join the Movement</a>
        </div>
    </div>
</section>

<!-- VALUES -->

<section>

    <div class="container">

        <h2 class="section-title">
            Our Values
        </h2>

        <div class="card-container">

            <div class="card value-card">

                <h3>
                    Empower & Regenerate
                </h3>

                <p>

                    Equip youth with the knowledge,
                    skills, and confidence needed
                    to lead food-system change.

                </p>

            </div>

            <div class="card value-card">

                <h3>
                    Solidarity & Collaboration
                </h3>

                <p>

                    Stand with communities,
                    animals, and ecosystems while
                    building meaningful partnerships.

                </p>

            </div>

            <div class="card value-card">

                <h3>
                    Liberation & Accountability
                </h3>

                <p>

                    Challenge injustice and ensure
                    transparency, impact, and
                    long-term sustainability.

                </p>

            </div>

        </div>

    </div>

</section>

<!-- FEATURED PROGRAMS -->

<section>

    <div class="container">

        <h2 class="section-title">
            Featured Programs
        </h2>

        <div class="card-container">

            <div class="card">

                <h3>
                    Sustainable Dining Initiatives
                </h3>

                <p>

                    Supporting universities in
                    implementing sustainable
                    dining practices during events.

                </p>

            </div>

            <div class="card">

                <h3>
                    Annual Plant-Based Festivals
                </h3>

                <p>

                    Bringing communities together
                    to celebrate sustainable
                    and plant-based food systems.

                </p>

            </div>

            <div class="card">

                <h3>
                    Plant-Forward Canteens
                </h3>

                <p>

                    Encouraging healthier and
                    more sustainable food choices
                    in educational institutions.

                </p>

            </div>

        </div>

    </div>

</section>

<!-- IMPACT STATS -->

<section class="stats">

    <div class="container">

        <div class="stats-grid">

            <div>

                <div class="stat-number">
                    500+
                </div>

                <div class="stat-label">
                    Youth Reached
                </div>

            </div>

            <div>

                <div class="stat-number">
                    30+
                </div>

                <div class="stat-label">
                    Community Partners
                </div>

            </div>

            <div>

                <div class="stat-number">
                    50+
                </div>

                <div class="stat-label">
                    Events Conducted
                </div>

            </div>

            <div>

                <div class="stat-number">
                    10K+
                </div>

                <div class="stat-label">
                    People Impacted
                </div>

            </div>

        </div>

    </div>

</section>

<!-- LATEST NEWS -->
<section>
    <div class="container">
        <h2 class="section-title">Latest News</h2>
        <div class="card-container">

            <?php if (empty($latestNews)): ?>
                <p style="text-align: center; color: #666; width: 100%;">No recent news available.</p>
            <?php else: ?>
                <?php foreach ($latestNews as $news): ?>
                    <!-- Added padding: 0 and overflow: hidden so the image stretches edge-to-edge -->
                    <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                        
                        <!-- The dynamic uploaded picture -->
                        <img src="<?= htmlspecialchars($news['image_path'] ?? 'images/default-news.jpg') ?>" 
                             alt="News Image" 
                             style="width: 100%; height: 200px; object-fit: cover;">
                        
                        <!-- The Text Content -->
                        <div style="padding: 25px; flex: 1;">
                            <h3 style="margin-top: 0;"><?= htmlspecialchars($news['title']) ?></h3>
                            <p style="margin-bottom: 0;"><?= $news['summary'] ?></p>
                        </div>
                        
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<!-- UPCOMING EVENTS -->

<section>

    <div class="container">

        <h2 class="section-title">
            Upcoming Events
        </h2>

        <div class="card-container">

            <div class="card">

                <h3>
                    Annual Plant-Based Festival
                </h3>

                <p>

                    A celebration of sustainability,
                    food innovation, and youth action.

                </p>

            </div>

            <div class="card">

                <h3>
                    Food Systems Workshop
                </h3>

                <p>

                    Interactive discussions on
                    food justice and sustainability.

                </p>

            </div>

            <div class="card">

                <h3>
                    Youth Leadership Training
                </h3>

                <p>

                    Developing future leaders for
                    just food systems advocacy.

                </p>

            </div>

        </div>

    </div>

</section>

<!-- SDG SECTION -->

<section>

    <div class="container">

        <h2 class="section-title">
            Supporting Global Goals
        </h2>

        <p class="section-subtitle">

            Our initiatives contribute to the
            United Nations Sustainable Development Goals.

        </p>

        <div class="sdg-grid">

            <div class="sdg-card">

                <h4>
                    SDG 2
                </h4>

                <p>
                    Zero Hunger
                </p>

            </div>

            <div class="sdg-card">

                <h4>
                    SDG 3
                </h4>

                <p>
                    Good Health & Well-Being
                </p>

            </div>

            <div class="sdg-card">

                <h4>
                    SDG 12
                </h4>

                <p>
                    Responsible Consumption
                </p>

            </div>

            <div class="sdg-card">

                <h4>
                    SDG 13
                </h4>

                <p>
                    Climate Action
                </p>

            </div>

        </div>

    </div>

</section>

<!-- CTA -->

<section class="cta">

    <div class="container">

        <h2>
            Join the Movement
        </h2>

        <p>

            Together, we can build food systems
            that liberate communities, animals,
            and ecosystems.

        </p>

        <br>

        <a href="about.php"
           class="btn btn-primary">

           Learn More

        </a>

        <a href="programs.php"
           class="btn btn-secondary">

           Explore Programs

        </a>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>