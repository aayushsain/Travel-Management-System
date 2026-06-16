<?php
// debug.php: Temporary script to diagnose HTTP 500 errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>VoyageQuest Live Debug Diagnostics</h2>";
echo "<strong>PHP Version:</strong> " . phpversion() . "<br>";
echo "<strong>mysqli extension loaded:</strong> " . (extension_loaded('mysqli') ? '✅ YES' : '❌ NO') . "<br>";

require_once('config.php');
echo "<strong>DB Host:</strong> " . DB_HOST . "<br>";
echo "<strong>DB User:</strong> " . DB_USER . "<br>";
echo "<strong>DB Name:</strong> " . DB_NAME . "<br>";

echo "<h3>Attempting Database Connection...</h3>";
$cn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$cn) {
    echo "<span style='color:red;'><strong>Connection failed:</strong> " . mysqli_connect_error() . "</span><br>";
} else {
    echo "<span style='color:green;'><strong>Connection successful!</strong></span><br>";
    $res = mysqli_query($cn, "SELECT VERSION()");
    if ($res) {
        $row = mysqli_fetch_row($res);
        echo "<strong>MySQL Database Version:</strong> " . $row[0] . "<br>";
    } else {
        echo "<strong>Query failed:</strong> " . mysqli_error($cn) . "<br>";
    }
}
?>
