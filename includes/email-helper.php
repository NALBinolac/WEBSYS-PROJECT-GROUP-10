<?php
/**
 * Email Helper Functions for Volunteer Notification System
 * 
 * This file contains functions to send email notifications to volunteers
 * when their application status changes (Approved, Rejected, Pending)
 */

/**
 * Send volunteer status update email
 * 
 * @param string $volunteer_email - Volunteer's email address
 * @param string $volunteer_name - Volunteer's full name
 * @param string $new_status - New status (Approved, Rejected, Pending)
 * @param string $rejection_reason - Reason for rejection (optional)
 * @param string $admin_notes - Additional notes from admin (optional)
 * @return bool - True if email sent successfully, false otherwise
 */
function sendVolunteerStatusEmail($volunteer_email, $volunteer_name, $new_status, $rejection_reason = '', $admin_notes = '') {
    
    // Your NGO's email address (change this to your organization email)
    $from_email = "noreply@youthforjustfoodsystems.org";
    $from_name = "Youth for Just Food Systems";
    
    // Email subject based on status
    $subject = "Update on Your Volunteer Application";
    
    // Generate email content based on status
    $email_body = generateEmailBody($volunteer_name, $new_status, $rejection_reason, $admin_notes);
    
    // Email headers
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . $from_name . " <" . $from_email . ">" . "\r\n";
    $headers .= "Reply-To: " . $from_email . "\r\n";
    
    // Send email
    $mail_sent = mail($volunteer_email, $subject, $email_body, $headers);
    
    return $mail_sent;
}

/**
 * Generate HTML email body based on volunteer status
 * 
 * @param string $volunteer_name - Volunteer's name
 * @param string $status - Volunteer status
 * @param string $rejection_reason - Reason for rejection
 * @param string $admin_notes - Admin's additional notes
 * @return string - HTML email content
 */
function generateEmailBody($volunteer_name, $status, $rejection_reason = '', $admin_notes = '') {
    
    $dashboard_url = "https://yourwebsite.com/volunteer-dashboard.php";
    $contact_email = "volunteer@youthforjustfoodsystems.org";
    $current_year = date('Y');
    
    // Start of HTML email
    $html = "<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
            .email-wrapper { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            .header { border-bottom: 3px solid #2e7d32; padding-bottom: 20px; margin-bottom: 20px; }
            .logo-text { color: #2e7d32; font-size: 24px; font-weight: bold; }
            .greeting { font-size: 18px; color: #333; margin: 20px 0; }
            .status-badge { 
                display: inline-block; 
                padding: 10px 20px; 
                border-radius: 4px; 
                font-weight: bold; 
                margin: 15px 0;
                font-size: 16px;
            }
            .status-approved { background-color: #d4edda; color: #155724; border: 2px solid #c3e6cb; }
            .status-rejected { background-color: #f8d7da; color: #721c24; border: 2px solid #f5c6cb; }
            .status-pending { background-color: #fff3cd; color: #856404; border: 2px solid #ffeeba; }
            .content { margin: 20px 0; }
            .message { 
                background-color: #f5f5f5; 
                padding: 15px; 
                border-left: 4px solid #2e7d32; 
                margin: 15px 0; 
                border-radius: 4px;
            }
            .action-button { 
                background-color: #2e7d32; 
                color: white; 
                padding: 12px 30px; 
                text-decoration: none; 
                border-radius: 4px; 
                display: inline-block; 
                margin: 20px 0;
                font-weight: bold;
            }
            .action-button:hover { background-color: #1b5e20; }
            .footer { 
                border-top: 1px solid #ddd; 
                padding-top: 20px; 
                margin-top: 30px; 
                font-size: 12px; 
                color: #666; 
            }
            .footer a { color: #2e7d32; text-decoration: none; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='email-wrapper'>
                <!-- Header -->
                <div class='header'>
                    <div class='logo-text'>🌱 Youth for Just Food Systems</div>
                </div>
                
                <!-- Greeting -->
                <div class='greeting'>
                    Hello <strong>" . htmlspecialchars($volunteer_name) . "</strong>,
                </div>
                
                <!-- Main Message -->
                <div class='content'>";
    
    // Status-specific message
    if ($status === 'Approved') {
        $html .= "
                    <p>Great news! 🎉</p>
                    <p>We are excited to inform you that your volunteer application has been <strong>APPROVED</strong>!</p>
                    
                    <div class='status-badge status-approved'>
                        ✓ APPLICATION APPROVED
                    </div>
                    
                    <div class='message'>
                        <strong>Next Steps:</strong>
                        <ul>
                            <li>Log in to your volunteer dashboard to view available opportunities</li>
                            <li>Check the event calendar for upcoming activities you can join</li>
                            <li>We'll send you details about orientation and first assignments</li>
                        </ul>
                    </div>
                    
                    <p>You are now part of the Youth for Just Food Systems volunteer community! We look forward to working with you in building just food systems.</p>
                    
                    <a href='" . $dashboard_url . "' class='action-button'>View Your Dashboard</a>";
        
    } elseif ($status === 'Rejected') {
        $html .= "
                    <p>Thank you for your interest in volunteering with us.</p>
                    
                    <div class='status-badge status-rejected'>
                        ✗ APPLICATION NOT APPROVED
                    </div>
                    
                    <div class='message'>
                        <strong>Status Update:</strong>
                        <p>We appreciate your enthusiasm to join our movement. Unfortunately, your application was not approved at this time.</p>";
        
        if (!empty($rejection_reason)) {
            $html .= "<p><strong>Reason:</strong> " . htmlspecialchars($rejection_reason) . "</p>";
        }
        
        $html .= "</div>
                    
                    <p>We encourage you to reapply in the future or explore other ways to support our mission. Feel free to reach out to us at <a href='mailto:" . $contact_email . "'>" . $contact_email . "</a> to discuss alternative opportunities.</p>";
        
    } elseif ($status === 'Pending') {
        $html .= "
                    <p>Thank you for submitting your volunteer application!</p>
                    
                    <div class='status-badge status-pending'>
                        ⏳ APPLICATION PENDING
                    </div>
                    
                    <div class='message'>
                        <strong>Status:</strong>
                        <p>Your application is currently under review by our volunteer coordination team. We will notify you soon with an update.</p>
                    </div>
                    
                    <p>In the meantime, feel free to explore our programs and events at our website.</p>";
    }
    
    // Add admin notes if provided
    if (!empty($admin_notes)) {
        $html .= "
                    <div class='message'>
                        <strong>Additional Information:</strong>
                        <p>" . htmlspecialchars($admin_notes) . "</p>
                    </div>";
    }
    
    $html .= "
                    <!-- Contact Section -->
                    <p style='margin-top: 30px; color: #666; font-size: 14px;'>
                        If you have any questions about your application or volunteer opportunities, please don't hesitate to contact us.
                    </p>
                </div>
                
                <!-- Footer -->
                <div class='footer'>
                    <p>Youth for Just Food Systems</p>
                    <p>Building Youth-Powered Just Food Systems Together</p>
                    <p>Email: <a href='mailto:" . $contact_email . "'>" . $contact_email . "</a></p>
                    <p style='color: #999; font-size: 11px; margin-top: 15px;'>
                        This is an automated message. Please do not reply directly to this email. Use our contact form instead.
                    </p>
                </div>
            </div>
        </div>
    </body>
    </html>";
    
    return $html;
}

/**
 * Log volunteer status change in database
 * 
 * @param mysqli $conn - Database connection
 * @param int $volunteer_id - Volunteer ID
 * @param string $previous_status - Previous status
 * @param string $new_status - New status
 * @param bool $notification_sent - Whether email was sent
 * @param string $admin_notes - Notes from admin
 * @return bool - True if logged successfully
 */
function logVolunteerStatusChange($conn, $volunteer_id, $previous_status, $new_status, $notification_sent, $admin_notes = '') {
    
    $notification_sent_int = $notification_sent ? 1 : 0;
    
    $query = "INSERT INTO volunteer_notifications 
              (volunteer_id, previous_status, new_status, notification_sent, admin_notes) 
              VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "issis", $volunteer_id, $previous_status, $new_status, $notification_sent_int, $admin_notes);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }
    
    return false;
}

?>