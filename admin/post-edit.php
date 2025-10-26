<?php
require_once '../config.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';
require_once '../includes/posts.php';

checkAuth();

$database = new Database();
$db = $database->getConnection();

$isEdit = isset($_GET['id']) && is_numeric($_GET['id']);
$post = null;
$error = '';
$success = '';

if ($isEdit) {
    $post = getPostById($db, $_GET['id']);
    if (!$post) {
        header('Location: index.php');
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $image_url = trim($_POST['image_url'] ?? '');
    $category_id = !empty($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $published = isset($_POST['published']) ? 1 : 0;
    
    // Validation
    if (empty($title)) {
        $error = 'Titel ist erforderlich.';
    } elseif (empty($content)) {
        $error = 'Inhalt ist erforderlich.';
    } else {
        // Auto-generate slug if empty
        if (empty($slug)) {
            $slug = generateSlug($title);
        } else {
            $slug = generateSlug($slug);
        }
        
        $data = [
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'image_url' => $image_url,
            'category_id' => $category_id,
            'published' => $published
        ];
        
        if ($isEdit) {
            if (updatePost($db, $post['id'], $data)) {
                header('Location: index.php?updated=1');
                exit();
            } else {
                $error = 'Fehler beim Aktualisieren des Beitrags.';
            }
        } else {
            if (createPost($db, $data, getUserId())) {
                header('Location: index.php?created=1');
                exit();
            } else {
                $error = 'Fehler beim Erstellen des Beitrags.';
            }
        }
    }
}

$categories = getAllCategories($db);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isEdit ? 'Beitrag bearbeiten' : 'Neuer Beitrag'; ?> - Admin</title>
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
            max-width: 900px;
            margin: 32px auto;
            padding: 0 20px;
        }
        .form-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 32px;
            box-shadow: var(--shadow);
        }
        .form-group {
            margin-bottom: 24px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .form-group small {
            display: block;
            color: var(--muted);
            margin-top: 6px;
            font-size: 0.9rem;
        }
        .form-group wa-input,
        .form-group wa-textarea,
        .form-group wa-select {
            width: 100%;
        }
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.95rem;
            min-height: 300px;
            resize: vertical;
        }
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            background: white;
        }
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .alert-error {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            background: #fee;
            color: #c00;
            border: 1px solid #fcc;
        }
        .page-header {
            margin-bottom: 24px;
        }
        .page-header h1 {
            margin: 0 0 8px 0;
            font-size: 1.75rem;
        }
        .page-header p {
            margin: 0;
            color: var(--muted);
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
            <div>
                <a href="index.php" style="color: white; text-decoration: none;">
                    <wa-button variant="ghost" size="small">
                        <i class="fa-solid fa-arrow-left"></i> Zurück zur Übersicht
                    </wa-button>
                </a>
            </div>
        </div>
    </header>

    <main class="admin-content">
        <div class="page-header">
            <h1>
                <i class="fa-solid fa-<?php echo $isEdit ? 'pen' : 'plus'; ?>"></i>
                <?php echo $isEdit ? 'Beitrag bearbeiten' : 'Neuer Beitrag'; ?>
            </h1>
            <p>Erstellen Sie ansprechende Inhalte für Ihren Blog.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="title">Titel *</label>
                    <wa-input 
                        id="title" 
                        name="title" 
                        type="text" 
                        placeholder="Geben Sie einen aussagekräftigen Titel ein"
                        value="<?php echo $post ? htmlspecialchars($post['title']) : ''; ?>"
                        required
                    ></wa-input>
                </div>

                <div class="form-group">
                    <label for="slug">URL-Slug</label>
                    <wa-input 
                        id="slug" 
                        name="slug" 
                        type="text" 
                        placeholder="wird-automatisch-generiert"
                        value="<?php echo $post ? htmlspecialchars($post['slug']) : ''; ?>"
                    ></wa-input>
                    <small>Lassen Sie dies leer, um automatisch aus dem Titel zu generieren.</small>
                </div>

                <div class="form-group">
                    <label for="category_id">Kategorie</label>
                    <select id="category_id" name="category_id">
                        <option value="">-- Keine Kategorie --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" 
                                <?php echo ($post && $post['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="excerpt">Kurzbeschreibung</label>
                    <wa-textarea 
                        id="excerpt" 
                        name="excerpt" 
                        rows="3" 
                        placeholder="Eine kurze Zusammenfassung des Beitrags..."
                    ><?php echo $post ? htmlspecialchars($post['excerpt']) : ''; ?></wa-textarea>
                    <small>Wird in der Beitragsübersicht angezeigt.</small>
                </div>

                <div class="form-group">
                    <label for="content">Inhalt *</label>
                    <textarea 
                        id="content" 
                        name="content" 
                        placeholder="Schreiben Sie hier den Hauptinhalt des Beitrags. Sie können HTML verwenden."
                        required
                    ><?php echo $post ? htmlspecialchars($post['content']) : ''; ?></textarea>
                    <small>Unterstützt HTML für Formatierung.</small>
                </div>

                <div class="form-group">
                    <label for="image_url">Bild-URL</label>
                    <wa-input 
                        id="image_url" 
                        name="image_url" 
                        type="url" 
                        placeholder="https://example.com/image.jpg"
                        value="<?php echo $post ? htmlspecialchars($post['image_url']) : ''; ?>"
                    ></wa-input>
                    <small>URL eines Bildes für den Beitrag (optional).</small>
                </div>

                <div class="form-group">
                    <div class="checkbox-group">
                        <input 
                            type="checkbox" 
                            id="published" 
                            name="published"
                            <?php echo ($post && $post['published']) ? 'checked' : ''; ?>
                        >
                        <label for="published" style="margin: 0; font-weight: 600;">
                            Sofort veröffentlichen
                        </label>
                    </div>
                    <small>Wenn nicht markiert, wird der Beitrag als Entwurf gespeichert.</small>
                </div>

                <div class="form-actions">
                    <wa-button type="submit" variant="primary">
                        <i class="fa-solid fa-save"></i> 
                        <?php echo $isEdit ? 'Aktualisieren' : 'Erstellen'; ?>
                    </wa-button>
                    <a href="index.php" style="text-decoration: none;">
                        <wa-button type="button" variant="ghost">
                            <i class="fa-solid fa-xmark"></i> Abbrechen
                        </wa-button>
                    </a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
