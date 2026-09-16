<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$userIsDM = isDM($pdo, $_SESSION['UserID']);

// 2. Calendar Settings & Parameters
// The real-world year that corresponds to festival year 5136 being 2026.
const BASELINE_REAL_YEAR    = 2026;
const BASELINE_FESTIVAL_YEAR = 5136;

$now = new DateTime('now');
$realYear = (int) $now->format('Y');

// Bump the count a day early — on Dec 31 rather than waiting for Jan 1.
if ((int) $now->format('n') === 12 && (int) $now->format('j') >= 31) {
    $realYear++;
}

$defaultYear = BASELINE_FESTIVAL_YEAR + ($realYear - BASELINE_REAL_YEAR);

$current_year = isset($_GET['year']) ? (int)$_GET['year'] : $defaultYear; // Default to the current festival year

// Fetch all months from the database
$months_stmt = $pdo->query("SELECT month_num, month_name FROM dekkara_month ORDER BY month_num ASC");
$months = $months_stmt->fetchAll();

// Fetch all events for the current year
// Absolute day range for the requested year
$year_start_day = (($current_year - 1) * 360) + 1;
$year_end_day   = $current_year * 360;

// Catch events that overlap this year at all — not just ones that START
// in it, since a multi-day event could start in a prior year and still
// spill into this one.
$event_stmt = $pdo->prepare(
    "SELECT name, start_day, end_day
     FROM dekkara_event
     WHERE start_day <= ?
       AND COALESCE(end_day, start_day) >= ?"
);
$event_stmt->execute([$year_end_day, $year_start_day]);
$events_raw = $event_stmt->fetchAll();

// Map every day an event spans to its name — a single-day event (no
// end_day) just maps to the one day, same as before. A multi-day event
// gets the same badge repeated on each day within the visible year.
$events = [];
foreach ($events_raw as $ev) {
    $spanStart = max($ev['start_day'], $year_start_day);
    $spanEnd   = min($ev['end_day'] ?? $ev['start_day'], $year_end_day);
    for ($day = $spanStart; $day <= $spanEnd; $day++) {
        $events[$day][] = $ev['name'];
    }
}

$weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

// Each month's Day 36 has its own fixed name — this is calendar structure,
// not campaign data, so it lives here rather than in a database table.
$holydays = [
    1  => 'Quilda',
    2  => 'Blademeet',
    3  => 'Luminus',
    4  => 'Midsummer',
    5  => 'Midannum',
    6  => "Land Mother's Harvest",
    7  => 'Ash Festival',
    8  => 'Resolve',
    9  => 'Darkmoot',
    10 => 'Firebloom',
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dekkara World Calendar</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        header {
            text-align: center;
            margin-bottom: 30px;
        }
        .year-nav {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .year-nav a {
            text-decoration: none;
            color: #007bff;
            padding: 0 15px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }
        .month-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 15px;
            border-top: 4px solid #4a5568;
        }
        .month-name {
            text-align: center;
            font-size: 1.25rem;
            margin-top: 0;
            margin-bottom: 10px;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .days-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr); /* 7 days — the HolyDay isn't a weekday column */
            gap: 4px;
            text-align: center;
            font-size: 0.85rem;
        }
        .day-header {
            font-weight: bold;
            color: #718096;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .day-cell {
            background: #f7fafc;
            border-radius: 4px;
            padding: 8px 0;
            min-height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }
        .day-cell.holyday {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #d97706;
            font-weight: bold;
            grid-column: span 7; /* Forces the HolyDay to stretch across the bottom row full-width */
            margin-top: 4px;
        }
        .day-number {
            font-weight: 600;
        }
        .event-badge {
            background-color: #ebf8ff;
            color: #2b6cb0;
            font-size: 0.7rem;
            padding: 2px 4px;
            border-radius: 3px;
            margin: 2px 4px 0 4px;
            border-left: 2px solid #3182ce;
            text-align: left;
            word-break: break-word;
        }
        .back-link { margin-bottom: 14px; }
        .back-link a { color: #007bff; text-decoration: none; font-size: 0.9rem; }
        .error-box {
            background: #fed7d7; color: #9b2c2c; border: 1px solid #fc8181;
            border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; font-size: 0.85rem;
        }
        .add-event-card {
            background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 15px 18px; margin-bottom: 24px; border-top: 4px solid #d97706;
        }
        .add-event-card h3 { margin-top: 0; font-size: 1.05rem; color: #2d3748; }
        .add-event-form { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; }
        .add-event-form .field { display: flex; flex-direction: column; gap: 4px; font-size: 0.8rem; color: #4a5568; }
        .add-event-form input, .add-event-form select {
            padding: 6px 8px; border: 1px solid #cbd5e0; border-radius: 5px; font-size: 0.85rem;
        }
        .add-event-form input[name="event_name"] { min-width: 200px; }
        .add-event-form button {
            background: #d97706; color: #fff; border: none; border-radius: 5px;
            padding: 7px 14px; font-size: 0.85rem; cursor: pointer; font-weight: 600;
        }
        .add-event-form button:hover { background: #b45309; }
    </style>
</head>
<body>

<div class="container">
    <div class="back-link"><a href="profile.php">&laquo; Back to profile</a></div>

    <?php if (!empty($_GET['error'])): ?>
        <div class="error-box"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <header>
        <h1>Dekkara Chronology</h1>
        <div class="year-nav">
            <a href="?year=<?= $current_year - 1 ?>">&laquo; Previous</a>
            <span>Year <?= $current_year ?></span>
            <a href="?year=<?= $current_year + 1 ?>">Next &raquo;</a>
        </div>
    </header>

    <?php if ($userIsDM): ?>
        <div class="add-event-card">
            <h3>Add an event (DM only)</h3>
            <form method="POST" action="calendar_actions.php" class="add-event-form">
                <input type="hidden" name="action" value="add_event">

                <div class="field">
                    <label>Year</label>
                    <input type="number" name="year" id="event-year" value="<?= $current_year ?>" required>
                </div>
                <div class="field">
                    <label>Month</label>
                    <select name="month_num" id="event-month" required>
                        <?php foreach ($months as $m): ?>
                            <option value="<?= (int) $m['month_num'] ?>"><?= htmlspecialchars($m['month_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="field">
                    <label>Day (1-36)</label>
                    <input type="number" name="day" id="event-day" min="1" max="36" value="1" required>
                </div>
                <div class="field">
                    <label>Duration (days)</label>
                    <input type="number" name="duration" id="event-duration" min="1" value="1" required>
                </div>
                <div class="field">
                    <label>Event name</label>
                    <input type="text" name="event_name" maxlength="255" required>
                </div>
                <button type="submit">Add event</button>
            </form>
            <p id="event-preview" style="font-size: 0.8rem; color: #718096; margin: 8px 0 0;"></p>
        </div>

        <script>
            const monthNames = <?= json_encode(array_column($months, 'month_name', 'month_num')) ?>;
            const monthCount = <?= count($months) ?>;
            const yearEl = document.getElementById('event-year');
            const monthEl = document.getElementById('event-month');
            const dayEl = document.getElementById('event-day');
            const durationEl = document.getElementById('event-duration');
            const previewEl = document.getElementById('event-preview');

            function updatePreview() {
                const startYear = parseInt(yearEl.value, 10) || <?= $current_year ?>;
                const startMonth = parseInt(monthEl.value, 10);
                const startDay = parseInt(dayEl.value, 10) || 1;
                const duration = parseInt(durationEl.value, 10) || 1;

                // Walk forward day-by-day through 36-day months, wrapping
                // to the next month — and year — as needed.
                let year = startYear, month = startMonth, day = startDay;
                for (let i = 1; i < duration; i++) {
                    day++;
                    if (day > 36) {
                        day = 1; month++;
                        if (month > monthCount) { month = 1; year++; }
                    }
                }

                if (duration <= 1) {
                    previewEl.textContent = `Single day: Day ${startDay} of ${monthNames[startMonth]}, Year ${startYear}.`;
                } else if (year === startYear) {
                    previewEl.textContent = `Runs from Day ${startDay} of ${monthNames[startMonth]} through Day ${day} of ${monthNames[month]}, Year ${startYear}.`;
                } else {
                    previewEl.textContent = `Runs from Day ${startDay} of ${monthNames[startMonth]}, Year ${startYear}, through Day ${day} of ${monthNames[month]}, Year ${year}.`;
                }
            }
            [yearEl, monthEl, dayEl, durationEl].forEach(el => el.addEventListener('input', updatePreview));
            updatePreview();
        </script>
    <?php endif; ?>

    <div class="calendar-grid">
        <?php foreach ($months as $month): ?>
            <div class="month-card">
                <h3 class="month-name"><?= htmlspecialchars($month['month_name']) ?></h3>
                
                <div class="days-grid">
                    <!-- Print Weekday Headers -->
                    <?php foreach ($weekdays as $day): ?>
                        <div class="day-header"><?= $day ?></div>
                    <?php endforeach; ?>

                    <!-- Generate 36 Days: 5 weeks of 7 (Sun-Sat), then the HolyDay -->
                    <?php 
                    for ($d = 1; $d <= 36; $d++) {
                        // Calculate absolute day index to pull database events correctly
                        $absolute_day = (($current_year - 1) * 360) + (($month['month_num'] - 1) * 36) + $d;
                        $has_events = isset($events[$absolute_day]);
                        
                        if ($d === 36) {
                            // Render the special HolyDay row element
                            $holydayName = $holydays[$month['month_num']] ?? 'HolyDay';
                            echo '<div class="day-cell holyday">';
                            echo '<span class="day-number">' . htmlspecialchars($holydayName) . '</span>';
                            if ($has_events) {
                                foreach ($events[$absolute_day] as $event_name) {
                                    echo '<div class="event-badge">' . htmlspecialchars($event_name) . '</div>';
                                }
                            }
                            echo '</div>';
                        } else {
                            // Render standard weekday elements
                            echo '<div class="day-cell">';
                            echo '<span class="day-number">' . $d . '</span>';
                            if ($has_events) {
                                foreach ($events[$absolute_day] as $event_name) {
                                    echo '<div class="event-badge">' . htmlspecialchars($event_name) . '</div>';
                                }
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>