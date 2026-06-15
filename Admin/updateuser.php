<?php
// C:\travel\Admin\updateuser.php
include('function.php');
check_admin();

$message = '';
$messageType = '';
$show_data = false;

$Username = "";
$Usertype = "";

// 1. Identify which user we are editing
$selected_user = isset($_POST["t1"]) ? trim($_POST["t1"]) : '';

// 2. Handle update submission
if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $password = $_POST["t2"] ?? '';
    $confirm  = $_POST["t3"] ?? '';
    $role     = trim($_POST["s1"] ?? '');
    
    if (strlen($password) > 0 && strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "danger";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
        $messageType = "danger";
    } elseif (!in_array($role, ['Admin', 'General'])) {
        $message = "Please select a valid user role.";
        $messageType = "danger";
    } else {
        if (strlen($password) > 0) {
            // Updating password too
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ok = prepare_exec($cn, "UPDATE users SET Pwd=?, Typeofuser=? WHERE Username=?", "sss", [$hash, $role, $selected_user]);
        } else {
            // Updating role only
            $ok = prepare_exec($cn, "UPDATE users SET Typeofuser=? WHERE Username=?", "ss", [$role, $selected_user]);
        }
        
        if ($ok) {
            echo "<script>alert('User updated successfully!'); window.location.href='index.php';</script>";
            exit;
        } else {
            $message = "Failed to update user.";
            $messageType = "danger";
        }
    }
}

// 3. Load active user values if we have a selection
if ($selected_user) {
    $stmt_load = prepare_query($cn, "SELECT Username, Typeofuser FROM users WHERE Username = ?", "s", [$selected_user]);
    if ($stmt_load && mysqli_num_rows($stmt_load) > 0) {
        $user_data = mysqli_fetch_assoc($stmt_load);
        $Username = $user_data['Username'];
        $Usertype = $user_data['Typeofuser'];
        $show_data = true;
    }
}

// Fetch users list for selection dropdown
$users_list = mysqli_query($cn, "SELECT Username FROM users ORDER BY Username");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Update User</title>
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
        
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Update User</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select User</td>
                        <td>
                            <select name="t1" required onchange="this.form.submit();" style="margin-bottom:0.5rem;">
                                <option value="">Select User to Modify</option>
                                <?php
                                if ($users_list) {
                                    while ($usr = mysqli_fetch_assoc($users_list)) {
                                        $selected = ($usr['Username'] === $selected_user) ? 'selected="selected"' : '';
                                        echo '<option value="' . h($usr['Username']) . '" ' . $selected . '>' . h($usr['Username']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <noscript><input type="submit" value="Show Details" name="show" class="btn btn-secondary btn-sm w-auto py-1 px-3 mt-1" formnovalidate/></noscript>
                        </td>
                    </tr>
                    
                    <?php if ($show_data): ?>
                    <tr>
                        <td class="lefttxt">Username</td>
                        <td>
                            <input type="text" readonly value="<?php echo h($Username); ?>" style="opacity: 0.7; cursor: not-allowed;" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">New Password</td>
                        <td>
                            <input type="password" name="t2" placeholder="Leave empty to keep current password" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Confirm New Password</td>
                        <td>
                            <input type="password" name="t3" placeholder="Confirm new password" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Type of User</td>
                        <td>
                            <select name="s1" required>
                                <option value="Admin" <?php echo ($Usertype === 'Admin') ? 'selected="selected"' : ''; ?>>Admin</option>
                                <option value="General" <?php echo ($Usertype === 'General') ? 'selected="selected"' : ''; ?>>General Operator</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Update User" name="sbmt" />
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php include('bottom.php'); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const p1 = document.getElementsByName("t2")[0];
    const p2 = document.getElementsByName("t3")[0];
    
    function validatePasswords() {
        if (p1.value.length > 0 && p1.value !== p2.value) {
            p2.setCustomValidity("Passwords do not match");
        } else {
            p2.setCustomValidity("");
        }
    }
    
    if (p1 && p2) {
        p1.addEventListener("change", validatePasswords);
        p2.addEventListener("keyup", validatePasswords);
    }
});
</script>
</body>
</html>
<?php mysqli_close($cn); ?>
