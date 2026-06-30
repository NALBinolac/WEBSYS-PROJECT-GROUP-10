<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources Hub | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO SECTION -->
<section class="hero resources-hero">
    <div class="hero-content">
        <h1>Resources Hub</h1>
        <p>
            Access open-source toolkits, community guides, and educational materials 
            designed to empower your food justice advocacy.
        </p>
    </div>
</section>

<!-- RESOURCE CATEGORIES & SEARCH -->
<section style="padding: 40px 0 20px 0;">
    <div class="container">
        <h2 class="section-title">Browse Our Library</h2>
        <p class="section-subtitle">
            Filter by topic or download directly to share with your local community.
        </p>
    </div>
</section>

<!-- MAIN RESOURCES GRID -->
<section style="padding-bottom: 60px;">
    <div class="container">
        <div class="card-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
            
            <!-- Resource Item 1 -->
            <div class="card resource-card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                <div>
                    <span style="background-color: #E8F5E9; color: #2E7D32; padding: 5px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 4px; text-transform: uppercase;">Toolkit</span>
                    <h3 style="margin: 15px 0 10px 0;">Community Organizing for Food Justice</h3>
                    <p style="font-size: 0.9rem; color: #555; line-height: 1.5;">
                        A step-by-step guide for local youth leaders on how to map food systems, form alliances, and mobilize neighborhoods around food sovereignty initiatives.
                    </p>
                </div>
                <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                    <a href="downloads/community-organizing-guide.pdf" class="btn btn-secondary" style="font-size: 0.85rem;" download>Download PDF</a>
                </div>
            </div>

            <!-- Resource Item 2 -->
            <div class="card resource-card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                <div>
                    <span style="background-color: #E8F5E9; color: #2E7D32; padding: 5px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 4px; text-transform: uppercase;">Zine / Primer</span>
                    <h3 style="margin: 15px 0 10px 0;">Farmed Animals & Climate Systems</h3>
                    <p style="font-size: 0.9rem; color: #555; line-height: 1.5;">
                        An illustrated, easily digestible educational zine mapping the profound connections between industrial farming practices, local ecosystems, and global climate urgency.
                    </p>
                </div>
                <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                    <a href="downloads/farmed-animals-climate-zine.pdf" class="btn btn-secondary" style="font-size: 0.85rem;" download>Download Zine</a>
                </div>
            </div>

            <!-- Resource Item 3 -->
            <div class="card resource-card" style="display: flex; flex-direction: column; justify-content: space-between; height: 100%;">
                <div>
                    <span style="background-color: #E8F5E9; color: #2E7D32; padding: 5px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 4px; text-transform: uppercase;">Policy Paper</span>
                    <h3 style="margin: 15px 0 10px 0;">Intersectional Liberation in Food Security</h3>
                    <p style="font-size: 0.9rem; color: #555; line-height: 1.5;">
                        A policy framework brief exploring structural barriers to nutritious foods and showcasing how community-led agroecological models dismantle systemic inequalities.
                    </p>
                </div>
                <div style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 15px;">
                    <a href="downloads/intersectional-food-policy.pdf" class="btn btn-secondary" style="font-size: 0.85rem;" download>Read Brief</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- CONTRIBUTIONS CALLOUT -->
<section style="background-color: #E8F5E9; padding: 60px 0; text-align: center;">
    <div class="container">
        <h2 style="color: #2E7D32; margin-bottom: 15px;">Have Resources to Share?</h2>
        <p style="max-width: 600px; margin: 0 auto 25px auto; color: #333;">
            We are always looking to expand our repository with research papers, toolkits, translations, and art pieces that center animal, human, and ecological liberation.
        </p>
        <a href="about.php#contact" class="btn btn-primary" style="background-color: #2E7D32;">Submit a Resource</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>