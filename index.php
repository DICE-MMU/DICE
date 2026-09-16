<?php
session_start();

// Already logged in? Skip the landing page.
if (!empty($_SESSION['UserID'])) {
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DICE</title>
</head>
<body>
    <h1>Welcome to DICE</h1>
    <style>
    body {
    background-image: url('Index.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover;
    }

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
  .action-link:hover { border-color: #7a2626; color: #c9a97a; }

    </style>
<!-- HTML Structure -->
<div class="auth-container">
  <a href="signup.php" class="auth-btn signup-btn">Sign Up</a>
  <a href="login.php" class="auth-btn login-btn">Log In</a>
</div>
<style>
  
/* Container to push the buttons to the right side */
.auth-container {
  display: flex;
  justify-content: flex-end;
  gap: 12px; /* Spacing between buttons */
  padding: 10px 20px;
}

/* Base button styling */
.auth-btn {
  padding: 8px 16px;
  text-decoration: none;
  font-family: sans-serif;
  font-size: 14px;
  font-weight: 600;
  border-radius: 6px;
  transition: all 0.2s ease;
}

.signup-btn {
  background-color: #fcfbfa;
  color: #333;
  border: 1px solid #007bff;
}

.signup-btn:hover {
  background-color: #b6b6b6;
  border-color: #0056b3;
}

.login-btn {
  background-color: #fcfbfa;
  color: #333;
  border: 1px solid #ccc;
}

.login-btn:hover {
  background-color: #b6b6b6;
  border-color: #adadad;
}
</style>
</body>
</html>
