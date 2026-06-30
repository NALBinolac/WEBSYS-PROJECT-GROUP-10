<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar">

    <a href="index.php" class="logo-container">
        <img
            src="images/YJFS Organizational Logo.png"
            alt="Youth for Just Food Systems Logo"
            class="logo-img">
    </a>

    <ul class="nav-links">

        <li>
            <a href="index.php"
            <?php if($currentPage=="index.php") echo 'style="color:#2E7D32;"'; ?>>
                Home
            </a>
        </li>

        <li>
            <a href="about.php"
            <?php if($currentPage=="about.php") echo 'style="color:#2E7D32;"'; ?>>
                About Us
            </a>
        </li>

        <li>
            <a href="work.php"
            <?php if($currentPage=="work.php") echo 'style="color:#2E7D32;"'; ?>>
                Our Work
            </a>
        </li>

        <li>
            <a href="learning.php"
            <?php if($currentPage=="learning.php" || $currentPage=="resources.php") echo 'style="color:#2E7D32;"'; ?>>
                Learn
            </a>
        </li>

        <li>
            <a href="get-involved.php"
            <?php if($currentPage=="get-involved.php") echo 'style="color:#2E7D32;"'; ?>>
                Get Involved
            </a>
        </li>

        <li>
            <a href="support.php"
            <?php if($currentPage=="support.php") echo 'style="color:#2E7D32;"'; ?>>
                Support Us
            </a>
        </li>

        <li>
            <a href="events.php"
            <?php if($currentPage=="events.php") echo 'style="color:#2E7D32;"'; ?>>
                Events
            </a>
        </li>

        <li>
            <a href="about.php#contact">
                Contact
            </a>
        </li>

    </ul>

</nav>
