<?php
// C:\travel\Admin\function.php
// Admin-specific functions including role-based access control

require_once(__DIR__ . '/../config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function makeconnection() {
    $cn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if (mysqli_connect_errno()) {
        error_log("DB Connection Error: " . mysqli_connect_error());
        die("Database connection failed. Please try again later.");
    }
    return $cn;
}

$cn = makeconnection();

function sanitize($data) {
    global $cn;
    if (!$cn) {
        $cn = makeconnection();
    }
    return mysqli_real_escape_string($cn, trim($data));
}

function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function prepare_query($cn, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($cn));
        return false;
    }
    if ($types && $params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

function prepare_exec($cn, $sql, $types = '', $params = []) {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        error_log("Prepare failed: " . mysqli_error($cn));
        return false;
    }
    if ($types && $params) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    return mysqli_stmt_execute($stmt);
}

function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die("Invalid CSRF token. Please go back and try again.");
    }
}

// Session security check — prevents Execution After Redirect (EAR)
function check_login() {
    if (empty($_SESSION['loginstatus']) || $_SESSION['loginstatus'] !== 'yes') {
        header("Location: loginform.php");
        exit;
    }
}

// Administrator check — prevents privilege escalation
function check_admin() {
    check_login();
    if (empty($_SESSION['usertype']) || strtolower($_SESSION['usertype']) !== 'admin') {
        header("Location: index.php");
        exit;
    }
}
?>