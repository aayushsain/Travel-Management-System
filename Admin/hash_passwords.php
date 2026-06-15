<?php
/**
 * C:\travel\Admin\hash_passwords.php
 * ONE-TIME UTILITY: Run this ONCE to migrate plaintext passwords in the DB to bcrypt hashes.
 * After running, DELETE this file from the server for security.
 * 
 * Access: http://localhost/travel/Admin/hash_passwords.php
 * (Must be logged in as Admin or running locally)
 */

// Only allow this on localhost for security
if (!in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1', 'localhost'])) {
    http_response_code(403);
    die("Access denied. This utility only runs on localhost.");
}

include('function.php');

if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    echo "<h2>Password Migration Utility</h2>";
    echo "<p>This will convert all plaintext passwords to secure bcrypt hashes.</p>";
    echo "<p><strong>WARNING:</strong> Make sure you know the existing passwords before running.</p>";
    echo "<p><a href='?confirm=yes' style='color:red'>Click here to confirm and run migration</a></p>";
    
    // Show current users (for reference)
    $result = mysqli_query($cn, "SELECT Username, Pwd, Typeofuser FROM users");
    echo "<h3>Current Users:</h3><table border='1' cellpadding='5'>";
    echo "<tr><th>Username</th><th>Password (current)</th><th>Type</th></tr>";
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . h($row['Username']) . "</td><td>" . h($row['Pwd']) . "</td><td>" . h($row['Typeofuser']) . "</td></tr>";
    }
    echo "</table>";
    exit;
}

// Run the migration
$result = mysqli_query($cn, "SELECT Username, Pwd FROM users");
$migrated = 0;
$skipped = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $pwd = $row['Pwd'];
    $username = $row['Username'];
    
    // Skip if already a bcrypt hash (starts with $2y$)
    if (substr($pwd, 0, 4) === '$2y$') {
        $skipped++;
        echo "<p>✅ <strong>" . h($username) . "</strong>: Already hashed — skipped.</p>";
        continue;
    }
    
    // Hash the plaintext password
    $hashed = password_hash($pwd, PASSWORD_DEFAULT);
    
    // Update using prepared statement
    $ok = prepare_exec($cn, "UPDATE users SET Pwd = ? WHERE Username = ?", "ss", [$hashed, $username]);
    
    if ($ok) {
        $migrated++;
        echo "<p>🔐 <strong>" . h($username) . "</strong>: Password migrated successfully. (Old: " . h($pwd) . ")</p>";
    } else {
        echo "<p>❌ <strong>" . h($username) . "</strong>: ERROR updating password.</p>";
    }
}

echo "<hr><h3>Migration Complete: $migrated migrated, $skipped already hashed.</h3>";
echo "<p style='color:red'><strong>IMPORTANT:</strong> Delete this file (hash_passwords.php) now for security!</p>";
echo "<p><a href='index.php'>→ Back to Dashboard</a></p>";

mysqli_close($cn);
?>
