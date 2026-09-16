<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$npcId = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare(
    'SELECT npc.NPCID, npc.NPCName, npc.NPCStatus, npc.NPCClass, npc.NPCLevel, npc.LocationID,
            location.LocationName,
            npc_bio.Race, npc_bio.SubRace, npc_bio.Alignment, npc_bio.Age,
            npc_stat.STR, npc_stat.DEX, npc_stat.CON, npc_stat.INT, npc_stat.WIS, npc_stat.CHA
     FROM pc
     LEFT JOIN location ON npc.LocationID = location.LocationID
     LEFT JOIN npc_bio ON npc.NPCID = npc_bio.NPCID
     LEFT JOIN npc_class ON npc.NPCID = npc_class.NPCID
     LEFT JOIN npc_stat ON npc.NPCID = npc_stat.NPCID
     WHERE npc.NPCID = ? AND npc.UserID = ?'
);
$stmt->execute([$npcId, $_SESSION['UserID']]);
$npc = $stmt->fetch();

if (!$npc) {
    die('Character not found.');
}

$classList  = ['Barbarian','Bard','Cleric','Druid','Fighter','Monk','Paladin','Ranger','Rogue','Sorcerer','Warlock','Wizard'];
$alignments = ['Lawful Good','Neutral Good','Chaotic Good','Lawful Neutral','True Neutral','Chaotic Neutral'];

// Total level = sum across every active multiclass slot
$totalLevel = (int) ($npc['NPCLevel'] ?? 0);

$classLine = [];
if ($pc['Class1']) { $classLine[] = $pc['Class1'] . ' ' . (int) $pc['Level1']; }
$classLine = implode(', ', $classLine);

function abilityMod(?int $score): int
{
    return (int) floor(((int) ($score ?? 10) - 10) / 2);
}
function fmtMod(int $m): string
{
    return $m >= 0 ? "+$m" : (string) $m;
}

$invContainers = $pdo->prepare(
    'SELECT InventoryID, InventoryName FROM inventory WHERE PCID = ? ORDER BY InventoryID ASC'
);
$invContainers->execute([$npc['NPCID']]);
$inventories = $invContainers->fetchAll();

$itemsByInventory = [];
if (!empty($inventories)) {
    $ids = array_column($inventories, 'InventoryID');
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $itemStmt = $pdo->prepare(
        "SELECT ii.InventoryItemID, ii.InventoryID, ii.Quantity, s.ItemName
         FROM inventory_item ii
         JOIN shop s ON ii.ItemID = s.ItemID
         WHERE ii.InventoryID IN ($placeholders)
         ORDER BY s.ItemName ASC"
    );
    $itemStmt->execute($ids);
    foreach ($itemStmt->fetchAll() as $row) {
        $itemsByInventory[$row['InventoryID']][] = $row;
    }
}

$locations = $pdo->query('SELECT LocationName FROM location ORDER BY LocationName ASC')->fetchAll();
$allItems  = $pdo->query('SELECT ItemName FROM shop ORDER BY ItemName ASC')->fetchAll();

$status = trim($npc['NPCStatus']);

$statusColors = [
    'Active'     => '#3f6f4a',
    'Resting'    => '#9c7a1f',
    'Travelling' => '#2f5578',
    'Deceased'   => '#7a2626',
];
$statusColor = $statusColors[$status] ?? '#555';

$error = $_GET['error'] ?? '';

function classOptions(array $classes, ?string $selected): string
{
    $html = '';
    foreach ($classes as $c) {
        $sel = ($c === $selected) ? 'selected' : '';
        $html .= '<option value="' . htmlspecialchars($c) . '" ' . $sel . '>' . htmlspecialchars($c) . '</option>';
    }
    return $html;
}

$abilities = [
    'STR' => $npc['STR'], 'DEX' => $npc['DEX'], 'CON' => $npc['CON'],
    'INT' => $npc['INT'], 'WIS' => $npc['WIS'], 'CHA' => $npc['CHA'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($npc['NPCName']) ?> — Character Sheet</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .sheet-wrap { max-width: 900px; margin: 20px auto; padding: 0 14px; }
        .back-link { display: block; margin-bottom: 10px; }
        .back-link a { color: #b48a5a; text-decoration: none; font-size: 13px; }

        .sheet-card {
            background: #201f26; border: 1px solid #35333d; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.4);
        }

        .status-banner {
            text-align: center; color: #f2ede4; font-weight: 700;
            padding: 6px 0; font-size: 12px; letter-spacing: 0.08em; text-transform: uppercase;
        }

        .location-row {
            display: flex; align-items: center; gap: 8px; justify-content: center;
            padding: 8px 22px; background: #1b1a20; border-bottom: 1px solid #2c2a33;
            font-size: 12.5px; flex-wrap: wrap;
        }
        .location-row span { color: #9b9599; }
        .location-row input {
            padding: 5px 8px; font-size: 12.5px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de; min-width: 180px;
        }

        .sheet-header {
            background: linear-gradient(135deg, #3a1a1a, #201f26 70%);
            padding: 18px 22px; display: flex; justify-content: space-between; align-items: center;
            border-bottom: 2px solid #7a2626; flex-wrap: wrap; gap: 10px;
        }
        .npc-name { font-size: 26px; font-weight: 700; margin: 0; letter-spacing: 0.02em; color: #f2ede4; }
        .class-line { font-size: 13px; color: #c9a97a; margin: 4px 0 0; text-transform: uppercase; letter-spacing: 0.05em; }

        .level-badge {
            text-align: center; background: #7a2626; color: #fff; border-radius: 8px;
            padding: 8px 16px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em;
            border: 1px solid #a83e3e;
        }
        .level-badge strong { display: block; font-size: 22px; letter-spacing: 0; }

        .bio-row {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;
            padding: 14px 22px; background: #1b1a20; border-bottom: 1px solid #2c2a33;
            align-items: end;
        }
        .bio-field { display: flex; flex-direction: column; gap: 4px; }
        .bio-field label { font-size: 10px; color: #9b9599; text-transform: uppercase; letter-spacing: 0.06em; }
        .bio-field input, .bio-field select {
            padding: 6px 8px; font-size: 13px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }
        .bio-save-wrap { grid-column: 1 / -1; text-align: right; margin-top: 6px; }

        .ability-rail {
            display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px;
            padding: 20px 22px 10px; background: #1b1a20; border-bottom: 1px solid #2c2a33;
        }
        .ability-box {
            background: #26242c; border: 1px solid #3a3742; border-radius: 8px;
            text-align: center; padding: 10px 4px;
        }
        .ability-box .ab-label { font-size: 10px; color: #9b9599; text-transform: uppercase; letter-spacing: 0.06em; }
        .ability-box input {
            width: 100%; text-align: center; font-size: 20px; font-weight: 700; color: #f2ede4;
            background: transparent; border: none; border-bottom: 1px solid #3a3742;
            margin: 4px 0 2px; padding: 2px 0;
        }
        .ability-box .ab-mod { font-size: 12px; color: #c9a97a; }
        .ability-save-wrap { padding: 0 22px 16px; background: #1b1a20; border-bottom: 1px solid #2c2a33; text-align: right; }

        details.section { border-bottom: 1px solid #2c2a33; }
        details.section > summary {
            cursor: pointer; padding: 12px 22px; font-size: 12.5px; font-weight: 700;
            color: #c9a97a; text-transform: uppercase; letter-spacing: 0.06em;
            background: #201f26; list-style: none; border-left: 3px solid #7a2626;
        }
        details.section > summary::-webkit-details-marker { display: none; }
        details.section > summary:before { content: '▸ '; display: inline-block; transition: transform 0.15s; }
        details.section[open] > summary:before { transform: rotate(90deg); }
        .section-body { padding: 16px 22px 20px; background: #1b1a20; }

        .class-row {
            display: flex; align-items: center; gap: 8px; margin-bottom: 12px;
            padding-bottom: 12px; border-bottom: 1px solid #2c2a33; flex-wrap: wrap;
        }
        .class-row .slot-label { flex: 0 0 90px; font-size: 12px; color: #9b9599; }
        .class-row select, .class-row input[type=text] {
            padding: 6px 8px; font-size: 13px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }
        .class-row input[type=number] {
            width: 55px; padding: 6px; font-size: 13px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }
        .class-row label.sub { font-size: 11px; color: #7d7880; margin-right: 3px; }
        .class-row input[type=checkbox] { accent-color: #7a2626; }

        details.inv-box { border: 1px solid #2c2a33; border-radius: 6px; margin-bottom: 10px; background: #201f26; }
        details.inv-box > summary {
            cursor: pointer; padding: 9px 12px; font-weight: 600; font-size: 13px;
            background: #26242c; list-style: none; color: #e7e4de; border-radius: 6px 6px 0 0;
        }
        details.inv-box > summary::-webkit-details-marker { display: none; }
        .inv-box-body { padding: 10px 12px; }

        .inv-list { list-style: none; margin: 0 0 12px; padding: 0; }
        .inv-list li {
            display: flex; align-items: center; justify-content: space-between;
            padding: 7px 0; border-bottom: 1px solid #2c2a33; font-size: 13px; color: #e7e4de;
        }
        .inv-list form { display: flex; gap: 6px; align-items: center; }
        .inv-list input[type=number] {
            width: 55px; padding: 4px; background: #26242c; color: #e7e4de;
            border: 1px solid #3a3742; border-radius: 4px;
        }
        .remove-btn {
            background: #2c2a33; border: 1px solid #3a3742; border-radius: 4px;
            cursor: pointer; padding: 4px 9px; font-size: 12px; color: #e7e4de;
        }
        .remove-btn:hover { border-color: #7a2626; }

        .add-item-row { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
        .add-item-row input[list] {
            flex: 1; min-width: 140px; padding: 7px 8px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }
        .add-item-row input[type=number] {
            width: 60px; padding: 7px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }

        .save-btn, .add-btn {
            background: #7a2626; color: #fff; border: 1px solid #a83e3e; border-radius: 5px;
            padding: 8px 16px; font-size: 13px; cursor: pointer; font-weight: 600;
        }
        .save-btn:hover, .add-btn:hover { background: #8f2d2d; }
        .save-btn.small { padding: 5px 12px; font-size: 12px; }

        .new-inv-row { display: flex; gap: 6px; margin-top: 8px; }
        .new-inv-row input[type=text] {
            flex: 1; padding: 7px 8px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }

        .error-box { background: #3a1f1f; color: #f0b4b4; padding: 10px 22px; font-size: 13px; border-bottom: 1px solid #7a2626; }

        @media (max-width: 600px) {
            .bio-row, .ability-rail { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

    <div class="sheet-wrap">
        <div class="back-link"><a href="profile.php">&laquo; Back to roster</a></div>

        <div class="sheet-card">
            <div class="status-banner" style="background: <?= $statusColor ?>;">
                <?= htmlspecialchars($status !== '' ? $status : 'Unknown') ?>
            </div>

            <!-- ===================== STATUS (change) ===================== -->
            <form method="POST" action="update_character.php" class="location-row">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">
                <span>Status</span>
                <select name="npc_status">
                    <?php foreach (array_keys($statusColors) as $s): ?>
                        <option value="<?= htmlspecialchars($s) ?>" <?= $status === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="save-btn small">Save</button>
            </form>

            <!-- ===================== LOCATION (below status) ===================== -->
            <form method="POST" action="update_character.php" class="location-row">
                <input type="hidden" name="action" value="update_location">
                <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">
                <span>Location</span>
                <input list="location-options" name="location_name"
                       value="<?= htmlspecialchars($npc['LocationName'] ?? '') ?>"
                       placeholder="Search a location…">
                <datalist id="location-options">
                    <?php foreach ($locations as $loc): ?>
                        <option value="<?= htmlspecialchars($loc['LocationName']) ?>">
                    <?php endforeach; ?>
                </datalist>
                <button type="submit" class="save-btn small">Save</button>
            </form>

            <div class="sheet-header">
                <div>
                    <p class="npc-name"><?= htmlspecialchars($npc['NPCName']) ?></p>
                    <p class="class-line"><?= $classLine !== '' ? htmlspecialchars($classLine) : 'No class set' ?></p>
                </div>
                <div class="level-badge">
                    LEVEL
                    <strong><?= $totalLevel ?></strong>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="error-box"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- ===================== BIO (npc_bio, editable, near top) ===================== -->
            <form method="POST" action="update_character.php" class="bio-row">
                <input type="hidden" name="action" value="update_bio">
                <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">

                <div class="bio-field">
                    <label>Race</label>
                    <input type="text" name="npc_race" value="<?= htmlspecialchars($pc['Race'] ?? '') ?>" maxlength="50">
                </div>
                <div class="bio-field">
                    <label>Subrace</label>
                    <input type="text" name="pc_subrace" value="<?= htmlspecialchars($npc['SubRace'] ?? '') ?>" maxlength="50" placeholder="Optional">
                </div>
                <div class="bio-field">
                    <label>Alignment</label>
                    <select name="npc_alignment">
                        <?php foreach ($alignments as $a): ?>
                            <option value="<?= htmlspecialchars($a) ?>" <?= $npc['Alignment'] === $a ? 'selected' : '' ?>><?= htmlspecialchars($a) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="bio-field">
                    <label>Age</label>
                    <input type="number" name="npc_age" value="<?= htmlspecialchars($npc['Age'] ?? '') ?>" min="0">
                </div>

                <div class="bio-save-wrap">
                    <button type="submit" class="save-btn small">Save bio</button>
                </div>
            </form>

            <!-- ===================== ABILITY SCORES (editable) ===================== -->
            <form method="POST" action="update_character.php">
                <input type="hidden" name="action" value="update_ability_scores">
                <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">

                <div class="ability-rail">
                    <?php
                    $abilityFields = [
                        'STR' => 'npc_str', 'DEX' => 'npc_dex', 'CON' => 'npc_con',
                        'INT' => 'npc_int', 'WIS' => 'npc_wis', 'CHA' => 'npc_cha',
                    ];
                    foreach ($abilityFields as $abbr => $fieldName):
                        $score = $abilities[$abbr] ?? 10;
                    ?>
                        <div class="ability-box">
                            <div class="ab-label"><?= $abbr ?></div>
                            <input type="number" name="<?= $fieldName ?>" value="<?= (int) $score ?>" min="1" max="30">
                            <div class="ab-mod"><?= fmtMod(abilityMod($score)) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="ability-save-wrap">
                    <button type="submit" class="save-btn small">Save scores</button>
                </div>
            </form>

            <!-- ===================== CLASS & MULTICLASS ===================== -->
            <details class="section" open>
                <summary>Class &amp; Multiclass</summary>
                <div class="section-body">
                    <form method="POST" action="update_character.php">
                        <input type="hidden" name="action" value="update_classes">
                        <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">

                        <div class="class-row">
                            <span class="slot-label">1st class</span>
                            <select name="class1"><?= classOptions($classList, $npc['Class1']) ?></select>
                            <label class="sub">Subclass</label>
                            <input type="text" name="subclass1" value="<?= htmlspecialchars($npc['SubClass1'] ?? '') ?>" maxlength="15">
                            <label class="sub">Level</label>
                            <input type="number" name="level1" value="<?= (int) ($npc['Level1'] ?? 1) ?>" min="0" max="30">
                        </div>

                        <button type="submit" class="save-btn">Save classes</button>
                    </form>
                </div>
            </details>

            <!-- ===================== INVENTORY ===================== -->
            <details class="section">
                <summary>Inventory</summary>
                <div class="section-body">

                    <?php if (empty($inventories)): ?>
                        <p style="color:#7d7880; font-size: 13px;">No inventory containers yet.</p>
                    <?php endif; ?>

                    <?php foreach ($inventories as $inv): ?>
                        <details class="inv-box">
                            <summary><?= htmlspecialchars($inv['InventoryName']) ?></summary>
                            <div class="inv-box-body">
                                <?php $items = $itemsByInventory[$inv['InventoryID']] ?? []; ?>

                                <?php if (empty($items)): ?>
                                    <p style="color:#7d7880; font-size: 13px;">Empty.</p>
                                <?php else: ?>
                                    <ul class="inv-list">
                                        <?php foreach ($items as $item): ?>
                                            <li>
                                                <span><?= htmlspecialchars($item['ItemName']) ?></span>
                                                <form method="POST" action="update_character.php">
                                                    <input type="hidden" name="action" value="update_item_qty">
                                                    <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">
                                                    <input type="hidden" name="inventory_item_id" value="<?= (int) $item['InventoryItemID'] ?>">
                                                    <input type="number" name="quantity" value="<?= (int) $item['Quantity'] ?>" min="0">
                                                    <button type="submit" class="remove-btn">Update</button>
                                                </form>
                                                <form method="POST" action="update_character.php">
                                                    <input type="hidden" name="action" value="remove_item">
                                                    <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">
                                                    <input type="hidden" name="inventory_item_id" value="<?= (int) $item['InventoryItemID'] ?>">
                                                    <button type="submit" class="remove-btn">Remove</button>
                                                </form>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>

                                <form method="POST" action="update_character.php" class="add-item-row">
                                    <input type="hidden" name="action" value="add_item">
                                    <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">
                                    <input type="hidden" name="inventory_id" value="<?= (int) $inv['InventoryID'] ?>">
                                    <input list="item-options" name="item_name" placeholder="Search an item…" required>
                                    <input type="number" name="quantity" value="1" min="1">
                                    <button type="submit" class="add-btn">Add</button>
                                </form>
                            </div>
                        </details>
                    <?php endforeach; ?>

                    <datalist id="item-options">
                        <?php foreach ($allItems as $item): ?>
                            <option value="<?= htmlspecialchars($item['ItemName']) ?>">
                        <?php endforeach; ?>
                    </datalist>

                    <form method="POST" action="update_character.php" class="new-inv-row">
                        <input type="hidden" name="action" value="add_inventory">
                        <input type="hidden" name="npc_id" value="<?= (int) $npc['NPCID'] ?>">
                        <input type="text" name="inventory_name" placeholder="New container name (e.g. Bank)" maxlength="50" required>
                        <button type="submit" class="add-btn">+ New inventory</button>
                    </form>
                </div>
            </details>

        </div>
    </div>

</body>
</html>
