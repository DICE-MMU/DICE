<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$action = $_POST['action'] ?? '';
$userId = $_SESSION['UserID'];
$validColors = ['yellow', 'pink', 'blue', 'green', 'orange'];

header('Content-Type: application/json');

switch ($action) {

    case 'add_note':
        $content = trim($_POST['content'] ?? '');
        $color   = $_POST['color'] ?? 'yellow';
        $x       = (float) ($_POST['pos_x'] ?? 50);
        $y       = (float) ($_POST['pos_y'] ?? 50);
        $authorType = $_POST['author_type'] ?? '';
        $authorId   = (int) ($_POST['author_id'] ?? 0);

        if ($content === '' || mb_strlen($content) > 500) {
            http_response_code(400);
            echo json_encode(['error' => 'Note text is required and must be 500 characters or fewer.']);
            exit;
        }
        if (!in_array($color, $validColors, true)) {
            $color = 'yellow';
        }

        // Only allow attributing the note to a PC/NPC the requester actually owns.
        $authorType = in_array($authorType, ['PC', 'NPC'], true) ? $authorType : null;
        if ($authorType === 'PC') {
            $check = $pdo->prepare('SELECT PCID FROM pc WHERE PCID = ? AND UserID = ?');
            $check->execute([$authorId, $userId]);
            if (!$check->fetch()) { $authorType = null; $authorId = null; }
        } elseif ($authorType === 'NPC') {
            $check = $pdo->prepare('SELECT NPCID FROM npc WHERE NPCID = ? AND UserID = ?');
            $check->execute([$authorId, $userId]);
            if (!$check->fetch()) { $authorType = null; $authorId = null; }
        } else {
            $authorType = null;
            $authorId = null;
        }

        $stmt = $pdo->prepare(
            'INSERT INTO board_note (UserID, Content, Color, PosX, PosY, AuthorType, AuthorID)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$userId, $content, $color, $x, $y, $authorType, $authorId]);

        echo json_encode(['ok' => true, 'note_id' => $pdo->lastInsertId()]);
        break;

    case 'move_note':
        $noteId = (int) ($_POST['note_id'] ?? 0);
        $x = (float) ($_POST['pos_x'] ?? 0);
        $y = (float) ($_POST['pos_y'] ?? 0);

        // Only the owner can move their own note.
        $stmt = $pdo->prepare('UPDATE board_note SET PosX = ?, PosY = ? WHERE NoteID = ? AND UserID = ?');
        $stmt->execute([$x, $y, $noteId, $userId]);

        echo json_encode(['ok' => true]);
        break;

    case 'delete_note':
        $noteId = (int) ($_POST['note_id'] ?? 0);

        $stmt = $pdo->prepare('DELETE FROM board_note WHERE NoteID = ? AND UserID = ?');
        $stmt->execute([$noteId, $userId]);

        echo json_encode(['ok' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action.']);
}
