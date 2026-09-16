<?php
session_start();
require 'db.php';

if (empty($_SESSION['UserID'])) {
    header('Location: signup.php');
    exit;
}

$userId = $_SESSION['UserID'];
$errors = [];
$name   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['npc_name'] ?? '');

    if ($name === '' || mb_strlen($name) > 50) {
        $errors[] = 'Character name is required and must be 50 characters or fewer.';
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            // Create the NPC first — InventoryID is nullable now, since
            // inventories point at the NPC (via inventory.NPCID) rather
            // than the NPC pointing at one single inventory.
            $stmt = $pdo->prepare(
                'INSERT INTO npc (UserID, NPCName, NPCStatus) VALUES (?, ?, ?)'
            );
            $stmt->execute([$userId, $name, 'Active']);
            $newNPCId = $pdo->lastInsertId();
            $pdo->commit();

            header('Location: profile.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('NPC creation failed: ' . $e->getMessage());
            $errors[] = 'Something went wrong creating your NPC. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Character</title>
</head>
<body>
    <h1>Create a new character</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="npc_create.php">
        <label>
            Character name
            <input type="text" name="npc_name" value="<?= htmlspecialchars($name) ?>" maxlength="50" required>
        </label><br>
        <button type="submit">Create</button>
    </form>

    <p><a href="profile.php">&laquo; Back to profile</a></p>
</body>
</html>
