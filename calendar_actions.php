<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

if (!isDM($pdo, $_SESSION['UserID'])) {
    die('Only a current DM can add calendar events.');
}

function redirectBack(int $year, string $error = ''): void
{
    $url = "calendar.php?year=$year";
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

$action = $_POST['action'] ?? '';

switch ($action) {

    case 'add_event':
        $year     = (int) ($_POST['year'] ?? 5136);
        $monthNum = (int) ($_POST['month_num'] ?? 0);
        $day      = (int) ($_POST['day'] ?? 0);
        $duration = max(1, (int) ($_POST['duration'] ?? 1));
        $name     = trim($_POST['event_name'] ?? '');

        if ($name === '' || mb_strlen($name) > 255) {
            redirectBack($year, 'Event name is required and must be 255 characters or fewer.');
        }
        if ($day < 1 || $day > 36) {
            redirectBack($year, 'Day must be between 1 and 36.');
        }

        // Confirm the month actually exists rather than trusting the POST blindly
        $monthCheck = $pdo->prepare('SELECT month_num FROM dekkara_month WHERE month_num = ?');
        $monthCheck->execute([$monthNum]);
        if (!$monthCheck->fetch()) {
            redirectBack($year, 'Invalid month selected.');
        }

        // Same absolute-day math used to render the calendar grid
        $startDay = (($year - 1) * 360) + (($monthNum - 1) * 36) + $day;
        $endDay   = $startDay + $duration - 1;

        $stmt = $pdo->prepare('INSERT INTO dekkara_event (name, start_day, end_day) VALUES (?, ?, ?)');
        $stmt->execute([$name, $startDay, $endDay]);

        redirectBack($year);
        break;

    default:
        redirectBack((int) ($_POST['year'] ?? 5136), 'Unknown action.');
}
