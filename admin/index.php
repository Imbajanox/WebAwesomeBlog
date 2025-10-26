<?php
require_once '../config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';
require_once '../includes/posts.php';

checkAuth();

$database = new Database();
$db = $database->getConnection();

// Handle post deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    deletePost($db, $_GET['delete']);
    header('Location: index.php?deleted=1');
    exit();
}

$posts = getAllPosts($db, false); // Get all posts including unpublished
$username = getUsername();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Programming Blog</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/webawesome.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/themes/awesome.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script type="module" src="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/webawesome.loader.js"></script>
    <style>
        .admin-header {
            background: linear-gradient(90deg, #071341, #0b2b6b);
            color: white;
            padding: 16px 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .admin-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-content {
            max-width: 1200px;
            margin: 32px auto;
            padding: 0 20px;
        }
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .posts-table {
            background: var(--card);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 14px 16px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background: #f9fafb;
        }
        .post-title-cell {
            font-weight: 600;
        }
        .actions {
            display: flex;
            gap: 8px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-published {
            background: #d1fae5;
            color: #065f46;
        }
        .status-draft {
            background: #fee2e2;
            color: #991b1b;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--muted);
        }
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-header-inner">
            <div>
                <h1 style="margin: 0; font-size: 1.5rem;">
                    <i class="fa-solid fa-gauge"></i> Admin Dashboard
                </h1>
            </div>
            <div class="user-menu">
                <span><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($username); ?></span>
                <a href="../index.php" style="color: white; text-decoration: none;">
                    <wa-button variant="ghost" size="small">
                        <i class="fa-solid fa-house"></i> Website
                    </wa-button>
                </a>
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <wa-button variant="ghost" size="small">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </wa-button>
                </a>
            </div>
        </div>
    </header>

    <main class="admin-content">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert">
                <i class="fa-solid fa-check-circle"></i> Beitrag erfolgreich gelöscht!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['created'])): ?>
            <div class="alert">
                <i class="fa-solid fa-check-circle"></i> Beitrag erfolgreich erstellt!
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['updated'])): ?>
            <div class="alert">
                <i class="fa-solid fa-check-circle"></i> Beitrag erfolgreich aktualisiert!
            </div>
        <?php endif; ?>

        <div class="page-title">
            <h2>Blog Beiträge verwalten</h2>
            <a href="post-edit.php" style="text-decoration: none;">
                <wa-button variant="primary">
                    <i class="fa-solid fa-plus"></i> Neuer Beitrag
                </wa-button>
            </a>
        </div>

        <div class="posts-table">
            <?php if (count($posts) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th>Titel</th>
                            <th>Kategorie</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 140px;">Datum</th>
                            <th style="width: 200px;">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><?php echo $post['id']; ?></td>
                                <td class="post-title-cell"><?php echo htmlspecialchars($post['title']); ?></td>
                                <td>
                                    <?php if ($post['category_name']): ?>
                                        <wa-badge variant="<?php echo htmlspecialchars($post['category_color']); ?>">
                                            <?php echo htmlspecialchars($post['category_name']); ?>
                                        </wa-badge>
                                    <?php else: ?>
                                        <span style="color: var(--muted);">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $post['published'] ? 'status-published' : 'status-draft'; ?>">
                                        <?php echo $post['published'] ? 'Veröffentlicht' : 'Entwurf'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d.m.Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="post-edit.php?id=<?php echo $post['id']; ?>" style="text-decoration: none;">
                                            <wa-button variant="secondary" size="small">
                                                <i class="fa-solid fa-pen"></i> Bearbeiten
                                            </wa-button>
                                        </a>
                                        <a href="?delete=<?php echo $post['id']; ?>" 
                                           onclick="return confirm('Möchten Sie diesen Beitrag wirklich löschen?');" 
                                           style="text-decoration: none;">
                                            <wa-button variant="danger" size="small">
                                                <i class="fa-solid fa-trash"></i>
                                            </wa-button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div><i class="fa-solid fa-inbox"></i></div>
                    <p>Noch keine Beiträge vorhanden.</p>
                    <a href="post-edit.php" style="text-decoration: none;">
                        <wa-button variant="primary">
                            <i class="fa-solid fa-plus"></i> Ersten Beitrag erstellen
                        </wa-button>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
