<?php
require_once 'config.php';

$query = "SELECT * FROM events ORDER BY date ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events & Campaigns | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="hero-content">
        <h1>Events & Campaigns</h1>
        <p>
            Join our upcoming activations, immersive trainings, and public festivals. 
            Step out of the classroom and directly into active food justice community spaces.
        </p>
    </div>
</section>

<!-- MAIN LISTINGS & MINI CALENDAR INTERFACE -->
<section style="padding: 60px 0;">
    <div class="container" style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
        
        <!-- LEFT: UPCOMING EVENTS LISTINGS -->
        <div>
            <h2 style="color: #2E7D32; margin-bottom: 25px;">Upcoming Activities</h2>

           <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($event = mysqli_fetch_assoc($result)): 
                    $eventTimestamp = strtotime($event['date']);
                    $eventDay = date('d', $eventTimestamp);
                    $eventMonth = date('M', $eventTimestamp);
                    
                    // Simple logic to change the tag color based on category
                    $catColor = "#E65100"; $catBg = "#FFF3E0"; // Default Orange (Festival)
                    if ($event['category'] == 'Workshop') { $catColor = "#2E7D32"; $catBg = "#E8F5E9"; } // Green
                    if ($event['category'] == 'Discussion') { $catColor = "#0288D1"; $catBg = "#E1F5FE"; } // Blue
                ?>
                <div class="card" style="display: flex; gap: 20px; padding: 25px; margin-bottom: 25px; align-items: flex-start; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-radius: 12px; background: white;">
                    
                    <div style="background-color: #E8F5E9; color: #2E7D32; padding: 15px; border-radius: 8px; text-align: center; min-width: 80px; margin-top: 30px;">
                        <span style="display: block; font-size: 1.5rem; font-weight: 700; line-height: 1;"><?= $eventDay ?></span>
                        <span style="font-size: 0.8rem; text-transform: uppercase; font-weight: 600;"><?= $eventMonth ?></span>
                    </div>
                    
                    <div style="flex-grow: 1;">
                        <span style="background-color: <?= $catBg ?>; color: <?= $catColor ?>; padding: 4px 10px; font-size: 0.75rem; font-weight: 700; border-radius: 4px; text-transform: uppercase; display: inline-block; margin-bottom: 10px;">
                            <?= htmlspecialchars($event['category'] ?? 'Event') ?>
                        </span>
                        
                        <h3 style="margin: 0 0 10px 0; color: #333; font-size: 1.4rem;"><?= htmlspecialchars($event['title']) ?></h3>
                        
                        <p style="font-size: 0.9rem; color: #555; margin-bottom: 15px;">
                            <strong>Time:</strong> <?= htmlspecialchars($event['time_range'] ?? 'TBA') ?> | <strong>Venue:</strong> <?= htmlspecialchars($event['venue']) ?>
                        </p>
                        
                        <p style="font-size: 0.95rem; color: #666; line-height: 1.6; margin: 0;">
                            <?= $event['description'] ?? 'No description provided.' ?>
                        </p>
                    </div>
                    
                    <div style="align-self: center; margin-left: 10px;">
                        <a href="get-involved.php" class="btn btn-primary" style="font-size: 0.95rem; font-weight: 600; padding: 12px 25px; border-radius: 30px; white-space: nowrap; background-color: #FDD835; color: #333; box-shadow: none; border: none;">Register</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div style="background: #f8f9fa; padding: 30px; text-align: center; border-radius: 8px; color: #666;">
                    <p>No upcoming events at the moment. Please check back later!</p>
                </div>
            <?php endif; ?>

        </div>

        <!-- RIGHT: CALENDAR MAP / ANNOUNCEMENT COLUMN -->
        <div class="card" style="padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background-color: #fBFbFB;">
            <h3 style="color: #2E7D32; margin-bottom: 15px; font-size: 1.2rem; text-align: center;">Calendar Matrix</h3>
            
            <div id="calendar"></div>

            <hr style="border: 0; border-top: 1px solid #ddd; margin: 25px 0;">

            <h4 style="color: #333; margin-bottom: 10px; font-size: 0.95rem;">Important Reminders</h4>
            <ul style="padding-left: 20px; font-size: 0.85rem; color: #666; line-height: 1.6;">
                <li style="margin-bottom: 8px;">All institutional events require presentation of student/faculty IDs upon entry.</li>
                <li style="margin-bottom: 8px;">Digital certificates will be generated via your Learning Hub accounts after completion of educational discussion check-ins.</li>
            </ul>
        </div>

    </div>
</section>

<!-- 1. Include the Footer structure -->
    <?php include 'includes/footer.php'; ?>

    <!-- 2. Core Calendar Engine Library (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>

    <!-- 3. Your Custom script link from your JS folder -->
    <script src="js/calendar.js"></script>

</body>
</html>