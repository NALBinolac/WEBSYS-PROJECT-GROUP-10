<?php
session_start();
include 'config.php';

// Fetch the "Who We Are" data from the database before the page loads
$aboutData = getSectionContent('about_who_we_are', $conn);
$impactData = getSectionContent('about_impact', $conn);
// Fetch the Leadership roster
$leaders = [];
$leaderQuery = "SELECT * FROM leaders ORDER BY id ASC";
$leaderResult = mysqli_query($conn, $leaderQuery);
if ($leaderResult) {
    while ($row = mysqli_fetch_assoc($leaderResult)) {
        $leaders[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<section class="hero about-hero">
    <div class="hero-content">
        <h1>Who We Are</h1>
        <p>
            A youth-led organization transforming
            food systems through education,
            sustainability, animal welfare,
            and community empowerment.
        </p>
    </div>
</section>

<section class="about-intro">
    <div class="container">
        <h2 class="section-title">
            About Youth for Just Food Systems
        </h2>

        <p class="section-subtitle"><?= $aboutData['content_text'] ?? 'Youth for Just Food Systems is a non-profit, nonstock youth-led organization driving transformative change in how food is produced, distributed, and consumed.' ?></p>
        
        <?php if (!empty($aboutData['image_path'])): ?>
            <div style="text-align: center; margin-top: 30px;">
                <img src="<?= htmlspecialchars($aboutData['image_path']) ?>" alt="About Youth for Just Food Systems" style="max-width: 100%; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="mission">
    <div class="container mission-content">
        <h2>Our Mission</h2>
        <p>Building Youth-Powered Just Food Systems Together</p>
    </div>
</section>

<section class="vision-section">
    <div class="container">
        <h2 class="section-title">Our Vision</h2>
        <p class="section-subtitle">
            Just food systems that liberate communities, animals, and ecosystems.
        </p>
    </div>
</section>

<section class="explanation-section" style="background-color: var(--light-gray);">
    <div class="container">
        <h2 class="section-title">What Do We Mean by Just Food Systems?</h2>
        <div class="card" style="max-width: 900px; margin: 0 auto;">
            <p style="text-align: justify;">
                A just food system centers animal liberation
                by ensuring farmed animals are treated with
                compassionate care and freedom, but it can’t succeed
                unless we also dismantle the social injustices—
                racism, classism, colonialism, and economic
                inequality—that underpin and sustain animal
                exploitation.
                <br><br>
                In other words, there can be no real animal
                liberation without addressing and healing the
                human and ecological injustices entangled in
                our food systems.
            </p>
        </div>
    </div>
</section>

<section class="values">
    <div class="container">
        <h2 class="section-title">Our Values</h2>
        <div class="card-container">
            <div class="card value-card">
                <h3>Empower & Regenerate</h3>
                <p>
                    <strong>Empower:</strong> Equip youth with knowledge, skills, and confidence to lead food-system change.
                    <br><br>
                    <strong>Regenerate:</strong> Prioritize practices and projects that restore ecosystems and social well-being, not merely maintain them.
                </p>
            </div>
            <div class="card value-card">
                <h3>Solidarity & Collaboration</h3>
                <p>
                    <strong>Solidarity:</strong> Listen to and stand with communities, animals, and ecosystems most affected by food injustices; center their voices.
                    <br><br>
                    <strong>Collaboration:</strong> Forge inclusive partnerships across sectors, disciplines, and geographies to co-create solutions.
                </p>
            </div>
            <div class="card value-card">
                <h3>Liberation & Accountability</h3>
                <p>
                    <strong>Liberation:</strong> Actively challenge and dismantle oppressive structures in food systems to achieve freedom and equity for people, animals, and the planet.
                    <br><br>
                    <strong>Accountability:</strong> Track progress openly, learn from outcomes, and adjust actions to ensure lasting impact and fidelity to justice.
                </p>
            </div>
        </div>
    </div>
</section>
          
<section class="leadership-section" style="background-color: var(--light-gray);">
    <div class="container">
        <h2 class="section-title">Our Leadership</h2>
        <p class="section-subtitle">
            Meet the passionate youth leaders driving transformative change in food systems, sustainability, and community empowerment.
        </p>

        <div class="leadership-grid">
            
            <?php if (empty($leaders)): ?>
                <p style="text-align: center; width: 100%; color: #666;">Leadership roster is currently being updated.</p>
            <?php else: ?>
                <?php foreach ($leaders as $leader): ?>
                    <div class="leader-card">
                        
                        <img src="<?= htmlspecialchars($leader['image_path']) ?>" 
                             alt="<?= htmlspecialchars($leader['name']) ?>" 
                             class="leader-img">
                        
                        <h3><?= htmlspecialchars(strtoupper($leader['name'])) ?></h3>
                        
                        <p class="leader-role">
                            <?= htmlspecialchars($leader['role']) ?>
                        </p>
                        
                        <p class="leader-description">
                            <?= $leader['bio'] ?>
                        </p>
                        
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</section>

<section class="stats">
    <div class="container">
        <h2 class="section-title" style="color: white; margin-bottom: 10px;">Our Impact</h2>
        
        <p class="section-subtitle" style="margin-bottom: 50px; color: rgba(255,255,255,0.9);"><?= $impactData['content_text'] ?? 'We are dedicated to tracking our progress and sharing our outcomes with transparent accountability.' ?></p>
        
        <div class="stats-grid">
            <div>
                <div class="stat-number">500+</div>
                <div class="stat-label">Youth Reached</div>
            </div>
            <div>
                <div class="stat-number">30+</div>
                <div class="stat-label">Communities</div>
            </div>
            <div>
                <div class="stat-number">50+</div>
                <div class="stat-label">Events</div>
            </div>
            <div>
                <div class="stat-number">10K+</div>
                <div class="stat-label">People Impacted</div>
            </div>
        </div>
    </div>
</section>

<section class="sdg-section">
    <div class="container">
        <h2 class="section-title">SDG Alignment</h2>
        <p class="section-subtitle">
            Our advocacy contributes to several United Nations Sustainable Development Goals.
        </p>
        <div class="sdg-grid">
            <div class="sdg-card">
                <h4>SDG 2</h4>
                <p>Zero Hunger</p>
            </div>
            <div class="sdg-card">
                <h4>SDG 3</h4>
                <p>Good Health & Well-Being</p>
            </div>
            <div class="sdg-card">
                <h4>SDG 12</h4>
                <p>Responsible Consumption</p>
            </div>
            <div class="sdg-card">
                <h4>SDG 13</h4>
                <p>Climate Action</p>
            </div>
        </div>
    </div>
</section>

<section id="contact" style="background-color: var(--light-gray);">
    <div class="container">
        <h2 class="section-title">Contact Us</h2>
        <p class="section-subtitle">
            Reach out for collaborations, educational engagements, partnerships, or inquiries.
        </p>
        <form action="process-contact.php" method="POST" style="max-width: 700px; margin: 0 auto;">
    <input type="text" name="name" placeholder="Full Name" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
    
    <input type="email" name="email" placeholder="Email Address" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
    
    <select name="subject" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; background-color: white;">
        <option value="General Inquiry">General Inquiry</option>
        <option value="Partnership">Partnership / Collaboration</option>
        <option value="Educational Engagement">Educational Engagement</option>
        <option value="Other">Other Inquiries</option>
    </select>
    
    <textarea name="message" placeholder="Your Message" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; min-height: 120px;"></textarea>
    
    <div style="text-align: center;">
        <button type="submit" class="btn btn-primary">Send Message</button>
    </div>
</form>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/main.js"></script>
<script src="js/counter.js"></script>

</body>
</html>