<?php
require_once '../config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';

startSession();

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Bitte geben Sie Benutzername und Passwort ein.';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        if (login($username, $password, $db)) {
            header('Location: index.php');
            exit();
        } else {
            $error = 'Ungültiger Benutzername oder Passwort.';
        }
    }
}

$timeout = isset($_GET['timeout']) ? true : false;
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Programming Blog</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/webawesome.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/themes/awesome.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script type="module" src="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/webawesome.loader.js"></script>
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary) 0%, #0770e0 100%);
            padding: 20px;
        }
        .login-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-header h1 {
            margin: 12px 0 8px;
            font-size: 1.75rem;
        }
        .login-header p {
            color: var(--muted);
            margin: 0;
        }
        .login-icon {
            font-size: 48px;
            color: var(--primary);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .form-group wa-input {
            width: 100%;
        }
        .login-btn {
            width: 100%;
            margin-top: 8px;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .alert-error {
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-icon">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h1>Admin Login</h1>
                <p>Programming Blog Control Panel</p>
            </div>

            <?php if ($timeout): ?>
                <div class="alert alert-warning">
                    <i class="fa-solid fa-clock"></i> Ihre Sitzung ist abgelaufen. Bitte melden Sie sich erneut an.
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Benutzername</label>
                    <wa-input 
                        id="username" 
                        name="username" 
                        type="text" 
                        placeholder="admin" 
                        required
                        autocomplete="username"
                    ></wa-input>
                </div>

                <div class="form-group">
                    <label for="password">Passwort</label>
                    <wa-input 
                        id="password" 
                        name="password" 
                        type="password" 
                        placeholder="••••••••" 
                        required
                        autocomplete="current-password"
                    ></wa-input>
                </div>

                <wa-button type="submit" variant="primary" class="login-btn">
                    <i class="fa-solid fa-right-to-bracket"></i> Anmelden
                </wa-button>
            </form>

            <div style="text-align: center; margin-top: 24px;">
                <a href="../index.php" style="color: var(--muted); text-decoration: none;">
                    <i class="fa-solid fa-arrow-left"></i> Zurück zur Website
                </a>
            </div>
        </div>
    </div>
</body>
</html>
