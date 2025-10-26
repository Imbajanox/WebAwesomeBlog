-- WebAwesome Blog Database Schema
-- MySQL/MariaDB compatible

-- Create database (optional - run if needed)
-- CREATE DATABASE IF NOT EXISTS webawesome_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE webawesome_blog;

-- Users table for admin authentication
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    color VARCHAR(20) DEFAULT 'info',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Posts table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) UNIQUE NOT NULL,
    excerpt TEXT,
    content TEXT NOT NULL,
    image_url VARCHAR(500),
    category_id INT,
    author_id INT NOT NULL,
    published BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_published (published),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: admin123 - CHANGE THIS!)
-- Password is hashed using PHP password_hash() with PASSWORD_DEFAULT
INSERT INTO users (username, password, email) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');

-- Insert default categories
INSERT INTO categories (name, slug, color) VALUES 
('Algorithmus', 'algorithmus', 'info'),
('ML', 'ml', 'warning'),
('Best Practices', 'best-practices', 'success'),
('Web Development', 'web-development', 'primary'),
('Database', 'database', 'danger');

-- Insert sample posts
INSERT INTO posts (title, slug, excerpt, content, image_url, category_id, author_id, published) VALUES 
(
    'Understanding Recursion',
    'understanding-recursion',
    'Rekursion hilft, Probleme elegant zu lösen — wir schauen uns Beispiele und Fallstricke an.',
    '<h2>Was ist Rekursion?</h2><p>Rekursion ist eine Programmiertechnik, bei der eine Funktion sich selbst aufruft. Dies ist besonders nützlich bei Problemen, die sich in kleinere, ähnliche Teilprobleme zerlegen lassen.</p><h3>Beispiel: Fakultät</h3><pre><code>function factorial(n) {\n    if (n <= 1) return 1;\n    return n * factorial(n - 1);\n}</code></pre><p>Die Funktion ruft sich selbst auf, bis die Basisbedingung erreicht ist.</p>',
    'https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=800&auto=format&fit=crop',
    1,
    1,
    TRUE
),
(
    'Intro to Machine Learning',
    'intro-to-machine-learning',
    'Grundbegriffe von ML leicht erklärt: Modelle, Daten, Overfitting und mehr.',
    '<h2>Einführung in Machine Learning</h2><p>Machine Learning ist ein Teilbereich der künstlichen Intelligenz, der es Computern ermöglicht, aus Daten zu lernen.</p><h3>Grundkonzepte</h3><ul><li><strong>Training:</strong> Der Prozess, bei dem das Modell aus Daten lernt</li><li><strong>Overfitting:</strong> Wenn das Modell zu gut an die Trainingsdaten angepasst ist</li><li><strong>Validation:</strong> Überprüfung der Modellleistung mit neuen Daten</li></ul>',
    'https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=800&auto=format&fit=crop',
    2,
    1,
    TRUE
),
(
    'Best Practices for Clean Code',
    'best-practices-for-clean-code',
    'Sauberer Code ist wartbar und zuverlässig — kleine Regeln mit großer Wirkung.',
    '<h2>Clean Code Prinzipien</h2><p>Sauberer Code ist einfach zu lesen, zu verstehen und zu warten.</p><h3>Wichtige Regeln</h3><ol><li>Verwende aussagekräftige Namen</li><li>Funktionen sollten nur eine Aufgabe erfüllen</li><li>Vermeide Code-Duplikation (DRY-Prinzip)</li><li>Schreibe Tests für deinen Code</li><li>Kommentiere nur, was nicht offensichtlich ist</li></ol>',
    'https://images.unsplash.com/photo-1508830524289-0adcbe822b40?q=80&w=800&auto=format&fit=crop',
    3,
    1,
    TRUE
);
