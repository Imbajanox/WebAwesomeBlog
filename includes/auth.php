<?php
// Authentication and session management

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        session_start();
    }
}

function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

function login($username, $password, $db) {
    try {
        $stmt = $db->prepare("SELECT id, username, password FROM users WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            startSession();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['last_activity'] = time();
            return true;
        }
        return false;
    } catch(PDOException $e) {
        error_log("Login Error: " . $e->getMessage());
        return false;
    }
}

function logout() {
    startSession();
    $_SESSION = array();
    
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }
    
    session_destroy();
}

function checkAuth() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
    
    // Check session timeout
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
        logout();
        header('Location: login.php?timeout=1');
        exit();
    }
    
    $_SESSION['last_activity'] = time();
}

function getUserId() {
    startSession();
    return $_SESSION['user_id'] ?? null;
}

function getUsername() {
    startSession();
    return $_SESSION['username'] ?? null;
}
