<?php
// C:\travel\Admin\chstatus.php
include('function.php');
check_login();

$eid = isset($_GET["eid"]) ? (int)$_GET["eid"] : 0;
if ($eid > 0) {
    // We already have $cn loaded via function.php
    prepare_exec($cn, "UPDATE enquiry SET Statusfield = 'Confirm' WHERE Enquiryid = ?", "i", [$eid]);
}
mysqli_close($cn);
header("location:viewenquiry.php");
exit;
?>