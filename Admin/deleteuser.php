<?php
// C:\travel\Admin\deleteuser.php
include('function.php');
check_admin();

$message = '';
$messageType = '';

if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $user_to_delete = trim($_POST["t1"] ?? '');
    
    // Security check: cannot delete yourself
    if (empty($user_to_delete)) {
        $message = "Please select a valid user.";
        $messageType = "danger";
    } elseif (strtolower($user_to_delete) === strtolower($_SESSION["Username"] ?? '')) {
        $message = "Security Alert: You cannot delete your own logged-in admin account.";
        $messageType = "danger";
    } else {
        $ok = prepare_exec($cn, "DELETE FROM users WHERE Username = ?", "s", [$user_to_delete]);
        if ($ok) {
            echo "<script>alert('User deleted successfully!'); window.location.href='index.php';</script>";
            exit;
        } else {
            $message = "Failed to delete user.";
            $messageType = "danger";
        }
    }
}

// Fetch users
$users_list = mysqli_query($cn, "SELECT Username FROM users ORDER BY Username");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Delete User</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="style.css?v=2.0" rel="stylesheet" type="text/css"/>
</head>
<body>

<?php include('top.php'); ?>

<div class="container">
    <div class="col-sm-3">
        <?php include('left.php'); ?>
    </div>
    
    <div class="col-sm-9 fade-in-up">
        <?php include('stats.php'); ?>
        
        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="max-width: 500px; margin: 0 auto 1.5rem auto; border-radius: 12px;">
                <strong><i class="fa-solid fa-circle-exclamation"></i></strong> <?php echo h($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" onsubmit="return confirm('Are you sure you want to permanently delete this user?');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Delete User</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select User</td>
                        <td>
                            <select name="t1" required>
                                <option value="">Select User to Delete</option>
                                <?php
                                if ($users_list) {
                                    while ($usr = mysqli_fetch_assoc($users_list)) {
                                        // Do not show the current user in delete list
                                        if (strtolower($usr['Username']) !== strtolower($_SESSION["Username"] ?? '')) {
                                            echo '<option value="' . h($usr['Username']) . '">' . h($usr['Username']) . '</option>';
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Delete User" name="sbmt" style="background: linear-gradient(135deg, #EF4444, #b91c1c) !important; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25) !important;" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php include('bottom.php'); ?>
</body>
</html>
<?php mysqli_close($cn); ?>
