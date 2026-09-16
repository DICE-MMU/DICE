<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

if (!isDM($pdo, $_SESSION['UserID'])) {
    die('Only a current DM can add Settlements.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $x     = $_POST['map_x'] ?? null;
    $y     = $_POST['map_y'] ?? null;

        $stmt = $pdo->prepare(
            'INSERT INTO location_settlement (SettlementName, Notes, MapX, MapY)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$name, $notes, $x, $y]);
    }


header('Location: map.php');
exit;
