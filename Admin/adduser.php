<?php
// C:\travel\Admin\adduser.php
include('function.php');
check_admin();

$message = '';
$messageType = '';

if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $username = trim($_POST["t1"] ?? '');
    $password = $_POST["t2"] ?? '';
    $confirm  = $_POST["t3"] ?? '';
    $role     = trim($_POST["s1"] ?? '');
    
    if (strlen($username) < 3 || strlen($username) > 50) {
        $message = "Username must be between 3 and 50 characters.";
        $messageType = "danger";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "danger";
    } elseif ($password !== $confirm) {
        $message = "Passwords do not match.";
        $messageType = "danger";
    } elseif (!in_array($role, ['Admin', 'General'])) {
        $message = "Please select a valid user role.";
        $messageType = "danger";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Use prepared statements
        $ok = prepare_exec($cn, "INSERT INTO users (Username, Pwd, Typeofuser) VALUES (?, ?, ?)", "sss", [$username, $hash, $role]);
        if ($ok) {
            echo "<script>alert('User registered successfully!'); window.location.href='index.php';</script>";
            exit;
        } else {
            $message = "Failed to register user. The username might already be taken.";
            $messageType = "danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Add User</title>
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
        
        <form method="post" id="userForm">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">
                            <div class="form-title-icon"><i class="fa-solid fa-user-plus text-info"></i></div>
                            Add User
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Username</td>
                        <td>
                            <input type="text" name="t1" required 
                                   value="<?php echo isset($_POST['t1']) ? h($_POST['t1']) : ''; ?>"
                                   pattern="[a-zA-Z0-9 _-]{3,50}" 
                                   title="3-50 alphanumeric characters, spaces, hyphens, or underscores"
                                   placeholder="Enter username" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Password</td>
                        <td>
                            <input type="password" name="t2" required minlength="6" placeholder="Enter password" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Confirm Password</td>
                        <td>
                            <input type="password" name="t3" required minlength="6" placeholder="Confirm password" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Type of User</td>
                        <td>
                            <select name="s1" required>
                                <option value="">Select Role</option>
                                <option value="Admin" <?php echo (isset($_POST['s1']) && $_POST['s1'] === 'Admin') ? 'selected="selected"' : ''; ?>>Admin</option>
                                <option value="General" <?php echo (isset($_POST['s1']) && $_POST['s1'] === 'General') ? 'selected="selected"' : ''; ?>>General Operator</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Save User" name="sbmt" />
                        </td>
                    </tr>
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
        if (p1.value !== p2.value) {
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