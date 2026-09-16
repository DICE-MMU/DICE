<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$action = $_POST['action'] ?? '';
$userId = $_SESSION['UserID'];
$userIsDM = isDM($pdo, $userId);

header('Content-Type: application/json');

switch ($action) {

    case 'add_card':
        $sectionId  = (int) ($_POST['section_id'] ?? 0);
        $title      = trim($_POST['title'] ?? '');
        $content    = trim($_POST['content'] ?? '');
        $authorType = $_POST['author_type'] ?? '';
        $authorId   = (int) ($_POST['author_id'] ?? 0);

        if ($title === '' || mb_strlen($title) > 150) {
            http_response_code(400);
            echo json_encode(['error' => 'Title is required and must be 150 characters or fewer.']);
            exit;
        }

        $sectionCheck = $pdo->prepare('SELECT SectionID FROM board_section WHERE SectionID = ?');
        $sectionCheck->execute([$sectionId]);
        if (!$sectionCheck->fetch()) {
            http_response_code(400);
            echo json_encode(['error' => 'Section not found.']);
            exit;
        }

        // Only allow attributing the card to a PC/NPC the requester actually owns.
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
            'INSERT INTO board_card (SectionID, UserID, AuthorType, AuthorID, Title, Content)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$sectionId, $userId, $authorType, $authorId, $title, $content ?: null]);

        echo json_encode(['ok' => true, 'card_id' => $pdo->lastInsertId()]);
        break;

    case 'delete_card':
        $cardId = (int) ($_POST['card_id'] ?? 0);

        // Owners can delete their own card; a current DM can delete any card.
        if ($userIsDM) {
            $pdo->prepare('DELETE FROM board_card WHERE CardID = ?')->execute([$cardId]);
        } else {
            $pdo->prepare('DELETE FROM board_card WHERE CardID = ? AND UserID = ?')->execute([$cardId, $userId]);
        }

        echo json_encode(['ok' => true]);
        break;

    case 'add_section':
        if (!$userIsDM) {
            http_response_code(403);
            echo json_encode(['error' => 'Only a current DM can add sections.']);
            exit;
        }

        $name = trim($_POST['section_name'] ?? '');
        if ($name === '' || mb_strlen($name) > 100) {
            http_response_code(400);
            echo json_encode(['error' => 'Section name is required and must be 100 characters or fewer.']);
            exit;
        }

        $maxOrder = (int) $pdo->query('SELECT COALESCE(MAX(SortOrder), 0) FROM board_section')->fetchColumn();

        $pdo->prepare('INSERT INTO board_section (SectionName, SortOrder) VALUES (?, ?)')
            ->execute([$name, $maxOrder + 1]);

        echo json_encode(['ok' => true]);
        break;

    case 'delete_section':
        if (!$userIsDM) {
            http_response_code(403);
            echo json_encode(['error' => 'Only a current DM can delete sections.']);
            exit;
        }

        $sectionId = (int) ($_POST['section_id'] ?? 0);
        $pdo->prepare('DELETE FROM board_section WHERE SectionID = ?')->execute([$sectionId]);

        echo json_encode(['ok' => true]);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action.']);
}
