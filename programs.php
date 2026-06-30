<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Programs | Youth for Just Food Systems
    </title>

    <link rel="stylesheet"
          href="css/style.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap"
          rel="stylesheet">

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO -->

<section class="hero">

    <div class="hero-content">

        <h1>
            Our Programs
        </h1>

        <p>

            Empowering youth, transforming food systems,
            and advancing sustainability through education,
            partnerships, and community action.

        </p>

    </div>

</section>

<!-- SEARCH & FILTER -->

<section>

    <div class="container">

        <h2 class="section-title">
            Explore Our Programs
        </h2>

        <p class="section-subtitle">

            Learn about the initiatives that help build
            youth-powered just food systems.

        </p>

        <div class="search-filter">

            <div class="search-box">

                <input
                    type="text"
                    id="programSearch"
                    placeholder="Search programs...">

            </div>

            <select
                id="programFilter"
                class="filter-select">

                <option value="all">
                    All Categories
                </option>

                <option value="dining">
                    Sustainable Dining
                </option>

                <option value="festival">
                    Festivals
                </option>

                <option value="canteen">
                    Canteens
                </option>

                <option value="training">
                    Capacity Building
                </option>

                <option value="curriculum">
                    Curriculum Development
                </option>

                <option value="education">
                    Educational Engagements
                </option>

            </select>

        </div>

    </div>

</section>

<!-- PROGRAM CARDS -->

<section>

    <div class="container">

        <div class="card-container"
             id="programContainer">

            <!-- PROGRAM 1 -->

            <div class="card program-item"
                 data-category="dining">

                <h3>
                    Sustainable Dining Initiatives for University Events
                </h3>

                <p>

                    Supporting universities and organizations
                    in implementing sustainable dining practices
                    during events and gatherings.

                </p>

            </div>

            <!-- PROGRAM 2 -->

            <div class="card program-item"
                 data-category="festival">

                <h3>
                    Annual Plant-Based Festivals
                </h3>

                <p>

                    Bringing together students,
                    advocates, chefs, and communities
                    to celebrate sustainable and
                    plant-based food systems.

                </p>

            </div>

            <!-- PROGRAM 3 -->

            <div class="card program-item"
                 data-category="canteen">

                <h3>
                    Plant-Forward Canteens
                </h3>

                <p>

                    Promoting healthier and more
                    sustainable food choices in
                    educational institutions and
                    community spaces.

                </p>

            </div>

            <!-- PROGRAM 4 -->

            <div class="card program-item"
                 data-category="training">

                <h3>
                    Capacity-Building on Plant-Based Food Preparation
                </h3>

                <p>

                    Equipping students, chefs,
                    concessionaires, and food workers
                    with practical plant-based food
                    preparation skills.

                </p>

            </div>

            <!-- PROGRAM 5 -->

            <div class="card program-item"
                 data-category="curriculum">

                <h3>
                    Curriculum Development on Plant-Based Nutrition,
                    Dietetics, and Food Technology
                </h3>

                <p>

                    Supporting educational institutions
                    in integrating sustainability and
                    plant-based approaches into learning.

                </p>

            </div>

            <!-- PROGRAM 6 -->

            <div class="card program-item"
                 data-category="education">

                <h3>
                    Educational Engagements on Food Systems,
                    Sustainability, and Farmed Animal Welfare
                </h3>

                <p>

                    Conducting workshops, discussions,
                    trainings, and awareness campaigns
                    focused on food justice and sustainability.

                </p>

            </div>

        </div>

    </div>

</section>

<!-- IMPACT SECTION -->

<section class="stats">

    <div class="container">

        <div class="stats-grid">

            <div>

                <div class="stat-number">
                    6
                </div>

                <div class="stat-label">
                    Core Programs
                </div>

            </div>

            <div>

                <div class="stat-number">
                    50+
                </div>

                <div class="stat-label">
                    Educational Events
                </div>

            </div>

            <div>

                <div class="stat-number">
                    500+
                </div>

                <div class="stat-label">
                    Youth Participants
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

        </div>

    </div>

</section>

<!-- CTA -->

<section class="cta">

    <div class="container">

        <h2>
            Get Involved
        </h2>

        <p>

            Join us in creating food systems that
            liberate communities, animals,
            and ecosystems.

        </p>

        <br>

        <a href="about.php"
           class="btn btn-primary">

           Learn More

        </a>

        <a href="news.php"
           class="btn btn-secondary">

           View News

        </a>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<!-- SEARCH + FILTER SCRIPT -->

<script>

const searchInput =
document.getElementById("programSearch");

const filterSelect =
document.getElementById("programFilter");

function filterPrograms(){

    const searchText =
    searchInput.value.toLowerCase();

    const category =
    filterSelect.value;

    const cards =
    document.querySelectorAll(".program-item");

    cards.forEach(card => {

        const text =
        card.textContent.toLowerCase();

        const cardCategory =
        card.dataset.category;

        const searchMatch =
        text.includes(searchText);

        const categoryMatch =
        category === "all" ||
        cardCategory === category;

        if(searchMatch && categoryMatch){

            card.style.display = "block";

        }else{

            card.style.display = "none";

        }

    });

}

searchInput.addEventListener(
"keyup",
filterPrograms
);

filterSelect.addEventListener(
"change",
filterPrograms
);

</script>

</body>
</html>