<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$action = $_POST['action'] ?? '';
$pcId   = (int) ($_POST['pc_id'] ?? 0);

// Confirm this PC belongs to the logged-in user before touching anything.
$stmt = $pdo->prepare('SELECT PCID FROM pc WHERE PCID = ? AND UserID = ?');
$stmt->execute([$pcId, $_SESSION['UserID']]);
$pc = $stmt->fetch();

if (!$pc) {
    die('Character not found.');
}

function redirectBack(int $pcId, string $error = ''): void
{
    $url = 'character.php?id=' . $pcId;
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

// Returns the inventory row (or null) only if it actually belongs to this PC.
function findOwnedInventory(PDO $pdo, int $inventoryId, int $pcId): ?array
{
    $stmt = $pdo->prepare('SELECT InventoryID FROM inventory WHERE InventoryID = ? AND PCID = ?');
    $stmt->execute([$inventoryId, $pcId]);
    $row = $stmt->fetch();
    return $row ?: null;
}

switch ($action) {

    case 'update_classes':
        $classList = ['Barbarian','Bard','Cleric','Druid','Fighter','Monk','Paladin','Ranger','Rogue','Sorcerer','Warlock','Wizard'];

        $class1    = $_POST['class1'] ?? '';
        $subclass1 = trim($_POST['subclass1'] ?? '');
        $level1    = (int) ($_POST['level1'] ?? 0);

        $enable2   = isset($_POST['enable2']);
        $class2    = $enable2 ? ($_POST['class2'] ?? '') : null;
        $subclass2 = $enable2 ? trim($_POST['subclass2'] ?? '') : null;
        $level2    = $enable2 ? (int) ($_POST['level2'] ?? 0) : null;

        $enable3   = isset($_POST['enable3']);
        $class3    = $enable3 ? ($_POST['class3'] ?? '') : null;
        $subclass3 = $enable3 ? trim($_POST['subclass3'] ?? '') : null;
        $level3    = $enable3 ? (int) ($_POST['level3'] ?? 0) : null;

        if (!in_array($class1, $classList, true)) {
            redirectBack($pcId, 'Invalid 1st class selected.');
        }
        if ($level1 < 0 || $level1 > 30) {
            redirectBack($pcId, '1st class level must be between 0 and 30.');
        }
        if (mb_strlen($subclass1) > 15) {
            redirectBack($pcId, 'Subclass must be 15 characters or fewer.');
        }
        if ($enable2) {
            if (!in_array($class2, $classList, true)) {
                redirectBack($pcId, 'Invalid 2nd class selected.');
            }
            if ($level2 < 0 || $level2 > 30) {
                redirectBack($pcId, '2nd class level must be between 0 and 30.');
            }
            if (mb_strlen($subclass2) > 15) {
                redirectBack($pcId, '2nd subclass must be 15 characters or fewer.');
            }
        }
        if ($enable3) {
            if (!in_array($class3, $classList, true)) {
                redirectBack($pcId, 'Invalid 3rd class selected.');
            }
            if ($level3 < 0 || $level3 > 30) {
                redirectBack($pcId, '3rd class level must be between 0 and 30.');
            }
            if (mb_strlen($subclass3) > 15) {
                redirectBack($pcId, '3rd subclass must be 15 characters or fewer.');
            }
        }

        $upsert = $pdo->prepare(
            'INSERT INTO pc_class
                (PCID, Class, SubClass, Level, `2nd Class`, `2nd SubClass`, `2nd Level`, `3rd Class`, `3rd SubClass`, `3rd Level`)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                Class = VALUES(Class), SubClass = VALUES(SubClass), Level = VALUES(Level),
                `2nd Class` = VALUES(`2nd Class`), `2nd SubClass` = VALUES(`2nd SubClass`), `2nd Level` = VALUES(`2nd Level`),
                `3rd Class` = VALUES(`3rd Class`), `3rd SubClass` = VALUES(`3rd SubClass`), `3rd Level` = VALUES(`3rd Level`)'
        );
        $upsert->execute([
            $pcId, $class1, $subclass1, $level1,
            $class2, $subclass2, $level2,
            $class3, $subclass3, $level3,
        ]);

        redirectBack($pcId);
        break;

    case 'update_status':
        $validStatuses = ['Active','Resting','Unavailable','Travelling','Retired','Imprisoned','Banished','Deceased'];
        $newStatus = $_POST['pc_status'] ?? '';

        if (!in_array($newStatus, $validStatuses, true)) {
            redirectBack($pcId, 'Invalid status selected.');
        }

        $pdo->prepare('UPDATE pc SET PCStatus = ? WHERE PCID = ?')->execute([$newStatus, $pcId]);
        redirectBack($pcId);
        break;

    case 'update_bio':
        $alignments = ['Lawful Good','Neutral Good','Chaotic Good','Lawful Neutral','True Neutral','Chaotic Neutral'];

        $race      = trim($_POST['pc_race'] ?? '');
        $subrace   = trim($_POST['pc_subrace'] ?? '');
        $subrace   = $subrace === '' ? null : $subrace;
        $age       = (int) ($_POST['pc_age'] ?? 0);
        $alignment = $_POST['pc_alignment'] ?? '';

        if ($race === '' || mb_strlen($race) > 50) {
            redirectBack($pcId, 'Race is required and must be 50 characters or fewer.');
        }
        if (!in_array($alignment, $alignments, true)) {
            redirectBack($pcId, 'Invalid alignment selected.');
        }
        if ($age < 0) {
            redirectBack($pcId, 'Age cannot be negative.');
        }

        $bioUpsert = $pdo->prepare(
            'INSERT INTO pc_bio (PCID, Race, SubRace, Alignment, Age)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                Race = VALUES(Race), SubRace = VALUES(SubRace),
                Alignment = VALUES(Alignment), Age = VALUES(Age)'
        );
        $bioUpsert->execute([$pcId, $race, $subrace, $alignment, $age]);

        redirectBack($pcId);
        break;

    case 'update_ability_scores':
        $str  = (int) ($_POST['pc_str'] ?? 10);
        $dex  = (int) ($_POST['pc_dex'] ?? 10);
        $con  = (int) ($_POST['pc_con'] ?? 10);
        $int_ = (int) ($_POST['pc_int'] ?? 10);
        $wis  = (int) ($_POST['pc_wis'] ?? 10);
        $cha  = (int) ($_POST['pc_cha'] ?? 10);

        foreach (['Strength' => $str, 'Dexterity' => $dex, 'Constitution' => $con, 'Intelligence' => $int_, 'Wisdom' => $wis, 'Charisma' => $cha] as $label => $val) {
            if ($val < 1 || $val > 30) {
                redirectBack($pcId, "$label must be between 1 and 30.");
            }
        }

        $statUpsert = $pdo->prepare(
            'INSERT INTO pc_stat (PCID, STR, DEX, CON, `INT`, WIS, CHA)
             VALUES (?, ?, ?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
                STR = VALUES(STR), DEX = VALUES(DEX), CON = VALUES(CON),
                `INT` = VALUES(`INT`), WIS = VALUES(WIS), CHA = VALUES(CHA)'
        );
        $statUpsert->execute([$pcId, $str, $dex, $con, $int_, $wis, $cha]);

        redirectBack($pcId);
        break;

    case 'add_item':
        $inventoryId = (int) ($_POST['inventory_id'] ?? 0);
        $itemName    = trim($_POST['item_name'] ?? '');
        $quantity    = max(1, (int) ($_POST['quantity'] ?? 1));

        if (!findOwnedInventory($pdo, $inventoryId, $pcId)) {
            redirectBack($pcId, 'That inventory does not belong to this character.');
        }

        $itemStmt = $pdo->prepare('SELECT ItemID FROM shop WHERE ItemName = ?');
        $itemStmt->execute([$itemName]);
        $item = $itemStmt->fetch();

        if (!$item) {
            redirectBack($pcId, "Item \"$itemName\" doesn't match any known item. Pick one from the list.");
        }

        $existing = $pdo->prepare(
            'SELECT InventoryItemID, Quantity FROM inventory_item WHERE InventoryID = ? AND ItemID = ?'
        );
        $existing->execute([$inventoryId, $item['ItemID']]);
        $row = $existing->fetch();

        if ($row) {
            $newQty = $row['Quantity'] + $quantity;
            $pdo->prepare('UPDATE inventory_item SET Quantity = ? WHERE InventoryItemID = ?')
                ->execute([$newQty, $row['InventoryItemID']]);
        } else {
            $pdo->prepare('INSERT INTO inventory_item (InventoryID, ItemID, Quantity) VALUES (?, ?, ?)')
                ->execute([$inventoryId, $item['ItemID'], $quantity]);
        }

        redirectBack($pcId);
        break;

    case 'update_item_qty':
        $invItemId = (int) ($_POST['inventory_item_id'] ?? 0);
        $quantity  = (int) ($_POST['quantity'] ?? 0);

        // Only touch this row if its inventory actually belongs to this PC.
        $check = $pdo->prepare(
            'SELECT ii.InventoryItemID
             FROM inventory_item ii
             JOIN inventory i ON ii.InventoryID = i.InventoryID
             WHERE ii.InventoryItemID = ? AND i.PCID = ?'
        );
        $check->execute([$invItemId, $pcId]);
        if (!$check->fetch()) {
            redirectBack($pcId, 'Item not found for this character.');
        }

        if ($quantity <= 0) {
            $pdo->prepare('DELETE FROM inventory_item WHERE InventoryItemID = ?')->execute([$invItemId]);
        } else {
            $pdo->prepare('UPDATE inventory_item SET Quantity = ? WHERE InventoryItemID = ?')
                ->execute([$quantity, $invItemId]);
        }

        redirectBack($pcId);
        break;

    case 'remove_item':
        $invItemId = (int) ($_POST['inventory_item_id'] ?? 0);

        $check = $pdo->prepare(
            'SELECT ii.InventoryItemID
             FROM inventory_item ii
             JOIN inventory i ON ii.InventoryID = i.InventoryID
             WHERE ii.InventoryItemID = ? AND i.PCID = ?'
        );
        $check->execute([$invItemId, $pcId]);
        if (!$check->fetch()) {
            redirectBack($pcId, 'Item not found for this character.');
        }

        $pdo->prepare('DELETE FROM inventory_item WHERE InventoryItemID = ?')->execute([$invItemId]);

        redirectBack($pcId);
        break;

    case 'add_inventory':
        $invName = trim($_POST['inventory_name'] ?? '');

        if ($invName === '' || mb_strlen($invName) > 50) {
            redirectBack($pcId, 'Inventory name is required and must be 50 characters or fewer.');
        }

        $pdo->prepare('INSERT INTO inventory (PCID, InventoryName) VALUES (?, ?)')
            ->execute([$pcId, $invName]);

        redirectBack($pcId);
        break;

    default:
        redirectBack($pcId, 'Unknown action.');
}
