<?php
session_start();
require 'db.php';

$errors = [];
$name  = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // ---- Validation (matches column limits in the `user` table) ----
    if ($name === '' || mb_strlen($name) > 50) {
        $errors[] = 'Username is required and must be 50 characters or fewer.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) {
        $errors[] = 'A valid email address is required (max 100 characters).';
    }
    if (mb_strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Passwords do not match.';
    }

    // ---- Check email isn't already taken ----
    if (empty($errors)) {
        $check = $pdo->prepare('SELECT UserID FROM user WHERE UserEmail = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $errors[] = 'An account with that email already exists.';
        }
    }

    // ---- Insert ----
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO user (UserName, UserPassword, UserEmail) VALUES (?, ?, ?)'
        );
        $stmt->execute([$name, $hash, $email]);

        // Log the new user straight in and send them to their profile.
        $_SESSION['UserID']   = $pdo->lastInsertId();
        $_SESSION['UserName'] = $name;

        header('Location: profile.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
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
    <h1>Create an account</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" action="signup.php">
        <label>
            Username
            <input type="text" name="username" value="<?= htmlspecialchars($name) ?>" maxlength="50" required>
                <small style="color: gray; margin-left: 5px;">Please use your UNIQUE DISCORD Username!</small>
                </div>

        </label><br>

        <label>
            Email
            <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" maxlength="100" required>
        </label><br>

        <label>
            Password
            <input type="password" name="password" minlength="8" required>
        </label><br>

        <label>
            Confirm password
            <input type="password" name="confirm_password" minlength="8" required>
        </label><br>

        <button type="submit">Sign up</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>
