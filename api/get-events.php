<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

require_once '../config.php';

$events = [];
$result = mysqli_query($conn, "SELECT * FROM events ORDER BY date ASC");

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $colorMap = [
            'Workshop'   => '#2E7D32',
            'Discussion' => '#0288D1',
            'Festival'   => '#E65100',
        ];
        $color = $colorMap[$row['category']] ?? '#2E7D32';

        $events[] = [
            'id'    => $row['id'],
            'title' => $row['title'],
            'start' => $row['date'],
            'color' => $color,
            'extendedProps' => [
                'description' => $row['description'] ?? '',
                'venue'       => $row['venue'] ?? '',
                'category'    => $row['category'] ?? '',
            ],
        ];
    }
}

echo json_encode($events);