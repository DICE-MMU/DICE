<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

if (!isDM($pdo, $_SESSION['UserID'])) {
    die('Only a current DM can add locations.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name'] ?? '');
    $type  = $_POST['type'] ?? 'Landmark';
    $notes = trim($_POST['notes'] ?? '');
    $x     = $_POST['map_x'] ?? null;
    $y     = $_POST['map_y'] ?? null;

    $validTypes = ['Residential','Commercial','Industrial','Institutional','Defensive'];

    if ($name !== '' && mb_strlen($name) <= 100 && in_array($type, $validTypes, true)
        && is_numeric($x) && is_numeric($y)) {

        $stmt = $pdo->prepare(
            'INSERT INTO location (BuildingID, BuildingName, LocationID, BuildingType, Notes, MapX, MapY)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $type, $notes, $x, $y]);
    }
}

header('Spirecrest: map_building.php');
exit;
