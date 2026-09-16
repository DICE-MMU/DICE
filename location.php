<?php
session_start();
require 'db.php';

if (empty($_SESSION['UserID'])) {
    header('Location: login.php');
    exit;
}

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM location WHERE LocationID = ?');
$stmt->execute([$id]);
$location = $stmt->fetch();

if (!$location) {
    die('Location not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($location['LocationName']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($location['LocationName']) ?></h1>
    <p><strong>Type:</strong> <?= htmlspecialchars($location['LocationType']) ?></p>
    <?php if (!empty($location['Notes'])): ?>
        <p><?= nl2br(htmlspecialchars($location['Notes'])) ?></p>
    <?php endif; ?>

    <p><a href="map.php">&laquo; Back to map</a></p>
</body>
</html>
