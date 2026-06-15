<?php
// C:\travel\function.php
// Core database connection and utility functions

require_once(__DIR__ . '/config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function makeconnection() {
    $cn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        // In production, log this instead of echoing
        error_log("DB Connection Error: " . mysqli_connect_error());
        die("Database connection failed. Please try again later.");
    }
    return $cn;
}

$cn = makeconnection();

/**
 * Sanitizes input for legacy queries using mysqli_real_escape_string.
 * Prefer prepared statements (prepare_query) for new code.
 */
function sanitize($data) {
    global $cn;
    if (!$cn) {
        $cn = makeconnection();
    }
    return mysqli_real_escape_string($cn, trim($data));
}

/**
 * XSS-safe output encoding for all user-facing HTML output.
 * Always use this when echoing database content into HTML.
 */
function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Execute a prepared statement safely.
 * @param mysqli $cn    — DB connection
 * @param string $sql   — Query with ? placeholders
 * @param string $types — Type string e.g. "ssi"
 * @param array  $params — Bound parameter values
 * @return mysqli_result|bool
 */
function prepare_query($cn, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($cn) . " | SQL: $sql");
        return false;
    }
    if ($types && $params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/**
 * Execute a prepared INSERT/UPDATE/DELETE statement.
 * Returns true on success, false on failure.
 */
function prepare_exec($cn, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($cn) . " | SQL: $sql");
        return false;
    }
    if ($types && $params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    return mysqli_stmt_execute($stmt);
}

/**
 * Generate a CSRF token and store it in session.
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify a submitted CSRF token.
 */
function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die("Invalid CSRF token. Please go back and try again.");
    }
}
?>