<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$userIsDM = isDM($pdo, $_SESSION['UserID']);

// Path to your map image — replace with your own file in this folder.
$mapImage = 'Spirecrest.jpg';

$locations = $pdo->query(
    'SELECT BuildingID, BuildingName, LocationID, BuildingType, Notes, MapX, MapY
     FROM location_building
     WHERE MapX IS NOT NULL AND MapY IS NOT NULL'
)->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
       <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .wrap { max-width: 900px; margin: 20px auto; padding: 0 14px; }

        h1 {
            font-size: 24px; margin: 0 0 4px; color: #f2ede4;
        }
        .subtitle { color: #9b9599; font-size: 13px; margin: 0 0 16px; }

        .card {
            background: #201f26; border: 1px solid #35333d; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.4); margin-bottom: 16px;
        }
        .card-header {
            padding: 12px 18px; background: linear-gradient(135deg, #3a1a1a, #201f26 70%);
            border-bottom: 2px solid #7a2626; display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h2 {
            margin: 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.06em; color: #c9a97a;
        }
        .card-header a {
            background: #7a2626; color: #fff; border: 1px solid #a83e3e; border-radius: 5px;
            padding: 6px 12px; font-size: 12.5px; text-decoration: none; font-weight: 600;
        }
        .card-header a:hover { background: #8f2d2d; }

        .roster-table-wrap { overflow-x: auto; }
        table.roster { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        table.roster th {
            text-align: left; padding: 8px 18px; color: #9b9599; font-size: 11px;
            text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #2c2a33;
        }
        table.roster td { padding: 9px 18px; border-bottom: 1px solid #2c2a33; }
        table.roster tr:last-child td { border-bottom: none; }
        table.roster a { color: #e7e4de; text-decoration: none; font-weight: 600; }
        table.roster a:hover { color: #c9a97a; }

        .status-pill {
            display: inline-block; padding: 2px 9px; border-radius: 10px;
            font-size: 11px; color: #fff; text-transform: uppercase; letter-spacing: 0.03em;
        }

        .empty { color: #7d7880; font-size: 13px; padding: 14px 18px; }

        .actions-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
        .action-link {
            background: #26242c; color: #e7e4de; border: 1px solid #3a3742; border-radius: 6px;
            padding: 8px 14px; font-size: 13px; text-decoration: none;
        }
        .action-link:hover { border-color: #7a2626; color: #c9a97a; }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spirecrest</title>
    <style>
        #map-wrap {
            position: relative;
            display: inline-block;
            cursor: crosshair;
        }
        #map-wrap img {
            display: block;
            max-width: 100%;
        }
        .pin {
            position: absolute;
            transform: translate(-50%, -100%);
            background: #8c2f2f;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 4px;
            white-space: nowrap;
            text-decoration: none;
        }
        #new-pin-form {
            position: absolute;
            background: white;
            border: 1px solid #999;
            padding: 10px;
            display: none;
            z-index: 10;
        }
    </style>
</head>
<body>
    <h1>Map</h1>
    <p><a href="profile.php">&laquo; Back to profile</a></p>
    <?php if ($userIsDM): ?>
        <p><em>DM mode: click the map to add a location.</em></p>
    <?php endif; ?>

    <div id="map-wrap">
        <img src="<?= htmlspecialchars($mapImage) ?>" id="map-image" alt="Campaign map" style="<?= $userIsDM ? 'cursor: crosshair;' : '' ?>">

        <?php foreach ($locations as $loc): ?>
            <a class="pin"
               style="left: <?= htmlspecialchars($loc['MapX']) ?>%; top: <?= htmlspecialchars($loc['MapY']) ?>%;"
               href="location.php?id=<?= (int) $loc['BuildingID'] ?>">
                <?= htmlspecialchars($loc['BuildingName']) ?>
            </a>
        <?php endforeach; ?>

        <?php if ($userIsDM): ?>
        <form id="new-pin-form" method="POST" action="save_building.php">
            <input type="hidden" name="map_x" id="field-x">
            <input type="hidden" name="map_y" id="field-y">

            <label>Name<br>
                <input type="text" name="name" maxlength="100" required>
            </label><br><br>

            <label>Type<br>
                <select name="type">
                    <option>Residential</option>
                    <option>Commercial</option>
                    <option>Industrial</option>
                    <option>Institutional</option>
                    <option>Defensive</option>
                </select>
            </label><br><br>

            <label>Notes<br>
                <textarea name="notes" rows="3" cols="30"></textarea>
            </label><br><br>

            <button type="submit">Save location</button>
            <button type="button" onclick="document.getElementById('new-pin-form').style.display='none';">Cancel</button>
        </form>
        <?php endif; ?>
    </div>

    <?php if ($userIsDM): ?>
    <script>
        const mapImage = document.getElementById('map-image');
        const form = document.getElementById('new-pin-form');
        const fieldX = document.getElementById('field-x');
        const fieldY = document.getElementById('field-y');

        mapImage.addEventListener('click', (e) => {
            const rect = mapImage.getBoundingClientRect();
            const xPercent = ((e.clientX - rect.left) / rect.width) * 100;
            const yPercent = ((e.clientY - rect.top) / rect.height) * 100;

            fieldX.value = xPercent.toFixed(2);
            fieldY.value = yPercent.toFixed(2);

            form.style.left = e.clientX - rect.left + 10 + 'px';
            form.style.top = e.clientY - rect.top + 10 + 'px';
            form.style.display = 'block';
        });
    </script>
    <?php endif; ?>
</body>
</html>
