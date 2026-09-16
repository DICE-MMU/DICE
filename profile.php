<?php
session_start();
require 'db.php';

if (empty($_SESSION['UserID'])) {
    header('Location: signup.php');
    exit;
}

$userId = $_SESSION['UserID'];

$pcStmt = $pdo->prepare('SELECT PCID, PCName, PCStatus FROM pc WHERE UserID = ? ORDER BY PCName ASC');
$pcStmt->execute([$userId]);
$characters = $pcStmt->fetchAll();

$npcStmt = $pdo->prepare('SELECT NPCID, NPCName, NPCStatus FROM npc WHERE UserID = ? ORDER BY NPCName ASC');
$npcStmt->execute([$userId]);
$npcs = $npcStmt->fetchAll();

$statusColors = [
    'Active'     => '#3f6f4a',
    'Resting'    => '#9c7a1f',
    'Travelling' => '#2f5578',
    'Deceased'   => '#7a2626',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Profile</title>
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
</head>
<body>
<div class="wrap">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['UserName']) ?></h1>
    <p class="subtitle">Your roster</p>

    <div class="card">
        <div class="card-header">
            <h2>Your PCs</h2>
            <a href="pc_create.php">+ New PC</a>
        </div>

        <?php if (empty($characters)): ?>
            <p class="empty">You don't have any characters yet.</p>
        <?php else: ?>
            <div class="roster-table-wrap">
                <table class="roster">
                    <thead><tr><th>Name</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($characters as $pc): ?>
                            <?php $s = trim($pc['PCStatus']); $c = $statusColors[$s] ?? '#555'; ?>
                            <tr>
                                <td><a href="pc.php?id=<?= (int) $pc['PCID'] ?>"><?= htmlspecialchars($pc['PCName']) ?></a></td>
                                <td><span class="status-pill" style="background: <?= $c ?>;"><?= htmlspecialchars($s) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Your NPCs</h2>
            <a href="npc_create.php">+ New NPC</a>
        </div>

        <?php if (empty($npcs)): ?>
            <p class="empty">You don't have any NPCs yet.</p>
        <?php else: ?>
            <div class="roster-table-wrap">
                <table class="roster">
                    <thead><tr><th>Name</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($npcs as $npc): ?>
                            <?php $s = trim($npc['NPCStatus']); $c = $statusColors[$s] ?? '#555'; ?>
                            <tr>
                                <td><a href="npc.php?id=<?= (int) $npc['NPCID'] ?>"><?= htmlspecialchars($npc['NPCName']) ?></a></td>
                                <td><span class="status-pill" style="background: <?= $c ?>;"><?= htmlspecialchars($s) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <div class="actions-row">
        <a href="map.php" class="action-link">View map</a>
        <a href="relationships.php" class="action-link">View relationships</a>
        <a href="feedback.php" class="action-link">View Feedback</a>
        <a href="quest_board.php" class="action-link">Quest Board</a>
        <a href="board.php" class="action-link">Investigation Board</a>
        <a href="calendar.php" class="action-link">Calendar</a>
        <a href="logout.php" class="action-link">Log out</a>
    </div>
</div>
</body>
</html>
