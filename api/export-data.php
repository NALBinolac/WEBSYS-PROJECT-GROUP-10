<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized");
}

$export_type = $_GET['type'] ?? 'users';
header('Content-Type: application/xml; charset=utf-8');

// Create root XML element
if ($export_type === 'events') {
    $xml = new SimpleXMLElement('<?xml version="1.0"?><events></events>');
    $query = "SELECT id, title, date, venue, description, time_range, category FROM events";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $event = $xml->addChild('event');
            $event->addAttribute('id', $row['id']);
            $event->addChild('title', htmlspecialchars($row['title']));
            $event->addChild('date', $row['date']);
            $event->addChild('venue', htmlspecialchars($row['venue']));
            $event->addChild('time_range', $row['time_range']);
            $event->addChild('category', $row['category']);
            $event->addChild('description', htmlspecialchars($row['description'] ?? ''));
        }
    }
    header('Content-Disposition: attachment; filename="events_export.xml"');
    
} elseif ($export_type === 'modules') {
    $xml = new SimpleXMLElement('<?xml version="1.0"?><modules></modules>');
    $query = "SELECT id, module_number, title, description, type FROM modules";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $module = $xml->addChild('module');
            $module->addAttribute('id', $row['id']);
            $module->addChild('module_number', $row['module_number']);
            $module->addChild('title', htmlspecialchars($row['title']));
            $module->addChild('type', $row['type']);
            $module->addChild('description', htmlspecialchars($row['description'] ?? ''));
        }
    }
    header('Content-Disposition: attachment; filename="modules_export.xml"');
    
} else {
    // Default: users
    $xml = new SimpleXMLElement('<?xml version="1.0"?><users></users>');
    $query = "SELECT id, username, fullname, email, role, created_at FROM users";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user = $xml->addChild('user');
            $user->addAttribute('id', $row['id']);
            $user->addChild('username', htmlspecialchars($row['username']));
            $user->addChild('fullname', htmlspecialchars($row['fullname'] ?? ''));
            $user->addChild('email', htmlspecialchars($row['email']));
            $user->addChild('role', $row['role']);
            $user->addChild('created_at', $row['created_at']);
        }
    }
    header('Content-Disposition: attachment; filename="users_export.xml"');
}

echo $xml->asXML();
?>