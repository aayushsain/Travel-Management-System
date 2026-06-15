<?php
// C:\travel\Admin\addcategory.php
include('function.php');
check_login();

$message = '';
$messageType = '';

if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name = trim($_POST["t1"] ?? '');
    if (strlen($name) < 3 || strlen($name) > 50) {
        $message = "Category name must be between 3 and 50 characters.";
        $messageType = "danger";
    } else {
        $ok = prepare_exec($cn, "INSERT INTO category (Cat_name) VALUES (?)", "s", [$name]);
        if ($ok) {
            echo "<script>alert('Category saved successfully!'); window.location.href='viewcategory.php';</script>";
            exit;
        } else {
            $message = "Failed to save category. It may already exist.";
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
    <title>VoyageQuest - Add Category</title>
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
                        <th colspan="2" class="toptd">
                            <div class="form-title-icon"><i class="fa-solid fa-folder-plus text-info"></i></div>
                            Add Category
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Category Name</td>
                        <td>
                            <input type="text" name="t1" required 
                                   pattern="[a-zA-Z0-9 _-]{3,50}" 
                                   title="Category name must be between 3 and 50 characters (alphanumeric, spaces, hyphens, or underscores only)" 
                                   placeholder="e.g. Adventure Tours" />
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Save Category" name="sbmt" />
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