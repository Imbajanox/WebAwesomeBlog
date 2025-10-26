<?php
require_once 'config.php';
require_once 'includes/database.php';
require_once 'includes/posts.php';

$database = new Database();
$db = $database->getConnection();

$posts = getAllPosts($db, true); // Get only published posts
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programming Blog</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/webawesome.css">
	<link rel="stylesheet" href="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/styles/themes/awesome.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script type="module" src="https://early.webawesome.com/webawesome@3.0.0-beta.6/dist/webawesome.loader.js"></script>
</head>
<body>
  <header class="site-header" id="site-header">
    <div class="container header-inner">
      <a class="brand" href="index.php" aria-label="Zur Startseite">
        <span class="brand-icon"><i class="fa-solid fa-code"></i></span>
        <span class="brand-text">Programming Blog</span>
      </a>

      <nav class="nav" aria-label="Hauptnavigation">
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
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
    <section id="home" class="hero">
      <div class="container hero-inner">
        <div class="hero-content">
          <h1>Willkommen in meinem Programming Blog</h1>
          <p class="lead">Gedanken zu Code, Algorithmen und modernen Technologien — kompakt und verständlich erklärt.</p>
          <div class="hero-ctas">
            <wa-button variant="primary" aria-label="Neueste Beiträge ansehen">Neueste Beiträge</wa-button>
            <wa-button variant="ghost" onclick="document.getElementById('about').scrollIntoView({behavior:'smooth'})">Über mich</wa-button>
          </div>
        </div>

        <div class="hero-visual" aria-hidden="true">
          <!-- simple SVG illustration as placeholder -->
          <svg width="360" height="220" viewBox="0 0 360 220" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="360" height="220" rx="12" fill="url(#g)"/>
            <g opacity="0.12" fill="#fff">
              <rect x="28" y="34" width="120" height="16" rx="6"></rect>
              <rect x="28" y="60" width="200" height="12" rx="6"></rect>
              <rect x="28" y="84" width="160" height="12" rx="6"></rect>
            </g>
            <defs>
              <linearGradient id="g" x1="0" x2="1" y1="0" y2="1">
                <stop stop-color="var(--primary)" offset="0%"/>
                <stop stop-color="var(--accent)" offset="100%"/>
              </linearGradient>
            </defs>
          </svg>
        </div>
      </div>
    </section>

    <section class="container posts-section" aria-labelledby="posts-heading">
      <h2 id="posts-heading" class="section-title">Aktuelle Beiträge</h2>
      <p class="section-sub">Kleine Lerneinheiten, Konzepte und Best Practices.</p>

      <div class="posts-grid" role="list">
        <?php if (count($posts) > 0): ?>
          <?php foreach ($posts as $post): ?>
            <article class="post-card" role="listitem">
              <?php if ($post['image_url']): ?>
                <div class="post-media" aria-hidden="true">
                  <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="">
                </div>
              <?php endif; ?>
              <div class="post-body">
                <div class="post-meta">
                  <?php if ($post['category_name']): ?>
                    <wa-badge variant="<?php echo htmlspecialchars($post['category_color']); ?>">
                      <?php echo htmlspecialchars($post['category_name']); ?>
                    </wa-badge>
                  <?php endif; ?>
                  <time datetime="<?php echo date('Y-m-d', strtotime($post['created_at'])); ?>">
                    <?php echo date('M d, Y', strtotime($post['created_at'])); ?>
                  </time>
                </div>
                <h3 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                <p class="post-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                <div class="post-actions">
                  <a href="post.php?slug=<?php echo urlencode($post['slug']); ?>" style="text-decoration: none;">
                    <wa-button variant="primary" aria-label="Read more about <?php echo htmlspecialchars($post['title']); ?>">
                      <i class="fa-solid fa-book-open-reader"></i> Weiterlesen
                    </wa-button>
                  </a>
                  <wa-button variant="ghost" aria-label="Share"><i class="fa-solid fa-share-nodes"></i></wa-button>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; color: var(--muted);">
            <i class="fa-solid fa-inbox" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5; display: block;"></i>
            <p>Noch keine Beiträge veröffentlicht.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section id="about" class="container about-section" aria-labelledby="about-heading">
      <h2 id="about-heading" class="section-title">About Me</h2>
      <div class="about-grid">
        <wa-card class="profile-card" aria-label="Über mich">
          <div class="profile-inner">
            <div>
              <h3>Imbajanox</h3>
              <p class="muted">Passionate developer • Backend • Frontend • Curious about new stuff</p>
              <div class="skills">
                <wa-badge variant="light"><i class="fa-brands fa-js-square"></i> JavaScript</wa-badge>
                <wa-badge variant="light"><i class="fa-brands fa-php"></i> PHP</wa-badge>
                <wa-badge variant="light"><i class="fa-brands fa-css3"></i> CSS</wa-badge>
				<wa-badge variant="light"> C#</wa-badge>
				<wa-badge variant="light"> MYSQL</wa-badge>
              </div>
            </div>
          </div>
        </wa-card>

        <div class="about-text">
          <p>Ich schreibe kurze, praxisorientierte Artikel, die komplexe Themen verständlich erklären sollen. Hier findest du Tutorials, Code-Beispiele und Gedanken zu sauberem Code und modernen Patterns.</p>
        </div>
      </div>
    </section>

    <section id="contact" class="container contact-section" aria-labelledby="contact-heading">
      <h2 id="contact-heading" class="section-title">Contact</h2>

      <wa-card class="contact-card" aria-hidden="false">
        <form id="contact-form" class="contact-form" autocomplete="on" novalidate>
          <div class="form-row">
            <label for="name">Name <span class="req">*</span></label>
            <wa-input id="name" name="name" type="text" placeholder="Dein Name" required></wa-input>
          </div>

          <div class="form-row">
            <label for="email">Email <span class="req">*</span></label>
            <wa-input id="email" name="email" type="email" placeholder="email@beispiel.de" required></wa-input>
          </div>

          <div class="form-row">
            <label for="message">Nachricht <span class="req">*</span></label>
            <wa-textarea id="message" name="message" rows="6" placeholder="Deine Nachricht..." required></wa-textarea>
          </div>

          <div class="form-actions">
            <wa-button type="submit" variant="primary"><i class="fa-solid fa-paper-plane"></i> Senden</wa-button>
            <wa-button type="button" variant="ghost" id="form-reset">Zurücksetzen</wa-button>
          </div>

          <div id="form-feedback" role="status" aria-live="polite" class="form-feedback" hidden></div>
        </form>
      </wa-card>
    </section>
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
