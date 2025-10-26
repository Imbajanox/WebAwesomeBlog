<?php
require_once 'config.php';
require_once 'includes/database.php';
require_once 'includes/posts.php';

$database = new Database();
$db = $database->getConnection();

$slug = $_GET['slug'] ?? '';
$post = null;

if ($slug) {
    $post = getPostBySlug($db, $slug);
}

if (!$post) {
    header('HTTP/1.0 404 Not Found');
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? htmlspecialchars($post['title']) . ' - ' : ''; ?>Programming Blog</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/webawesome.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/themes/awesome.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script type="module" src="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/webawesome.loader.js"></script>
    <style>
        .post-header {
            background: linear-gradient(135deg, var(--primary) 0%, #0770e0 100%);
            color: white;
            padding: 60px 0 40px;
            margin-bottom: 40px;
        }
        .post-header h1 {
            font-size: 2.5rem;
            margin: 0 0 16px 0;
            line-height: 1.2;
        }
        .post-meta-header {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }
        .post-meta-header time {
            opacity: 0.9;
        }
        .post-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }
        .post-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: var(--radius);
            margin-bottom: 40px;
            box-shadow: var(--shadow);
        }
        .post-body {
            font-size: 1.05rem;
            line-height: 1.8;
        }
        .post-body h2 {
            font-size: 1.75rem;
            margin: 40px 0 16px 0;
            color: var(--text);
        }
        .post-body h3 {
            font-size: 1.35rem;
            margin: 32px 0 12px 0;
            color: var(--text);
        }
        .post-body p {
            margin: 0 0 20px 0;
        }
        .post-body ul, .post-body ol {
            margin: 0 0 20px 0;
            padding-left: 28px;
        }
        .post-body li {
            margin-bottom: 8px;
        }
        .post-body pre {
            background: #f5f6f8;
            border-radius: 8px;
            padding: 20px;
            overflow-x: auto;
            margin: 20px 0;
        }
        .post-body code {
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 32px;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .not-found {
            text-align: center;
            padding: 80px 20px;
        }
        .not-found h1 {
            font-size: 3rem;
            margin: 0 0 16px 0;
            color: var(--muted);
        }
        body.dark .post-body pre {
            background: #1a1f2e;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="index.php" aria-label="Zur Startseite">
                <span class="brand-icon"><i class="fa-solid fa-code"></i></span>
                <span class="brand-text">Programming Blog</span>
            </a>

            <nav class="nav" aria-label="Hauptnavigation">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="index.php#about">About</a></li>
                    <li><a href="index.php#contact">Contact</a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <button id="theme-toggle" class="icon-btn" aria-pressed="false" aria-label="Toggle dark mode">
                    <i class="fa-regular fa-moon"></i>
                </button>
            </div>
        </div>
    </header>

    <main>
        <?php if ($post): ?>
            <div class="post-header">
                <div class="container">
                    <div class="post-meta-header">
                        <?php if ($post['category_name']): ?>
                            <wa-badge variant="light">
                                <?php echo htmlspecialchars($post['category_name']); ?>
                            </wa-badge>
                        <?php endif; ?>
                        <time datetime="<?php echo date('Y-m-d', strtotime($post['created_at'])); ?>">
                            <i class="fa-solid fa-calendar"></i>
                            <?php echo date('d. F Y', strtotime($post['created_at'])); ?>
                        </time>
                        <span>
                            <i class="fa-solid fa-user"></i>
                            <?php echo htmlspecialchars($post['author_name']); ?>
                        </span>
                    </div>
                    <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                    <?php if ($post['excerpt']): ?>
                        <p style="font-size: 1.15rem; opacity: 0.95; margin: 0;">
                            <?php echo htmlspecialchars($post['excerpt']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <article class="post-content">
                <a href="index.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> Zurück zur Übersicht
                </a>

                <?php if ($post['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($post['title']); ?>" 
                         class="post-image">
                <?php endif; ?>

                <div class="post-body">
                    <?php echo $post['content']; ?>
                </div>

                <div style="margin-top: 60px; padding-top: 40px; border-top: 1px solid #e5e7eb;">
                    <a href="index.php" class="back-link">
                        <i class="fa-solid fa-arrow-left"></i> Zurück zur Übersicht
                    </a>
                </div>
            </article>
        <?php else: ?>
            <div class="container not-found">
                <h1><i class="fa-solid fa-triangle-exclamation"></i></h1>
                <h2>Beitrag nicht gefunden</h2>
                <p style="color: var(--muted); margin-bottom: 24px;">
                    Der gesuchte Beitrag existiert nicht oder wurde entfernt.
                </p>
                <a href="index.php" style="text-decoration: none;">
                    <wa-button variant="primary">
                        <i class="fa-solid fa-house"></i> Zur Startseite
                    </wa-button>
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-inner">
            <small>&copy; 2025 Programming Blog. All rights reserved. <i class="fa-regular fa-heart"></i></small>
            <nav aria-label="Footer links" class="footer-links">
                <a href="index.php">Top</a>
                <a href="#privacy">Privacy</a>
            </nav>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
