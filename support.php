<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Us | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<?php
if(isset($_GET['success']) && $_GET['success'] == 'donation'){
    echo "
    <div style='background:#d4edda;
                color:#155724;
                padding:15px;
                text-align:center;'>

        Donation logged successfully!

    </div>";
}
?>

<section class="hero support-hero">
    <div class="hero-content">
        <h1>Support Our Movement</h1>
        <p>
            Fuel transformative systems change. Your contributions directly support educational discussions, 
            institutional dining shifts, and animal liberation advocacy.
        </p>
    </div>
</section>

<section style="padding: 60px 0;">
    <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 40px; align-items: start;">
        
        <div>
            <h2 style="color: #2E7D32; margin-bottom: 20px;">Ways to Give</h2>
            <p style="color: #555; line-height: 1.6; margin-bottom: 25px;">
                We are transitioning from completely manual processes to a centralized tracking system to ensure 
                radical transparency and accountability. Choose your preferred digital payment channel below:
            </p>

            <div class="card" style="margin-bottom: 20px; padding: 20px; border-left: 5px solid #2E7D32;">
                <h4 style="margin-bottom: 5px; color: #333;">Option 1: GCash / Mobile Wallet</h4>
                <p style="font-size: 0.95rem; color: #666;">
                    <strong>Account Name:</strong> Youth for Just Food Systems Inc.<br>
                    <strong>Account Number:</strong> 0917-XXXX-XXX
                </p>
            </div>

            <div class="card" style="margin-bottom: 20px; padding: 20px; border-left: 5px solid #2E7D32;">
                <h4 style="margin-bottom: 5px; color: #333;">Option 2: Direct Bank Transfer</h4>
                <p style="font-size: 0.95rem; color: #666;">
                    <strong>Bank Name:</strong> BPI (Bank of the Philippine Islands)<br>
                    <strong>Account Name:</strong> Youth for Just Food Systems<br>
                    <strong>Account Number:</strong> XXXX-XXXX-XX
                </p>
            </div>

            <p style="font-size: 0.85rem; color: #777; font-style: italic;">
                *After making a transfer, please use the form on the right to log your pledge or transaction details so our admin team can verify and issue an official receipt record.
            </p>
        </div>

        <div class="card" style="padding: 40px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
            <h3 style="color: #2E7D32; margin-bottom: 15px; font-size: 1.4rem;">Log Your Donation</h3>
            
            <form action="process-support.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                <input type="hidden" name="support_type" value="donation">
                
                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Full Name / Organization</label>
                    <input type="text" name="donor_name" placeholder="Anonymous or your name" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Email Address</label>
                    <input type="email" name="email" placeholder="donor@email.com" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Amount (PHP)</label>
                        <input type="number" name="amount" placeholder="500" required min="1" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Payment Channel</label>
                        <select name="payment_channel" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; background-color: white;">
                            <option value="GCash">GCash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Other">Other Channel</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Donation Purpose</label>
                    <select name="purpose" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; background-color: white;">
                        <option value="General Fund">General Operations Fund</option>
                        <option value="Capacity Building">Capacity-Building & Workshops</option>
                        <option value="Plant-Based Festivals">Annual Plant-Based Festivals</option>
                        <option value="Educational Resources">Resource & Material Development</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 5px; color: #333;">Proof of Payment (Screenshot)</label>
                    <input type="file" name="proof_of_payment" accept="image/*" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-family: inherit; background-color: white;">
                    <small style="color: #666; font-size: 0.75rem; display: block; margin-top: 4px;">Tumatanggap lamang ng larawan (JPG, JPEG, PNG).</small>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px;">Log Donation Record</button>
            </form>
        </div>

    </div>
</section>

<section style="background-color: #f9f9f9; padding: 60px 0;">
    <div class="container">
        <h2 class="section-title" style="text-align: center;">Other Ways to Support</h2>
        <p class="section-subtitle" style="text-align: center; margin-bottom: 40px;">
            Giving isn't limited to financial assistance. Explore these alternative partnership avenues:
        </p>

        <div class="card-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px;">
            
            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; text-align: center; padding: 25px;">
                <div>
                    <h3 style="color: #2E7D32; margin-bottom: 10px;">Partner with Us</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                        Are you a university administrator, merchant, or chef? Bring our sustainable programs directly into your canteens or institutional dining spaces.
                    </p>
                </div>
                <a href="about.php#contact" class="btn btn-secondary" style="margin-top: 20px; font-size: 0.85rem;">Inquire Partnerships</a>
            </div>

            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; text-align: center; padding: 25px;">
                <div>
                    <h3 style="color: #2E7D32; margin-bottom: 10px;">Advisory Committee</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                        Lend professional expertise, curriculum oversight, or structural strategic guidance to expand our organizational scope.
                    </p>
                </div>
                <a href="about.php#contact" class="btn btn-secondary" style="margin-top: 20px; font-size: 0.85rem;">Express Interest</a>
            </div>

            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; text-align: center; padding: 25px;">
                <div>
                    <h3 style="color: #2E7D32; margin-bottom: 10px;">Nominate a Youth</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                        Know an outstanding young student or advocate working on food tech, nutrition, or animal systems? Flag them to join our network.
                    </p>
                </div>
                <a href="about.php#contact" class="btn btn-secondary" style="margin-top: 20px; font-size: 0.85rem;">Submit Nomination</a>
            </div>

            <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; text-align: center; padding: 25px;">
                <div>
                    <h3 style="color: #2E7D32; margin-bottom: 10px;">Our Shop</h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                        Purchase organizational merchandise, advocacy zines, or plant-based recipe collections. All net proceeds go straight to campaigns.
                    </p>
                </div>
                <button class="btn btn-secondary" style="margin-top: 20px; font-size: 0.85rem;" disabled>Coming Soon</button>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="js/main.js"></script>
<script src="js/counter.js"></script>

</body>
</html>