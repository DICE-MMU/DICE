<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin(); // Ensure user session exists

$isUserDM = isDM($pdo, $_SESSION['UserID']);

// 1. Fetch character pool depending on user authorization role
if ($isUserDM) {
    // DMs can evaluate or pull records for all system characters globally
    $userPCs = $pdo->query('SELECT PCID, PCName, UserID FROM pc ORDER BY PCName ASC')->fetchAll();
} else {
    // Normal users are restricted entirely to their own collection entries
    $userPCs = $pdo->prepare('SELECT PCID, PCName, UserID FROM pc WHERE UserID = ? ORDER BY PCName ASC');
    $userPCs->execute([$_SESSION['UserID']]);
    $userPCs = $userPCs->fetchAll();
}

// BYPASS LAYER: Check if pool is empty. If they are a DM, let them pass through.
if (empty($userPCs)) {
    if ($isUserDM) {
        die('The database currently contains zero player characters. A player must create one before the DM can manage relationship files.');
    } else {
        die('You need a character first. <a href="create_pc.php">Create one</a>.');
    }
}

// 2. Resolve selected active Subject Character context
$subjectId = (int) ($_GET['pc_id'] ?? $userPCs[0]['PCID']);

// Safely pull details of the selected subject
$subjectMeta = null;
foreach ($userPCs as $p) {
    if ((int) $p['PCID'] === $subjectId) {
        $subjectMeta = $p;
        break;
    }
}
if (!$subjectMeta) {
    // Direct fallback path if an illegal ID was manually input into the query parameter
    $subjectMeta = $userPCs[0];
    $subjectId = (int) $subjectMeta['PCID'];
}

// Check character ownership lineage
$isOwner = ((int)$subjectMeta['UserID'] === (int)$_SESSION['UserID']);

// 3. Apply operational constraints to the visibility layers
$allowedTabs = ['PC', 'NPC', 'Faction'];
if (!$isOwner && $isUserDM) {
    // Rule Enforcement: DMs monitoring other players' characters cannot access PC-to-PC records
    $allowedTabs = ['NPC', 'Faction'];
}

$tab = in_array($_POST['tab'] ?? $_GET['tab'] ?? '', $allowedTabs, true) ? ($_POST['tab'] ?? $_GET['tab']) : $allowedTabs[0];

$bubbles = $pdo->query('SELECT BubbleID, BubbleName, BubbleCategory FROM relationship_bubble ORDER BY BubbleCategory, BubbleName')->fetchAll();

$categoryColors = [
    'Positive'    => '#3f6f4a', 'Negative'    => '#7a2626', 'Romantic'    => '#a83e7a',
    'Passive'     => '#555b63', 'Conflictive' => '#9c7a1f',
];

$bubbleStyles = [
    "Haven't Met"    => ['bg'=>'#d9d9d9','fg'=>'#6e6e6e'],
    '? Curious ?'    => ['bg'=>'#cfe8e8','fg'=>'#2f7a7a','italic'=>true],
    '? Confused ?'   => ['bg'=>'#c9c9c9','fg'=>'#7a7a7a','italic'=>true],
    "Don't care"     => ['bg'=>'#f5eeda','fg'=>'#b08d4a','italic'=>true],
    'Chill'          => ['bg'=>'#f2f2f2','fg'=>'#222222'],
    'Mixed feelings' => ['bg'=>'#d9d9d9','fg'=>'#6e6e6e'],
    'Suspicious'     => ['bg'=>'#bfbfbf','fg'=>'#555555','italic'=>true],
    'Cautious'       => ['bg'=>'#6e6e6e','fg'=>'#ffffff','bold'=>true],
    'Neutral'        => ['bg'=>'#9e9e9e','fg'=>'#ffffff'],
    'Jealous'        => ['bg'=>'#e8c99a','fg'=>'#c07a1f','italic'=>true],
    'Awkward'        => ['bg'=>'#c9a97a','fg'=>'#7a4a1f','italic'=>true],
    'Anxious'        => ['bg'=>'#8a5a2a','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    '! Amused !'     => ['bg'=>'#a83e5a','fg'=>'#ffffff','bold'=>true],
    'Apathy'         => ['bg'=>'#000000','fg'=>'#ffffff','bold'=>true],
    'Rivals'         => ['bg'=>'#3f6fa8','fg'=>'#ffffff','italic'=>true],
    'Like'           => ['bg'=>'#d9edc8','fg'=>'#3f8f3f'],
    'Friendly'       => ['bg'=>'#c8e6b0','fg'=>'#3f8f3f'],
    'Fond'           => ['bg'=>'#8fc76a','fg'=>'#1f5f1f'],
    'Friends'        => ['bg'=>'#4f9f4f','fg'=>'#ffffff','bold'=>true],
    'Close friends'  => ['bg'=>'#2f8f6f','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    'Best friend'    => ['bg'=>'#1f6f3f','fg'=>'#ffffff','bold'=>true],
    'Trust'          => ['bg'=>'#e08a2f','fg'=>'#ffffff'],
    'Acquaintance'   => ['bg'=>'#cddce8','fg'=>'#3f6f9f'],
    'Respect'        => ['bg'=>'#4f8f5f','fg'=>'#ffffff'],
    'Empathy'        => ['bg'=>'#cfe0f2','fg'=>'#3f6faf','italic'=>true],
    'Admire'         => ['bg'=>'#f5d9b0','fg'=>'#c07a1f','italic'=>true],
    'Family'         => ['bg'=>'#c8e6b0','fg'=>'#2f6f2f','bold'=>true],
    'Determination'  => ['bg'=>'#000000','fg'=>'#c0392b','bold'=>true],
    'Dislike'        => ['bg'=>'#f2c9c9','fg'=>'#c0392b'],
    'Annoyed'        => ['bg'=>'#eaa38a','fg'=>'#b0451f'],
    'Ignore'         => ['bg'=>'#f2c9c9','fg'=>'#c0392b'],
    'HATE'           => ['bg'=>'#e03030','fg'=>'#000000','bold'=>true],
    'DETEST'         => ['bg'=>'#8f1f1f','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    'ENRAGED'        => ['bg'=>'#5a1414','fg'=>'#e08a2f','bold'=>true,'italic'=>true],
    'Bitter'         => ['bg'=>'#c78a4a','fg'=>'#4a2f14'],
    'Distrust'       => ['bg'=>'#8a5a3a','fg'=>'#ffffff'],
    'Disgust'        => ['bg'=>'#a85a1f','fg'=>'#ffffff','bold'=>true],
    'Fear'           => ['bg'=>'#3f2f5a','fg'=>'#ffffff','italic'=>true],
    'Horrified'      => ['bg'=>'#3f2f5a','fg'=>'#ffffff','italic'=>true],
    'Uncomfortable'  => ['bg'=>'#2f5a8a','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    'Anxiety'        => ['bg'=>'#000000','fg'=>'#8a5aff','italic'=>true],
    'Crush ♡'             => ['bg'=>'#f2b8cf','fg'=>'#d0447a','italic'=>true],
    'Lovers ♡♡'           => ['bg'=>'#f2a8c0','fg'=>'#c0304f'],
    'Simp ♡♡♡'            => ['bg'=>'#f2a8c0','fg'=>'#c0304f','bold'=>true],
    '♡♡ Obsessed ♡♡'      => ['bg'=>'#e0308a','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    'Platonic Love'       => ['bg'=>'#f5eec0','fg'=>'#c07a1f'],
    '♡♡♡ Lovestruck ♡♡♡'  => ['bg'=>'#e0509f','fg'=>'#ffffff','bold'=>true],
    '♡ Couple ♡'          => ['bg'=>'#d0447a','fg'=>'#ffffff','bold'=>true],
    'Insulted'       => ['bg'=>'#c8b8e0','fg'=>'#6a3f9f','italic'=>true],
    'Hurt'           => ['bg'=>'#7a4fa8','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    'Vengeful'       => ['bg'=>'#4a2f6f','fg'=>'#ffffff','bold'=>true,'italic'=>true],
    'Heartbroken'    => ['bg'=>'#5a2f6f','fg'=>'#e08ab0','italic'=>true],
    'Pity'           => ['bg'=>'#5a2f6f','fg'=>'#ffffff','bold'=>true],
    'Guilty'         => ['bg'=>'#2f5a8a','fg'=>'#ffffff','bold'=>true],
    'Concern'        => ['bg'=>'#1f6f6f','fg'=>'#ffffff','italic'=>true],
    "Doesn't exist"  => ['bg'=>'#9e9e9e','fg'=>'#6e6e6e','italic'=>true],
    'Traumatized'    => ['bg'=>'#14202f','fg'=>'#5a8aff','italic'=>true],
    'Mourn'          => ['bg'=>'#4a4a4a','fg'=>'#ffffff','bold'=>true],
];

function bubbleStyle(string $name, string $category, array $bubbleStyles, array $categoryColors): array
{
    if (isset($bubbleStyles[$name])) {
        $s = $bubbleStyles[$name];
        return [
            'bg'     => $s['bg'],
            'fg'     => $s['fg'],
            'bold'   => $s['bold'] ?? false,
            'italic' => $s['italic'] ?? false,
        ];
    }
    return ['bg' => $categoryColors[$category] ?? '#555', 'fg' => '#ffffff', 'bold' => false, 'italic' => false];
}

// 4. Form dynamic variables based on target entity routing configurations
$targetTable = ['PC' => 'pc', 'NPC' => 'npc', 'Faction' => 'faction'][$tab];
$targetKey   = ['PC' => 'PCID', 'NPC' => 'NPCID', 'Faction' => 'FactionID'][$tab];
$targetName  = ['PC' => 'PCName', 'NPC' => 'NPCName', 'Faction' => 'FactionName'][$tab];

// Execute extraction of standard mapped outbounds
$relStmt = $pdo->prepare(
    "SELECT r.RelationshipID, r.TargetID, t.$targetName AS TargetName,
            rb.BubbleID, rb.BubbleName, rb.BubbleCategory, r.Note
     FROM relationship r
     JOIN $targetTable t ON r.TargetID = t.$targetKey
     JOIN relationship_bubble rb ON r.BubbleID = rb.BubbleID
     WHERE r.SubjectType = 'PC' AND r.SubjectID = ? AND r.TargetType = ?
     ORDER BY t.$targetName ASC"
);
$relStmt->execute([$subjectId, $tab]);
$relationships = $relStmt->fetchAll();

$existingIds = array_column($relationships, 'TargetID');

// Determine dropdown option exclusions to prevent linking to oneself
$candidates = $pdo->query("SELECT $targetKey AS id, $targetName AS name FROM $targetTable ORDER BY $targetName ASC")->fetchAll();
$candidates = array_filter($candidates, function($c) use ($existingIds, $tab, $subjectId) {
    if ($tab === 'PC' && (int)$c['id'] === $subjectId) {
        return false;
    }
    return !in_array($c['id'], $existingIds);
});

$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Relationships Dashboard</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .wrap { max-width: 900px; margin: 20px auto; padding: 0 14px; }
        .back-link a { color: #b48a5a; text-decoration: none; font-size: 13px; }
        .card { background: #201f26; border: 1px solid #35333d; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.4); margin-top: 10px; }
        .subject-row { display: flex; align-items: center; gap: 8px; padding: 14px 18px; background: linear-gradient(135deg, #3a1a1a, #201f26 70%); border-bottom: 2px solid #7a2626; flex-wrap: wrap; }
        .subject-row span { color: #c9a97a; font-size: 12px; text-transform: uppercase; letter-spacing: 0.06em; }
        .subject-row select { padding: 6px 8px; font-size: 13px; border: 1px solid #3a3742; border-radius: 5px; background: #26242c; color: #e7e4de; }
        .tabs { display: flex; border-bottom: 1px solid #2c2a33; background: #1b1a20; }
        .tabs a { flex: 1; text-align: center; padding: 10px; font-size: 13px; text-decoration: none; color: #9b9599; border-bottom: 3px solid transparent; }
        .tabs a.active { color: #f2ede4; border-color: #7a2626; font-weight: 600; }
