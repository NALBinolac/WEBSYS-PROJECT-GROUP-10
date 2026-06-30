<?php
/**
 * Volunteer Status Dashboard
 * 
 * Allows volunteers to check their application status by entering their email
 * No login required for privacy and ease of access
 */

include 'config.php';
include 'includes/navbar.php';

$volunteer_data = null;
$error_message = '';
$search_email = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $search_email = trim($_POST['email']);
    
    // Validate email format
    if (!filter_var($search_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address.";
    } else {
        // Search for volunteer in database
        $query = "SELECT id, fullname, email, activities, affiliation, status, created_at, status_updated_at, rejection_reason FROM volunteers WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $search_email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $volunteer_data = mysqli_fetch_assoc($result);
            mysqli_stmt_close($stmt);
            
            if (!$volunteer_data) {
                $error_message = "No application found for this email. Please check your email address or submit an application.";
            }
        } else {
            $error_message = "Error searching database. Please try again later.";
        }
    }
}

// Get status color and icon
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Approved':
            return ['class' => 'status-approved', 'icon' => '✓', 'text' => 'APPROVED'];
        case 'Rejected':
            return ['class' => 'status-rejected', 'icon' => '✗', 'text' => 'NOT APPROVED'];
        default:
            return ['class' => 'status-pending', 'icon' => '⏳', 'text' => 'PENDING REVIEW'];
    }
}

// Get status description
function getStatusDescription($status) {
    switch ($status) {
        case 'Approved':
            return "Congratulations! Your volunteer application has been approved. You are now part of our volunteer community. Check back soon for upcoming volunteer opportunities!";
        case 'Rejected':
            return "Thank you for your interest. Unfortunately, your application was not approved at this time. Feel free to reach out to us to explore other opportunities to support our mission.";
        default:
            return "Your volunteer application is currently under review. We will notify you via email as soon as we have an update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Status | Youth for Just Food Systems</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Dashboard Styles */
        .dashboard-hero {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }
        
        .dashboard-hero h1 {
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 700;
        }
        
        .dashboard-hero p {
            font-size: 1.1rem;
            opacity: 0.95;
        }
        
        .dashboard-container {
            max-width: 700px;
            margin: 60px auto;
            padding: 0 20px;
        }
        
        .search-card {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }
        
        .search-card h2 {
            color: #2e7d32;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #2e7d32;
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }
        
        .btn-search {
            width: 100%;
            padding: 12px;
            background-color: #2e7d32;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-search:hover {
            background-color: #1b5e20;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        
        .status-card {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-top: 30px;
        }
        
        .status-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 15px 30px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 1.1rem;
            margin: 15px 0;
            border: 2px solid;
        }
        
        .status-approved {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeeba;
        }
        
        .status-description {
            background-color: #f9f9f9;
            padding: 20px;
            border-left: 4px solid #2e7d32;
            border-radius: 4px;
            margin: 20px 0;
            line-height: 1.6;
        }
        
        .volunteer-info {
            margin: 30px 0;
        }
        
        .info-group {
            margin: 15px 0;
            padding: 15px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
        
        .info-group strong {
            color: #2e7d32;
            display: block;
            margin-bottom: 5px;
        }
        
        .info-group p {
            color: #555;
            margin: 0;
        }
        
        .rejection-reason {
            background-color: #fff3cd;
            padding: 15px;
            border-left: 4px solid #ffc107;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        .rejection-reason strong {
            color: #856404;
            display: block;
            margin-bottom: 5px;
        }
        
        .timeline {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
        
        .timeline-item {
            display: flex;
            margin-bottom: 15px;
        }
        
        .timeline-marker {
            width: 40px;
            height: 40px;
            background-color: #2e7d32;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .timeline-content {
            flex: 1;
        }
        
        .timeline-date {
            font-size: 0.9rem;
            color: #666;
        }
        
        .timeline-text {
            font-weight: 600;
            color: #333;
            margin: 5px 0;
        }
        
        .action-buttons {
            text-align: center;
            margin-top: 30px;
        }
        
        .action-buttons a {
            display: inline-block;
            padding: 12px 30px;
            background-color: #2e7d32;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 0 10px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .action-buttons a:hover {
            background-color: #1b5e20;
        }
        
        .contact-section {
            background-color: #e8f5e9;
            padding: 20px;
            border-radius: 4px;
            margin-top: 30px;
            border-left: 4px solid #2e7d32;
        }
        
        .contact-section h4 {
            color: #2e7d32;
            margin-top: 0;
        }
        
        .contact-section a {
            color: #2e7d32;
            text-decoration: none;
            font-weight: 600;
        }
        
        .contact-section a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Hero Section -->
<section class="dashboard-hero">
    <h1>Check Your Volunteer Application Status</h1>
    <p>Enter your email to see the status of your volunteer application</p>
</section>

<div class="dashboard-container">
    <?php if (!$volunteer_data): ?>
        <!-- Search Form -->
        <div class="search-card">
            <h2>Find Your Application</h2>
            
            <?php if ($error_message): ?>
                <div class="error-message">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Enter the email you used to apply" 
                        value="<?php echo htmlspecialchars($search_email); ?>"
                        required>
                </div>
                <button type="submit" class="btn-search">Search</button>
            </form>
            
            <div class="contact-section" style="margin-top: 40px;">
                <h4>Didn't Find Your Application?</h4>
                <p>If you just submitted your application, it may take a few minutes to appear in our system. Please try again later.</p>
                <p>Still having issues? <a href="mailto:volunteer@youthforjustfoodsystems.org">Contact us directly</a></p>
            </div>
        </div>
    <?php else: ?>
        <!-- Status Display -->
        <div class="status-card">
            <div class="status-header">
                <h2>Application Status</h2>
                <?php 
                    $badge_info = getStatusBadgeClass($volunteer_data['status']);
                    $status_desc = getStatusDescription($volunteer_data['status']);
                ?>
                <div class="status-badge <?php echo $badge_info['class']; ?>">
                    <?php echo $badge_info['icon']; ?> <?php echo $badge_info['text']; ?>
                </div>
            </div>
            
            <div class="status-description">
                <?php echo htmlspecialchars($status_desc); ?>
            </div>
            
            <!-- Volunteer Information -->
            <div class="volunteer-info">
                <h3 style="color: #2e7d32; margin-bottom: 15px;">Your Application Details</h3>
                
                <div class="info-group">
                    <strong>Name</strong>
                    <p><?php echo htmlspecialchars($volunteer_data['fullname']); ?></p>
                </div>
                
                <div class="info-group">
                    <strong>Email</strong>
                    <p><?php echo htmlspecialchars($volunteer_data['email']); ?></p>
                </div>
                
                <?php if ($volunteer_data['affiliation']): ?>
                <div class="info-group">
                    <strong>Affiliation</strong>
                    <p><?php echo htmlspecialchars($volunteer_data['affiliation']); ?></p>
                </div>
                <?php endif; ?>
                
                <?php if ($volunteer_data['activities']): ?>
                <div class="info-group">
                    <strong>Interested Activities</strong>
                    <p><?php echo htmlspecialchars($volunteer_data['activities']); ?></p>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Timeline -->
            <div class="timeline">
                <h3 style="color: #2e7d32; margin-top: 0; margin-bottom: 20px;">Application Timeline</h3>
                
                <div class="timeline-item">
                    <div class="timeline-marker">1</div>
                    <div class="timeline-content">
                        <div class="timeline-text">Application Submitted</div>
                        <div class="timeline-date"><?php echo date('F d, Y \a\t g:i A', strtotime($volunteer_data['created_at'])); ?></div>
                    </div>
                </div>
                
                <?php if ($volunteer_data['status'] !== 'Pending'): ?>
                <div class="timeline-item">
                    <div class="timeline-marker">2</div>
                    <div class="timeline-content">
                        <div class="timeline-text">Status Updated</div>
                        <div class="timeline-date"><?php echo date('F d, Y \a\t g:i A', strtotime($volunteer_data['status_updated_at'])); ?></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Rejection Reason (if applicable) -->
            <?php if ($volunteer_data['status'] === 'Rejected' && $volunteer_data['rejection_reason']): ?>
            <div class="rejection-reason">
                <strong>Feedback from Our Team</strong>
                <p><?php echo htmlspecialchars($volunteer_data['rejection_reason']); ?></p>
            </div>
            <?php endif; ?>
            
            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="get-involved.php">Back to Get Involved</a>
                <a href="javascript:void(0);" onclick="window.print(); return false;">Print This Page</a>
            </div>
            
            <!-- Contact Support -->
            <div class="contact-section">
                <h4>Have Questions?</h4>
                <p>If you have any questions about your application or would like more information about volunteer opportunities, please reach out to us at:</p>
                <p>
                    <strong>Email:</strong> <a href="mailto:volunteer@youthforjustfoodsystems.org">volunteer@youthforjustfoodsystems.org</a><br>
                    <strong>Phone:</strong> +63 (XXX) XXX-XXXX
                </p>
            </div>
            
            <!-- Back to Search -->
            <div style="text-align: center; margin-top: 30px;">
                <form method="POST" action="" style="display: inline;">
                    <button type="submit" class="btn-search" style="width: auto; padding: 10px 20px;">Search Another Email</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>