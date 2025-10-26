<?php
/**
 * Security Configuration File
 * Handles session management, CSRF protection, and security headers
 */

// Start session with secure configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
    ini_set('session.cookie_samesite', 'Strict');
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['username']) && isset($_SESSION['role']);
}

/**
 * Check session timeout (30 minutes of inactivity)
 */
function checkSessionTimeout() {
    $timeout = 1800; // 30 minutes
    
    if (isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > $timeout) {
            session_unset();
            session_destroy();
            header("Location: login.php?timeout=1");
            exit();
        }
    }
    
    // Update last activity time
    $_SESSION['login_time'] = time();
}

/**
 * Validate session integrity
 * Checks if IP address and user agent match
 */
function validateSession() {
    if (!isLoggedIn()) {
        return false;
    }
    
    // Check IP address
    if (isset($_SESSION['user_ip'])) {
        if ($_SESSION['user_ip'] !== $_SERVER['REMOTE_ADDR']) {
            session_unset();
            session_destroy();
            header("Location: login.php?error=session_invalid");
            exit();
        }
    }
    
    // Check user agent
    if (isset($_SESSION['user_agent'])) {
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            session_unset();
            session_destroy();
            header("Location: login.php?error=session_invalid");
            exit();
        }
    }
    
    return true;
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Require authentication and specific role
 */
function requireAuth($allowed_roles = []) {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
    
    checkSessionTimeout();
    validateSession();
    
    if (!empty($allowed_roles)) {
        if (!in_array($_SESSION['role'], $allowed_roles)) {
            header("Location: login.php?error=unauthorized");
            exit();
        }
    }
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Set security headers
 */
function setSecurityHeaders() {
    // Prevent clickjacking
    header("X-Frame-Options: DENY");
    
    // Enable XSS protection
    header("X-XSS-Protection: 1; mode=block");
    
    // Prevent MIME type sniffing
    header("X-Content-Type-Options: nosniff");
    
    // Referrer policy
    header("Referrer-Policy: strict-origin-when-cross-origin");
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://fonts.googleapis.com https://fonts.gstatic.com data:;");
}

/**
 * Logout user securely
 */
function logoutUser() {
    session_unset();
    session_destroy();
    
    // Clear session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }
    
    header("Location: login.php");
    exit();
}

// Apply security headers
setSecurityHeaders();

/**
 * Database connection with error handling
 */
function getDatabaseConnection() {
    $conn = new mysqli("localhost", "root", "", "chatbot");
    
    if ($conn->connect_error) {
        error_log("Database connection failed: " . $conn->connect_error);
        die("Connection failed. Please try again later.");
    }
    
    $conn->set_charset("utf8mb4");
    return $conn;
}

/**
 * Close database connection
 */
function closeDatabaseConnection($conn) {
    if ($conn && !$conn->connect_error) {
        $conn->close();
    }
}
?>
