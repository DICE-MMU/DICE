<?php
// auth.php — shared session/permission helpers. Include after session_start()
// and after db.php (needs $pdo).

function isLoggedIn(): bool
{
    return !empty($_SESSION['UserID']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isDM(PDO $pdo, int $userId): bool
{
    $stmt = $pdo->prepare('SELECT DMUntil FROM user WHERE UserID = ?');
    $stmt->execute([$userId]);
    $row = $stmt->fetch();

    return $row && $row['DMUntil'] !== null && strtotime($row['DMUntil']) > time();
}
