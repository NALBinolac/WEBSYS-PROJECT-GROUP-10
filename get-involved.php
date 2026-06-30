<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Involved | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<?php

if(isset($_GET['success'])){

    if($_GET['success'] == 'member'){
        echo "
        <div style='background:#d4edda;
                    color:#155724;
                    padding:15px;
                    text-align:center;'>

            Membership application submitted successfully!

        </div>";
    }

    if($_GET['success'] == 'volunteer'){
        echo "
        <div style='background:#d4edda;
                    color:#155724;
                    padding:15px;
                    text-align:center;'>

            Volunteer registration submitted successfully!

        </div>";
    }
}

if(isset($_GET['error']) && $_GET['error'] == 'no_activity'){
    echo "
    <div style='background:#f8d7da;
                color:#721c24;
                padding:15px;
                text-align:center;'>

        Please select at least one activity before registering as a volunteer.

    </div>";
}
?>

<!-- HERO SECTION -->
<section class="hero get-involved-hero">
    <div class="hero-content">
        <h1>Get Involved</h1>
        <p>
            Be the structural change. Join a growing youth movement dedicating energy, 
            skills, and voices toward liberating communities, animals, and ecosystems.
        </p>
    </div>
</section>

<!-- SELECTION INTRO -->
<section style="padding: 60px 0 20px 0;">
    <div class="container" style="text-align: center;">
        <h2 class="section-title">Choose Your Path to Action</h2>
        <p class="section-subtitle" style="max-width: 650px; margin: 0 auto;">
            Whether you want to drive long-term structural changes as an official member, or 
            lend a hand at specific local events, there is a space here for you.
        </p>
    </div>
</section>

<!-- MAIN FORMS CONTAINER -->
<section style="padding-bottom: 80px;">
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 40px; align-items: stretch;">
        
        <!-- MEMBER APPLICATION FORM -->
        <div class="card" style="padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
            <h3 style="color: #2E7D32; margin-bottom: 10px; font-size: 1.5rem;">Become a Member</h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 25px; line-height: 1.5;">
                Apply for official membership to take on structural organizational roles, run local chapters, 
                and lead ongoing campaigns. Ideal for long-term advocates.
            </p>
            
            <form action="process-engagement.php" method="POST" style="display: flex; flex-direction: column; gap: 15px; flex-grow: 1;">
                <input type="hidden" name="form_type" value="member">
                
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter your full name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Email Address</label>
                    <input type="email" name="email" placeholder="username@email.com" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Primary Area of Interest</label>
                    <select name="core_interest" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; background-color: white;">
                        <option value="" disabled selected>Select an core initiative</option>
                        <option value="Sustainable Dining">Sustainable Dining Initiatives</option>
                        <option value="Plant-Based Festivals">Annual Plant-Based Festivals</option>
                        <option value="Plant-Forward Canteens">Plant-Forward Canteens</option>
                        <option value="Curriculum & Education">Curriculum Development & Tech</option>
                    </select>
                </div>

                <div style="flex-grow: 1; display: flex; flex-direction: column;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Why do you want to join our membership framework?</label>
                    <textarea name="motivation" placeholder="Share your motivation or background context..." required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; min-height: 100px; flex-grow: 1; resize: vertical;"></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Submit Membership Application</button>
            </form>
        </div>

        <!-- VOLUNTEER AND REGISTRATION FORM -->
        <div class="card" style="padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; flex-direction: column;">
            <h3 style="color: #2E7D32; margin-bottom: 10px; font-size: 1.5rem;">Volunteer & Event Sign-Up</h3>
            <p style="font-size: 0.9rem; color: #666; margin-bottom: 25px; line-height: 1.5;">
                Register to help out during upcoming activations, local trainings, plant-based festivals, 
                or individual community workshops based on your availability.
            </p>
            
            <form id="volunteer-form" action="process-engagement.php" method="POST" style="display: flex; flex-direction: column; gap: 15px; flex-grow: 1;">
                <input type="hidden" name="form_type" value="volunteer">
                
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter your full name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Email Address</label>
                    <input type="email" name="email" placeholder="username@email.com" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                </div>

                <div style="flex-grow: 1; display: flex; flex-direction: column;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 8px; color: #333;">Select Activities You Are Available For:</label>
                    <span id="activities-error" style="display: none; color: #c62828; font-size: 0.85rem; margin-bottom: 8px;">Please select at least one activity.</span>

                    <div id="activities-group" style="display: flex; flex-direction: column; gap: 10px; flex-grow: 1;">
                        <label style="display: flex; align-items: flex-start; gap: 12px; font-weight: normal; font-size: 0.95rem; color: #444; line-height: 1.4; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: background-color 0.2s, border-color 0.2s;">
                            <input type="checkbox" name="activities[]" value="Festival Support" style="width: 17px; height: 17px; margin: 2px 0 0 0; flex-shrink: 0; accent-color: #2E7D32; cursor: pointer;">
                            <span>Plant-Based Festival Event Support</span>
                        </label>
                        <label style="display: flex; align-items: flex-start; gap: 12px; font-weight: normal; font-size: 0.95rem; color: #444; line-height: 1.4; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: background-color 0.2s, border-color 0.2s;">
                            <input type="checkbox" name="activities[]" value="Kitchen Capacity" style="width: 17px; height: 17px; margin: 2px 0 0 0; flex-shrink: 0; accent-color: #2E7D32; cursor: pointer;">
                            <span>Culinary Capacity-Building Workshops</span>
                        </label>
                        <label style="display: flex; align-items: flex-start; gap: 12px; font-weight: normal; font-size: 0.95rem; color: #444; line-height: 1.4; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: background-color 0.2s, border-color 0.2s;">
                            <input type="checkbox" name="activities[]" value="Campaign Mobile" style="width: 17px; height: 17px; margin: 2px 0 0 0; flex-shrink: 0; accent-color: #2E7D32; cursor: pointer;">
                            <span>Joining Local Educational Campaigns</span>
                        </label>
                        <label style="display: flex; align-items: flex-start; gap: 12px; font-weight: normal; font-size: 0.95rem; color: #444; line-height: 1.4; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: background-color 0.2s, border-color 0.2s;">
                            <input type="checkbox" name="activities[]" value="Research Assist" style="width: 17px; height: 17px; margin: 2px 0 0 0; flex-shrink: 0; accent-color: #2E7D32; cursor: pointer;">
                            <span>Resource & Policy Research Support</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Your Affiliation / Target Group</label>
                    <select name="affiliation" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; background-color: white;">
                        <option value="" disabled selected>Identify your demographic group</option>
                        <option value="Student / Youth">Student / Youth Advocate</option>
                        <option value="Out of School Youth">Out of School Youth</option>
                        <option value="Faculty / Admin">University Faculty, Staff, or Admin</option>
                        <option value="Concessionaire / Chef">Chef, Concessionaire, or Kitchen Personnel</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Register as a Volunteer</button>
            </form>
        </div>

        <script>
        // Highlight the option box when its checkbox is checked/hovered
        document.querySelectorAll('#activities-group label').forEach(function (lbl) {
            var box = lbl.querySelector('input[type="checkbox"]');
            function refresh() {
                if (box.checked) {
                    lbl.style.borderColor = '#2E7D32';
                    lbl.style.backgroundColor = '#f1f8f1';
                } else {
                    lbl.style.borderColor = '#ddd';
                    lbl.style.backgroundColor = 'transparent';
                }
            }
            box.addEventListener('change', refresh);
            refresh();
        });

        document.getElementById('volunteer-form').addEventListener('submit', function (e) {
            var checked = document.querySelectorAll('#activities-group input[name="activities[]"]:checked');
            var errorEl = document.getElementById('activities-error');

            if (checked.length === 0) {
                e.preventDefault();
                errorEl.style.display = 'block';
                errorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                errorEl.style.display = 'none';
            }
        });
        </script>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="js/main.js"></script>
    
    <script src="js/counter.js"></script>


</body>
</html>