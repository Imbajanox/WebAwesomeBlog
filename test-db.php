<?php
/**
 * Database Connection Test Script
 * 
 * This script helps verify that your database configuration is correct.
 * Run this file in your browser after setting up config.php
 * 
 * DELETE THIS FILE after successful setup for security!
 */

// Check if config.php exists
if (!file_exists('config.php')) {
    die('❌ Error: config.php not found. Please copy config.example.php to config.php and configure your database settings.');
}

require_once 'config.php';

echo '<html><head><title>Database Connection Test</title>';
echo '<style>body{font-family:sans-serif;max-width:800px;margin:50px auto;padding:20px;}';
echo '.success{color:green;}.error{color:red;}.warning{color:orange;}';
echo 'pre{background:#f5f5f5;padding:15px;border-radius:5px;overflow-x:auto;}';
echo 'h1{border-bottom:2px solid #333;padding-bottom:10px;}</style></head><body>';

echo '<h1>🔍 Database Connection Test</h1>';

// Test 1: Check if config constants are defined
echo '<h2>Step 1: Configuration Check</h2>';
$configOk = true;

$requiredConstants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_CHARSET'];
foreach ($requiredConstants as $constant) {
    if (defined($constant)) {
        echo "✅ <span class='success'>$constant is defined</span><br>";
    } else {
        echo "❌ <span class='error'>$constant is NOT defined</span><br>";
        $configOk = false;
    }
}

if (!$configOk) {
    die('<p class="error">Please check your config.php file.</p></body></html>');
}

// Test 2: PDO Extension Check
echo '<h2>Step 2: PDO Extension Check</h2>';
if (extension_loaded('pdo')) {
    echo "✅ <span class='success'>PDO extension is loaded</span><br>";
} else {
    die("❌ <span class='error'>PDO extension is NOT loaded. Please enable PDO in php.ini</span></body></html>");
}

if (extension_loaded('pdo_mysql')) {
    echo "✅ <span class='success'>PDO MySQL driver is loaded</span><br>";
} else {
    die("❌ <span class='error'>PDO MySQL driver is NOT loaded. Please enable pdo_mysql in php.ini</span></body></html>");
}

// Test 3: Database Connection
echo '<h2>Step 3: Database Connection Test</h2>';
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "✅ <span class='success'>Successfully connected to database: " . htmlspecialchars(DB_NAME) . "</span><br>";
    
    // Test 4: Check Tables
    echo '<h2>Step 4: Database Tables Check</h2>';
    $requiredTables = ['users', 'categories', 'posts'];
    $allTablesExist = true;
    
    foreach ($requiredTables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "✅ <span class='success'>Table '$table' exists</span><br>";
        } else {
            echo "❌ <span class='error'>Table '$table' does NOT exist</span><br>";
            $allTablesExist = false;
        }
    }
    
    if (!$allTablesExist) {
        echo '<p class="warning">⚠️ Some tables are missing. Please run schema.sql to create the database structure.</p>';
        echo '<pre>mysql -u ' . htmlspecialchars(DB_USER) . ' -p ' . htmlspecialchars(DB_NAME) . ' < schema.sql</pre>';
    }
    
    // Test 5: Check Sample Data
    if ($allTablesExist) {
        echo '<h2>Step 5: Sample Data Check</h2>';
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
        $userCount = $stmt->fetch()['count'];
        echo "ℹ️ Users in database: <strong>$userCount</strong><br>";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
        $catCount = $stmt->fetch()['count'];
        echo "ℹ️ Categories in database: <strong>$catCount</strong><br>";
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM posts");
        $postCount = $stmt->fetch()['count'];
        echo "ℹ️ Posts in database: <strong>$postCount</strong><br>";
        
        if ($userCount > 0 && $catCount > 0) {
            echo '<p class="success">✅ Database is properly set up!</p>';
        } else {
            echo '<p class="warning">⚠️ Database is empty. Run schema.sql to populate with sample data.</p>';
        }
    }
    
    echo '<h2>🎉 Setup Summary</h2>';
    echo '<p class="success"><strong>Your database is configured correctly!</strong></p>';
    echo '<ul>';
    echo '<li>Frontend: <a href="index.php">index.php</a></li>';
    echo '<li>Admin Login: <a href="admin/login.php">admin/login.php</a></li>';
    echo '<li>Default credentials: username: <code>admin</code>, password: <code>admin123</code></li>';
    echo '</ul>';
    echo '<p class="warning"><strong>⚠️ IMPORTANT SECURITY NOTES:</strong></p>';
    echo '<ol>';
    echo '<li>DELETE this test-db.php file immediately</li>';
    echo '<li>Change the default admin password</li>';
    echo '<li>Make sure config.php is not accessible from the web</li>';
    echo '</ol>';
    
} catch(PDOException $e) {
    echo "❌ <span class='error'>Connection failed: " . htmlspecialchars($e->getMessage()) . "</span><br>";
    echo '<h3>Troubleshooting:</h3>';
    echo '<ul>';
    echo '<li>Check if MySQL/MariaDB is running</li>';
    echo '<li>Verify database credentials in config.php</li>';
    echo '<li>Ensure the database exists (or run schema.sql first)</li>';
    echo '<li>Check if the database user has proper permissions</li>';
    echo '</ul>';
}

echo '</body></html>';
