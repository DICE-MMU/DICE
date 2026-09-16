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
    $name = trim($_POST['pc_name'] ?? '');

    if ($name === '' || mb_strlen($name) > 50) {
        $errors[] = 'Character name is required and must be 50 characters or fewer.';
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            // Create the PC first — InventoryID is nullable now, since
            // inventories point at the PC (via inventory.PCID) rather
            // than the PC pointing at one single inventory.
            $stmt = $pdo->prepare(
                'INSERT INTO pc (UserID, PCName, PCStatus) VALUES (?, ?, ?)'
            );
            $stmt->execute([$userId, $name, 'Active']);
            $newPcId = $pdo->lastInsertId();

            // Give it a default "Main" inventory container.
            $pdo->prepare('INSERT INTO inventory (PCID, InventoryName) VALUES (?, ?)')
                ->execute([$newPcId, 'Backpack']);
            $mainInventoryId = $pdo->lastInsertId();

            // Keep pc.InventoryID pointed at the main one too, for anything
            // still relying on that single reference.
            $pdo->prepare('UPDATE pc SET InventoryID = ? WHERE PCID = ?')
                ->execute([$mainInventoryId, $newPcId]);

            $pdo->commit();

            header('Location: profile.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('PC creation failed: ' . $e->getMessage());
            $errors[] = 'Something went wrong creating your character. Please try again.';
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

    <form method="POST" action="pc_create.php">
        <label>
            Character name
            <input type="text" name="pc_name" value="<?= htmlspecialchars($name) ?>" maxlength="50" required>
        </label><br>
        <button type="submit">Create</button>
    </form>

    <p><a href="profile.php">&laquo; Back to profile</a></p>
</body>
</html>
