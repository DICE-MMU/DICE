<?php
session_start();
require 'db.php';
require 'auth.php';

requireLogin();

$quests = $pdo->query('SELECT QuestID, QuestName FROM quest ORDER BY QuestName ASC')->fetchAll();
$users  = $pdo->query('SELECT UserID, UserName FROM user ORDER BY UserName ASC')->fetchAll();

// ---- Read filters ----
$date  = trim($_GET['date'] ?? '');
$quest = trim($_GET['quest_id'] ?? '');
$dm    = trim($_GET['dm_id'] ?? '');
$players = [];
for ($i = 1; $i <= 6; $i++) {
    $players[$i] = trim($_GET["player{$i}_id"] ?? '');
}
$hasSearched = ($date !== '' || $quest !== '' || $dm !== '' || array_filter($players) !== []);

// ---- Build the query dynamically based on which filters are set ----
$where  = [];
$params = [];

if ($date !== '')  { $where[] = 's.AttemptDate = ?'; $params[] = $date; }
if ($quest !== '') { $where[] = 's.QuestID = ?';     $params[] = (int) $quest; }
if ($dm !== '')     { $where[] = 'fd.DMID = ?';      $params[] = (int) $dm; }
foreach ($players as $i => $val) {
    if ($val !== '') {
        $where[] = "fp.Player{$i} = ?";
        $params[] = (int) $val;
    }
}

$sessions = [];
if ($hasSearched) {
    $sql = "SELECT s.SessionID, s.AttemptDate, s.Status, q.QuestName
            FROM session s
            JOIN quest q ON s.QuestID = q.QuestID
            LEFT JOIN session_feedback_dm fd ON s.SessionID = fd.SessionID
            LEFT JOIN session_feedback_player fp ON s.SessionID = fp.SessionID
            " . (!empty($where) ? 'WHERE ' . implode(' AND ', $where) : '') . "
            GROUP BY s.SessionID
            ORDER BY s.AttemptDate DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $sessions = $stmt->fetchAll();
}

// ---- Pull full feedback rows for each matched session ----
function fetchOne(PDO $pdo, string $table, int $sessionId): ?array
{
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE SessionID = ?");
    $stmt->execute([$sessionId]);
    return $stmt->fetch() ?: null;
}
function userName(PDO $pdo, ?int $id): ?string
{
    if (!$id) return null;
    $stmt = $pdo->prepare('SELECT UserName FROM user WHERE UserID = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row['UserName'] ?? null;
}

foreach ($sessions as &$s) {
    $s['dm']     = fetchOne($pdo, 'session_feedback_dm', $s['SessionID']);
    $s['module'] = fetchOne($pdo, 'session_feedback_module', $s['SessionID']);
    $s['player'] = fetchOne($pdo, 'session_feedback_player', $s['SessionID']);
    if ($s['dm']) { $s['dm']['DMName'] = userName($pdo, $s['dm']['DMID']); }
    if ($s['player']) {
        for ($i = 1; $i <= 6; $i++) {
            $s['player']["Player{$i}Name"] = userName($pdo, $s['player']["Player{$i}"]);
        }
    }
}
unset($s);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session Feedback</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .wrap { max-width: 900px; margin: 20px auto; padding: 0 14px; }
        .back-link a { color: #b48a5a; text-decoration: none; font-size: 13px; }

        h1 { font-size: 22px; margin: 14px 0 4px; color: #f2ede4; }
        .subtitle { color: #9b9599; font-size: 13px; margin: 0 0 14px; }

        .card {
            background: #201f26; border: 1px solid #35333d; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.4); margin-bottom: 16px;
        }
        .card-header {
            padding: 12px 18px; background: linear-gradient(135deg, #3a1a1a, #201f26 70%);
            border-bottom: 2px solid #7a2626;
        }
        .card-header h2 { margin: 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.06em; color: #c9a97a; }

        .filter-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;
            padding: 16px 18px; background: #1b1a20;
        }
        .filter-field { display: flex; flex-direction: column; gap: 4px; }
        .filter-field label { font-size: 10px; color: #9b9599; text-transform: uppercase; letter-spacing: 0.06em; }
        .filter-field input, .filter-field select {
            padding: 6px 8px; font-size: 13px; border: 1px solid #3a3742;
            border-radius: 5px; background: #26242c; color: #e7e4de;
        }
        .search-wrap { grid-column: 1 / -1; text-align: right; margin-top: 4px; }

        .search-btn {
            background: #7a2626; color: #fff; border: 1px solid #a83e3e; border-radius: 5px;
            padding: 8px 16px; font-size: 13px; cursor: pointer; font-weight: 600;
        }
        .search-btn:hover { background: #8f2d2d; }

        .session-card {
            background: #201f26; border: 1px solid #35333d; border-radius: 10px;
            margin-bottom: 14px; overflow: hidden;
        }
        .session-top {
            padding: 12px 18px; background: #26242c; display: flex; justify-content: space-between;
            align-items: center; flex-wrap: wrap; gap: 6px;
        }
        .session-top h3 { margin: 0; font-size: 15px; color: #f2ede4; }
        .session-meta { font-size: 12px; color: #9b9599; }

        details.fb-block { border-top: 1px solid #2c2a33; }
        details.fb-block > summary {
            cursor: pointer; padding: 10px 18px; font-size: 12.5px; font-weight: 700;
            color: #c9a97a; text-transform: uppercase; letter-spacing: 0.05em;
            background: #1b1a20; list-style: none;
        }
        details.fb-block > summary::-webkit-details-marker { display: none; }
        .fb-body { padding: 12px 18px 16px; background: #1b1a20; font-size: 13px; }
        .fb-ratings { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 10px; }
        .fb-rating { background: #26242c; border: 1px solid #3a3742; border-radius: 6px; padding: 6px 10px; text-align: center; }
        .fb-rating .n { display: block; font-size: 16px; font-weight: 700; color: #f2ede4; }
        .fb-rating .l { font-size: 10px; color: #9b9599; text-transform: uppercase; }
        .fb-text { margin: 6px 0; color: #cfcac2; line-height: 1.4; }
        .fb-text strong { color: #c9a97a; }
        .players-list { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px; }
        .player-chip { background: #26242c; border: 1px solid #3a3742; border-radius: 12px; padding: 3px 10px; font-size: 12px; }

        .empty { color: #7d7880; font-size: 13px; padding: 14px 0; }

        @media (max-width: 640px) {
            .filter-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="back-link"><a href="profile.php">&laquo; Back to profile</a></div>
    <h1>Session Feedback</h1>
    <p class="subtitle">Search by any mix of criteria — leave the rest blank.</p>

    <div class="card">
        <div class="card-header"><h2>Search</h2></div>
        <form method="GET" class="filter-grid">
            <div class="filter-field">
                <label>Date</label>
                <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
            </div>
            <div class="filter-field">
                <label>Quest</label>
                <select name="quest_id">
                    <option value="">Any</option>
                    <?php foreach ($quests as $q): ?>
                        <option value="<?= (int) $q['QuestID'] ?>" <?= $quest === (string) $q['QuestID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($q['QuestName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="filter-field">
                <label>DM</label>
                <select name="dm_id">
                    <option value="">Any</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= (int) $u['UserID'] ?>" <?= $dm === (string) $u['UserID'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($u['UserName']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="filter-field">
                    <label>Player <?= $i ?></label>
                    <select name="player<?= $i ?>_id">
                        <option value="">Any</option>
                        <?php foreach ($users as $u): ?>
                            <option value="<?= (int) $u['UserID'] ?>" <?= $players[$i] === (string) $u['UserID'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($u['UserName']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endfor; ?>

            <div class="search-wrap">
                <button type="submit" class="search-btn">Search</button>
            </div>
        </form>
    </div>

    <?php if (!$hasSearched): ?>
        <p class="empty">Set at least one filter above and hit Search.</p>
    <?php elseif (empty($sessions)): ?>
        <p class="empty">No sessions matched those filters.</p>
    <?php else: ?>
        <?php foreach ($sessions as $s): ?>
            <div class="session-card">
                <div class="session-top">
                    <h3><?= htmlspecialchars($s['QuestName']) ?></h3>
                    <span class="session-meta"><?= htmlspecialchars($s['AttemptDate']) ?> &middot; <?= htmlspecialchars($s['Status']) ?></span>
                </div>

                <?php if ($s['dm']): ?>
                    <details class="fb-block">
                        <summary>DM Feedback — <?= htmlspecialchars($s['dm']['DMName'] ?? 'Unknown') ?></summary>
                        <div class="fb-body">
                            <div class="fb-ratings">
                                <?php foreach (['Attentiveness','Fairness','Immersion','Engagement','Adaptability'] as $k): ?>
                                    <div class="fb-rating"><span class="n"><?= (int) $s['dm'][$k] ?></span><span class="l"><?= $k ?></span></div>
                                <?php endforeach; ?>
                            </div>
                            <p class="fb-text"><strong>Compliments:</strong> <?= nl2br(htmlspecialchars($s['dm']['Compliments'])) ?></p>
                            <p class="fb-text"><strong>Issues:</strong> <?= nl2br(htmlspecialchars($s['dm']['Issues'])) ?></p>
                            <p class="fb-text"><strong>Advice:</strong> <?= nl2br(htmlspecialchars($s['dm']['Advices'])) ?></p>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if ($s['module']): ?>
                    <details class="fb-block">
                        <summary>Module Feedback</summary>
                        <div class="fb-body">
                            <div class="fb-ratings">
                                <?php foreach (['Theme','Mechanics','Descriptions','Elaborations','NPCs'] as $k): ?>
                                    <div class="fb-rating"><span class="n"><?= (int) $s['module'][$k] ?></span><span class="l"><?= $k ?></span></div>
                                <?php endforeach; ?>
                            </div>
                            <p class="fb-text"><strong>Compliments:</strong> <?= nl2br(htmlspecialchars($s['module']['Compliments'])) ?></p>
                            <p class="fb-text"><strong>Issues:</strong> <?= nl2br(htmlspecialchars($s['module']['Issues'])) ?></p>
                            <p class="fb-text"><strong>Advice:</strong> <?= nl2br(htmlspecialchars($s['module']['Advices'])) ?></p>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if ($s['player']): ?>
                    <details class="fb-block">
                        <summary>Player Feedback</summary>
                        <div class="fb-body">
                            <div class="players-list">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <?php if (!empty($s['player']["Player{$i}Name"])): ?>
                                        <span class="player-chip"><?= htmlspecialchars($s['player']["Player{$i}Name"]) ?></span>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="fb-ratings">
                                <?php foreach (['Attentiveness','Cooperation','Immersion','Engagement','Decisiveness'] as $k): ?>
                                    <div class="fb-rating"><span class="n"><?= (int) $s['player'][$k] ?></span><span class="l"><?= $k ?></span></div>
                                <?php endforeach; ?>
                            </div>
                            <p class="fb-text"><strong>Compliments:</strong> <?= nl2br(htmlspecialchars($s['player']['Compliments'])) ?></p>
                            <p class="fb-text"><strong>Issues:</strong> <?= nl2br(htmlspecialchars($s['player']['Issues'])) ?></p>
                            <p class="fb-text"><strong>Advice:</strong> <?= nl2br(htmlspecialchars($s['player']['Advice'])) ?></p>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if (!$s['dm'] && !$s['module'] && !$s['player']): ?>
                    <p class="empty" style="padding-left:18px;">No feedback recorded for this session.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>
