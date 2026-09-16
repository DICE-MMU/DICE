<?php
session_start();
require 'db.php';

// Already logged in? Skip straight to the profile.
if (!empty($_SESSION['UserID'])) {
    header('Location: profile.php');
    exit;
}

$errors   = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $errors[] = 'Please enter both username and password.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            'SELECT UserID, UserName, UserPassword FROM user WHERE UserName = ?'
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Same generic error whether the username doesn't exist or the
        // password is wrong — don't reveal which one it was.
        if (!$user || !password_verify($password, $user['UserPassword'])) {
            $errors[] = 'Incorrect username or password.';
        } else {
            $_SESSION['UserID']   = $user['UserID'];
            $_SESSION['UserName'] = $user['UserName'];

            header('Location: profile.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log In</title>
        <style>
        * { box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #17161c; color: #e7e4de; margin: 0; }
        .wrap { max-width: 900px; margin: 20px auto; padding: 0 14px; }

        h1 {
            font-size: 24px; margin: 0 0 4px; color: #f2ede4;
        }
        .subtitle { color: #9b9599; font-size: 13px; margin: 0 0 16px; }

        .card {
            background: #201f26; border: 1px solid #35333d; border-radius: 10px;
            overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,0.4); margin-bottom: 16px;
        }
        .card-header {
            padding: 12px 18px; background: linear-gradient(135deg, #3a1a1a, #201f26 70%);
            border-bottom: 2px solid #7a2626; display: flex; justify-content: space-between; align-items: center;
        }
        .card-header h2 {
            margin: 0; font-size: 15px; text-transform: uppercase; letter-spacing: 0.06em; color: #c9a97a;
        }
        .card-header a {
            background: #7a2626; color: #fff; border: 1px solid #a83e3e; border-radius: 5px;
            padding: 6px 12px; font-size: 12.5px; text-decoration: none; font-weight: 600;
        }
        .card-header a:hover { background: #8f2d2d; }

        .roster-table-wrap { overflow-x: auto; }
        table.roster { width: 100%; border-collapse: collapse; font-size: 13.5px; }
        table.roster th {
            text-align: left; padding: 8px 18px; color: #9b9599; font-size: 11px;
            text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #2c2a33;
        }
        table.roster td { padding: 9px 18px; border-bottom: 1px solid #2c2a33; }
        table.roster tr:last-child td { border-bottom: none; }
        table.roster a { color: #e7e4de; text-decoration: none; font-weight: 600; }
        table.roster a:hover { color: #c9a97a; }

        .status-pill {
            display: inline-block; padding: 2px 9px; border-radius: 10px;
            font-size: 11px; color: #fff; text-transform: uppercase; letter-spacing: 0.03em;
        }

        .empty { color: #7d7880; font-size: 13px; padding: 14px 18px; }

        .actions-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 6px; }
        .action-link {
            background: #26242c; color: #e7e4de; border: 1px solid #3a3742; border-radius: 6px;
            padding: 8px 14px; font-size: 13px; text-decoration: none;
        }
        .action-link:hover { border-color: #7a2626; color: #c9a97a; }
    </style>
</head>
<body>
    <h1>Log in</h1>
    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <label>
            Username
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" maxlength="50" required>
        </label><br>

        <label>
            Password
            <input type="password" name="password" required>
        </label><br>

        <button type="submit">Log in</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>
</html>
