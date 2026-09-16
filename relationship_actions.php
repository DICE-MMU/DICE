<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin(); // Ensure active session authentication

$action    = $_POST['action'] ?? '';
$subjectId = (int) ($_POST['pc_id'] ?? 0);
$tab       = in_array($_POST['tab'] ?? '', ['PC','NPC','Faction'], true) ? $_POST['tab'] : 'PC';

// 1. Fetch cross-referenced owner IDs of the target PC subject
$check = $pdo->prepare('SELECT UserID FROM pc WHERE PCID = ?');
$check->execute([$subjectId]);
$pcRecord = $check->fetch();

if (!$pcRecord) {
    die('Subject character entity does not exist.');
}

$isOwner = ((int)$pcRecord['UserID'] === (int)$_SESSION['UserID']);
$isUserDM = isDM($pdo, $_SESSION['UserID']);

// 2. Strict Privilege Rule Framework Enforcement
if (!$isOwner) {
    // If the active execution environment is NOT triggered by the owner, they must be a DM
    // Additionally, the targeted write array CANNOT be PC (Strict PC-to-PC boundary exclusion)
    if (!$isUserDM || $tab === 'PC') {
        die('Access Denied: Administrative security perimeter breach.');
    }
}

function redirectBack(int $subjectId, string $tab, string $error = ''): void
{
    $url = "relationships.php?pc_id=$subjectId&tab=$tab";
    if ($error !== '') {
        $url .= '&error=' . urlencode($error);
    }
    header('Location: ' . $url);
    exit;
}

$targetTable = ['PC' => 'pc', 'NPC' => 'npc', 'Faction' => 'faction'][$tab];
$targetKey   = ['PC' => 'PCID', 'NPC' => 'NPCID', 'Faction' => 'FactionID'][$tab];

switch ($action) {

    case 'add_relationship':
        $targetId = (int) ($_POST['target_id'] ?? 0);
        $bubbleId = (int) ($_POST['bubble_id'] ?? 0);
        $note     = trim($_POST['note'] ?? '');

        // Prevent setting up mapping connections targeting oneself
        if ($tab === 'PC' && $targetId === $subjectId) {
            redirectBack($subjectId, $tab, 'Cannot construct self-referencing links.');
        }

        $targetCheck = $pdo->prepare("SELECT $targetKey FROM $targetTable WHERE $targetKey = ?");
        $targetCheck->execute([$targetId]);
        if (!$targetCheck->fetch()) {
            redirectBack($subjectId, $tab, 'The targeted component no longer exists.');
        }

        $bubbleCheck = $pdo->prepare('SELECT BubbleID FROM relationship_bubble WHERE BubbleID = ?');
        $bubbleCheck->execute([$bubbleId]);
        if (!$bubbleCheck->fetch()) {
            redirectBack($subjectId, $tab, 'Invalid bubble classification identity.');
        }

        $ins = $pdo->prepare(
            'INSERT INTO relationship (SubjectType, SubjectID, TargetType, TargetID, BubbleID, Note)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $ins->execute(['PC', $subjectId, $tab, $targetId, $bubbleId, $note]);

        redirectBack($subjectId, $tab);
        break;

    case 'update_relationship':
        $relationshipId = (int) ($_POST['relationship_id'] ?? 0);
        $bubbleId       = (int) ($_POST['bubble_id'] ?? 0);
        $note           = trim($_POST['note'] ?? '');

        $bubbleCheck = $pdo->prepare('SELECT BubbleID FROM relationship_bubble WHERE BubbleID = ?');
        $bubbleCheck->execute([$bubbleId]);
        if (!$bubbleCheck->fetch()) {
            redirectBack($subjectId, $tab, 'Invalid bubble classification identity.');
        }

        // Apply mutations across specific constraints
        $upd = $pdo->prepare(
            'UPDATE relationship
             SET BubbleID = ?, Note = ?
             WHERE RelationshipID = ? AND SubjectType = ? AND SubjectID = ? AND TargetType = ?'
        );
        $upd->execute([$bubbleId, $note, $relationshipId, 'PC', $subjectId, $tab]);

        redirectBack($subjectId, $tab);
        break;

    default:
        redirectBack($subjectId, $tab, 'Unknown application processing directive.');
}