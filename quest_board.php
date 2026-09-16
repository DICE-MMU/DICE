<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$userIsDM = isDM($pdo, $_SESSION['UserID']);

$sections = $pdo->query('SELECT SectionID, SectionName FROM board_section ORDER BY SortOrder ASC, SectionID ASC')->fetchAll();

$cardsBySection = [];
if (!empty($sections)) {
    $cards = $pdo->query(
        "SELECT bc.CardID, bc.SectionID, bc.UserID, bc.Title, bc.Content, bc.CreatedAt,
                bc.AuthorType, bc.AuthorID, u.UserName, pc.PCName, npc.NPCName
         FROM board_card bc
         JOIN user u ON bc.UserID = u.UserID
         LEFT JOIN pc  ON bc.AuthorType = 'PC'  AND bc.AuthorID = pc.PCID
         LEFT JOIN npc ON bc.AuthorType = 'NPC' AND bc.AuthorID = npc.NPCID
         ORDER BY bc.CreatedAt DESC"
    )->fetchAll();
    foreach ($cards as $c) {
        $c['DisplayName'] = $c['PCName'] ?? $c['NPCName'] ?? $c['UserName'];
        $cardsBySection[$c['SectionID']][] = $c;
    }
}

$myPCs  = $pdo->prepare('SELECT PCID, PCName FROM pc WHERE UserID = ? ORDER BY PCName ASC');
$myPCs->execute([$_SESSION['UserID']]);
$myPCs = $myPCs->fetchAll();

$myNPCs = $pdo->prepare('SELECT NPCID, NPCName FROM npc WHERE UserID = ? ORDER BY NPCName ASC');
$myNPCs->execute([$_SESSION['UserID']]);
$myNPCs = $myNPCs->fetchAll();

function timeAgo(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 2592000) return floor($diff / 86400) . 'd ago';
    return floor($diff / 2592000) . 'mo ago';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quest Board</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .wrap { max-width: 100%; margin: 0 auto; padding: 14px; }
        .back-link a { color: #b48a5a; text-decoration: none; font-size: 13px; }
        h1 { font-size: 22px; margin: 10px 0 14px; color: #f2ede4; }

        .board-row {
            display: flex; gap: 14px; overflow-x: auto; padding-bottom: 12px;
            align-items: flex-start;
        }
        .column {
            background: #201f26; border: 1px solid #35333d; border-radius: 10px;
            min-width: 240px; max-width: 240px; flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(0,0,0,0.35);
        }
        .column-header {
            padding: 10px 12px; background: linear-gradient(135deg, #3a1a1a, #201f26 70%);
            border-bottom: 2px solid #7a2626; border-radius: 10px 10px 0 0;
            font-weight: 700; font-size: 14px; color: #f2ede4;
        }
        .column-body { padding: 10px; display: flex; flex-direction: column; gap: 8px; }

        .add-card-btn {
            width: 100%; padding: 6px; background: #26242c; border: 1px dashed #3a3742;
            border-radius: 6px; color: #9b9599; cursor: pointer; font-size: 13px;
        }
        .add-card-btn:hover { border-color: #7a2626; color: #c9a97a; }

        .card {
            background: #26242c; border: 1px solid #3a3742; border-radius: 8px; padding: 10px;
        }
        .card-meta { display: flex; align-items: center; gap: 6px; font-size: 11px; color: #9b9599; margin-bottom: 6px; }
        .card-avatar {
            width: 20px; height: 20px; border-radius: 50%; background: #3a3742;
            display: flex; align-items: center; justify-content: center; font-size: 11px;
        }
        .card-title { font-size: 13.5px; font-weight: 700; color: #f2ede4; margin: 0 0 4px; }
        .card-content { font-size: 12.5px; color: #cfcac2; line-height: 1.4; white-space: pre-wrap; word-break: break-word; }
        .card-delete {
            margin-top: 6px; font-size: 11px; background: none; border: none; color: #7d7880; cursor: pointer;
        }
        .card-delete:hover { color: #c0392b; }

        .add-card-form, .add-section-form {
            background: #1b1a20; border: 1px solid #3a3742; border-radius: 8px; padding: 10px;
            display: none;
        }
        .add-card-form input, .add-card-form textarea, .add-card-form select,
        .add-section-form input {
            width: 100%; font-size: 12.5px; padding: 6px; border-radius: 5px;
            border: 1px solid #3a3742; background: #26242c; color: #e7e4de; margin-bottom: 6px;
        }
        .form-actions { display: flex; justify-content: flex-end; gap: 6px; }
        .form-actions .save {
            background: #7a2626; color: #fff; border: 1px solid #a83e3e; border-radius: 5px;
            padding: 5px 10px; font-size: 12px; cursor: pointer;
        }
        .form-actions .cancel {
            background: transparent; color: #9b9599; border: 1px solid #3a3742; border-radius: 5px;
            padding: 5px 10px; font-size: 12px; cursor: pointer;
        }

        .add-section-col {
            min-width: 160px; flex-shrink: 0;
        }
        .add-section-btn {
            background: #26242c; border: 1px solid #3a3742; border-radius: 8px;
            padding: 10px; color: #9b9599; cursor: pointer; font-size: 13px; text-align: center;
        }
        .add-section-btn:hover { border-color: #7a2626; color: #c9a97a; }
        .section-delete {
            float: right; background: none; border: none; color: #cfcac2; cursor: pointer; font-size: 12px;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="back-link"><a href="profile.php">&laquo; Back to profile</a></div>
    <h1>Quest Board</h1>

    <div class="board-row">
        <?php foreach ($sections as $s): ?>
            <div class="column">
                <div class="column-header">
                    <?= htmlspecialchars($s['SectionName']) ?>
                    <?php if ($userIsDM): ?>
                        <button type="button" class="section-delete" data-delete-section="<?= (int) $s['SectionID'] ?>">&times;</button>
                    <?php endif; ?>
                </div>
                <div class="column-body">
                    <button type="button" class="add-card-btn" data-open-card-form="<?= (int) $s['SectionID'] ?>">+ Add card</button>

                    <form class="add-card-form" id="card-form-<?= (int) $s['SectionID'] ?>" data-section="<?= (int) $s['SectionID'] ?>">
                        <input type="text" placeholder="Title" class="card-title-input" maxlength="150" required>
                        <textarea rows="3" placeholder="Details…" class="card-content-input" maxlength="1000"></textarea>
                        <select class="card-author-select">
                            <option value="">Posting as yourself</option>
                            <?php foreach ($myPCs as $pc): ?>
                                <option value="PC:<?= (int) $pc['PCID'] ?>"><?= htmlspecialchars($pc['PCName']) ?></option>
                            <?php endforeach; ?>
                            <?php foreach ($myNPCs as $npc): ?>
                                <option value="NPC:<?= (int) $npc['NPCID'] ?>"><?= htmlspecialchars($npc['NPCName']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-actions">
                            <button type="button" class="cancel" data-close-card-form="<?= (int) $s['SectionID'] ?>">Cancel</button>
                            <button type="button" class="save" data-save-card="<?= (int) $s['SectionID'] ?>">Post</button>
                        </div>
                    </form>

                    <?php foreach ($cardsBySection[$s['SectionID']] ?? [] as $c): ?>
                        <div class="card">
                            <div class="card-meta">
                                <span class="card-avatar"><?= $c['AuthorType'] === 'NPC' ? '🙂' : '🧙' ?></span>
                                <span><?= htmlspecialchars($c['DisplayName']) ?> &middot; <?= timeAgo($c['CreatedAt']) ?></span>
                            </div>
                            <p class="card-title"><?= htmlspecialchars($c['Title']) ?></p>
                            <?php if ($c['Content']): ?>
                                <p class="card-content"><?= htmlspecialchars($c['Content']) ?></p>
                            <?php endif; ?>
                            <?php if ((int) $c['UserID'] === (int) $_SESSION['UserID'] || $userIsDM): ?>
                                <button type="button" class="card-delete" data-delete-card="<?= (int) $c['CardID'] ?>">Delete</button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($userIsDM): ?>
            <div class="add-section-col">
                <div class="add-section-btn" id="open-section-form">+ Add section</div>
                <form class="add-section-form" id="section-form">
                    <input type="text" id="section-name-input" placeholder="Section name" maxlength="100" required>
                    <div class="form-actions">
                        <button type="button" class="cancel" id="cancel-section-form">Cancel</button>
                        <button type="button" class="save" id="save-section">Add</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.querySelectorAll('[data-open-card-form]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('card-form-' + btn.dataset.openCardForm).style.display = 'block';
        btn.style.display = 'none';
    });
});
document.querySelectorAll('[data-close-card-form]').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.closeCardForm;
        document.getElementById('card-form-' + id).style.display = 'none';
        document.querySelector('[data-open-card-form="' + id + '"]').style.display = 'block';
    });
});

document.querySelectorAll('[data-save-card]').forEach(btn => {
    btn.addEventListener('click', async () => {
        const sectionId = btn.dataset.saveCard;
        const form = document.getElementById('card-form-' + sectionId);
        const title = form.querySelector('.card-title-input').value.trim();
        const content = form.querySelector('.card-content-input').value.trim();
        const authorVal = form.querySelector('.card-author-select').value;
        const [authorType, authorId] = authorVal ? authorVal.split(':') : ['', ''];

        if (!title) return;

        const res = await fetch('quest_board_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({
                action: 'add_card', section_id: sectionId, title, content,
                author_type: authorType, author_id: authorId,
            }),
        });
        if (res.ok) location.reload(); else alert('Could not post card.');
    });
});

document.querySelectorAll('[data-delete-card]').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Delete this card?')) return;
        await fetch('quest_board_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'delete_card', card_id: btn.dataset.deleteCard }),
        });
        location.reload();
    });
});

document.querySelectorAll('[data-delete-section]').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Delete this whole section and its cards?')) return;
        await fetch('quest_board_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'delete_section', section_id: btn.dataset.deleteSection }),
        });
        location.reload();
    });
});

const openSectionBtn = document.getElementById('open-section-form');
if (openSectionBtn) {
    openSectionBtn.addEventListener('click', () => {
        document.getElementById('section-form').style.display = 'block';
        openSectionBtn.style.display = 'none';
    });
    document.getElementById('cancel-section-form').addEventListener('click', () => {
        document.getElementById('section-form').style.display = 'none';
        openSectionBtn.style.display = 'block';
    });
    document.getElementById('save-section').addEventListener('click', async () => {
        const name = document.getElementById('section-name-input').value.trim();
        if (!name) return;
        await fetch('quest_board_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'add_section', section_name: name }),
        });
        location.reload();
    });
}
</script>
</body>
</html>
