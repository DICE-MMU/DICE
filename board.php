<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$notes = $pdo->query(
    'SELECT bn.NoteID, bn.UserID, bn.Content, bn.Color, bn.PosX, bn.PosY,
            bn.AuthorType, bn.AuthorID, u.UserName,
            pc.PCName, npc.NPCName
     FROM board_note bn
     JOIN user u ON bn.UserID = u.UserID
     LEFT JOIN pc  ON bn.AuthorType = \'PC\'  AND bn.AuthorID = pc.PCID
     LEFT JOIN npc ON bn.AuthorType = \'NPC\' AND bn.AuthorID = npc.NPCID'
)->fetchAll();

foreach ($notes as &$n) {
    $n['DisplayName'] = $n['PCName'] ?? $n['NPCName'] ?? $n['UserName'];
}
unset($n);

$myPCs  = $pdo->prepare('SELECT PCID, PCName FROM pc WHERE UserID = ? ORDER BY PCName ASC');
$myPCs->execute([$_SESSION['UserID']]);
$myPCs = $myPCs->fetchAll();

$myNPCs = $pdo->prepare('SELECT NPCID, NPCName FROM npc WHERE UserID = ? ORDER BY NPCName ASC');
$myNPCs->execute([$_SESSION['UserID']]);
$myNPCs = $myNPCs->fetchAll();

$colors = ['yellow', 'pink', 'blue', 'green', 'orange'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Board</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .wrap { max-width: 1000px; margin: 20px auto; padding: 0 14px; }
        .back-link a { color: #b48a5a; text-decoration: none; font-size: 13px; }
        h1 { font-size: 22px; margin: 14px 0 4px; color: #f2ede4; }
        .subtitle { color: #9b9599; font-size: 13px; margin: 0 0 14px; }

        #board-canvas {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 10;
            background: #201f26;
            border: 1px solid #35333d;
            border-radius: 10px;
            overflow: hidden;
            cursor: crosshair;
            background-image:
                linear-gradient(#2c2a33 1px, transparent 1px),
                linear-gradient(90deg, #2c2a33 1px, transparent 1px);
            background-size: 24px 24px;
        }

        .note {
            position: absolute;
            width: 160px;
            min-height: 100px;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            cursor: grab;
            transform: translate(-50%, -50%);
            font-size: 12.5px;
            color: #222;
            display: flex;
            flex-direction: column;
        }
        .note.dragging { cursor: grabbing; z-index: 50; opacity: 0.85; }
        .note-yellow { background: #f2e28a; }
        .note-pink   { background: #f2a8c0; }
        .note-blue   { background: #a8c8f2; }
        .note-green  { background: #b0e08a; }
        .note-orange { background: #f2b98a; }

        .note-author { font-size: 10px; opacity: 0.6; margin-bottom: 4px; }
        .note-content { flex: 1; white-space: pre-wrap; word-break: break-word; }
        .note-actions { display: flex; justify-content: flex-end; gap: 4px; margin-top: 6px; }
        .note-actions button {
            font-size: 10px; padding: 2px 6px; border: none; border-radius: 4px;
            background: rgba(0,0,0,0.15); cursor: pointer;
        }

        #new-note-form {
            position: absolute;
            background: #26242c;
            border: 1px solid #3a3742;
            border-radius: 8px;
            padding: 12px;
            width: 220px;
            display: none;
            z-index: 60;
            transform: translate(-50%, -50%);
        }
        #new-note-form textarea {
            width: 100%; font-size: 13px; padding: 6px; border-radius: 5px;
            border: 1px solid #3a3742; background: #1b1a20; color: #e7e4de; resize: vertical;
        }
        #new-note-form select {
            width: 100%; font-size: 12.5px; padding: 6px; border-radius: 5px;
            border: 1px solid #3a3742; background: #1b1a20; color: #e7e4de; margin-top: 6px;
        }
        #new-note-form .swatches { display: flex; gap: 6px; margin: 8px 0; }
        #new-note-form .swatch {
            width: 20px; height: 20px; border-radius: 50%; cursor: pointer; border: 2px solid transparent;
        }
        #new-note-form .swatch.selected { border-color: #fff; }
        #new-note-form .form-actions { display: flex; justify-content: flex-end; gap: 6px; }
        #new-note-form button.save {
            background: #7a2626; color: #fff; border: 1px solid #a83e3e; border-radius: 5px;
            padding: 6px 12px; font-size: 12px; cursor: pointer;
        }
        #new-note-form button.cancel {
            background: transparent; color: #9b9599; border: 1px solid #3a3742; border-radius: 5px;
            padding: 6px 12px; font-size: 12px; cursor: pointer;
        }

        .hint { font-size: 12px; color: #7d7880; margin-top: 8px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="back-link"><a href="profile.php">&laquo; Back to profile</a></div>
    <h1>Board</h1>
    <p class="subtitle">Click anywhere to add a note. Drag notes to move them.</p>

    <div id="board-canvas">
        <?php foreach ($notes as $n): ?>
            <div class="note note-<?= htmlspecialchars($n['Color']) ?>"
                 data-id="<?= (int) $n['NoteID'] ?>"
                 data-owner="<?= (int) $n['UserID'] ?>"
                 style="left: <?= htmlspecialchars($n['PosX']) ?>%; top: <?= htmlspecialchars($n['PosY']) ?>%;">
                <div class="note-author"><?= htmlspecialchars($n['DisplayName']) ?></div>
                <div class="note-content"><?= htmlspecialchars($n['Content']) ?></div>
                <?php if ((int) $n['UserID'] === (int) $_SESSION['UserID']): ?>
                    <div class="note-actions">
                        <button type="button" class="delete-note" data-id="<?= (int) $n['NoteID'] ?>">Delete</button>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <form id="new-note-form">
            <textarea id="new-note-text" rows="3" placeholder="Write a note…" maxlength="500"></textarea>
            <select id="new-note-author">
                <?php if (!empty($myPCs)): ?>
                    <optgroup label="Your PCs">
                        <?php foreach ($myPCs as $pc): ?>
                            <option value="PC:<?= (int) $pc['PCID'] ?>"><?= htmlspecialchars($pc['PCName']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
                <?php if (!empty($myNPCs)): ?>
                    <optgroup label="Your NPCs">
                        <?php foreach ($myNPCs as $npc): ?>
                            <option value="NPC:<?= (int) $npc['NPCID'] ?>"><?= htmlspecialchars($npc['NPCName']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                <?php endif; ?>
            </select>
            <div class="swatches">
                <?php foreach ($colors as $c): ?>
                    <div class="swatch note-<?= $c ?>" data-color="<?= $c ?>" style="background: var(--swatch-<?= $c ?>, );"></div>
                <?php endforeach; ?>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel" id="cancel-note">Cancel</button>
                <button type="button" class="save" id="save-note">Add</button>
            </div>
        </form>
    </div>

    <p class="hint">Notes are shared — everyone on the board can see them. You can only delete your own.</p>
</div>

<script>
const canvas = document.getElementById('board-canvas');
const form = document.getElementById('new-note-form');
const textArea = document.getElementById('new-note-text');
let pendingX = 0, pendingY = 0, selectedColor = 'yellow';

// Map swatch backgrounds directly (avoids relying on CSS vars above)
const swatchColors = { yellow: '#f2e28a', pink: '#f2a8c0', blue: '#a8c8f2', green: '#b0e08a', orange: '#f2b98a' };
document.querySelectorAll('.swatch').forEach(sw => {
    sw.style.background = swatchColors[sw.dataset.color];
    sw.addEventListener('click', () => {
        document.querySelectorAll('.swatch').forEach(s => s.classList.remove('selected'));
        sw.classList.add('selected');
        selectedColor = sw.dataset.color;
    });
});
document.querySelector('.swatch[data-color="yellow"]').classList.add('selected');

canvas.addEventListener('click', (e) => {
    if (e.target !== canvas) return; // ignore clicks on notes/form
    const rect = canvas.getBoundingClientRect();
    pendingX = ((e.clientX - rect.left) / rect.width) * 100;
    pendingY = ((e.clientY - rect.top) / rect.height) * 100;
    form.style.left = pendingX + '%';
    form.style.top = pendingY + '%';
    form.style.display = 'block';
    textArea.value = '';
    textArea.focus();
});

document.getElementById('cancel-note').addEventListener('click', () => {
    form.style.display = 'none';
});

document.getElementById('save-note').addEventListener('click', async () => {
    const content = textArea.value.trim();
    if (!content) return;

    const authorSelect = document.getElementById('new-note-author');
    const [authorType, authorId] = authorSelect.value ? authorSelect.value.split(':') : ['', ''];

    const res = await fetch('board_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action: 'add_note',
            content: content,
            color: selectedColor,
            pos_x: pendingX.toFixed(2),
            pos_y: pendingY.toFixed(2),
            author_type: authorType,
            author_id: authorId,
        }),
    });

    if (res.ok) {
        location.reload();
    } else {
        alert('Could not save note.');
    }
});

// ---- Dragging existing notes ----
let dragEl = null, dragOffsetX = 0, dragOffsetY = 0;

document.querySelectorAll('.note').forEach(note => {
    note.addEventListener('mousedown', (e) => {
        if (e.target.tagName === 'BUTTON') return;
        dragEl = note;
        note.classList.add('dragging');
        const rect = canvas.getBoundingClientRect();
        dragOffsetX = e.clientX;
        dragOffsetY = e.clientY;
        e.preventDefault();
    });
});

document.addEventListener('mousemove', (e) => {
    if (!dragEl) return;
    const rect = canvas.getBoundingClientRect();
    let xPercent = ((e.clientX - rect.left) / rect.width) * 100;
    let yPercent = ((e.clientY - rect.top) / rect.height) * 100;
    xPercent = Math.max(0, Math.min(100, xPercent));
    yPercent = Math.max(0, Math.min(100, yPercent));
    dragEl.style.left = xPercent + '%';
    dragEl.style.top = yPercent + '%';
});

document.addEventListener('mouseup', async () => {
    if (!dragEl) return;
    dragEl.classList.remove('dragging');
    const id = dragEl.dataset.id;
    const x = parseFloat(dragEl.style.left);
    const y = parseFloat(dragEl.style.top);

    await fetch('board_actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ action: 'move_note', note_id: id, pos_x: x, pos_y: y }),
    });

    dragEl = null;
});

// ---- Delete ----
document.querySelectorAll('.delete-note').forEach(btn => {
    btn.addEventListener('click', async () => {
        if (!confirm('Delete this note?')) return;
        await fetch('board_actions.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'delete_note', note_id: btn.dataset.id }),
        });
        location.reload();
    });
});
</script>
</body>
</html>
