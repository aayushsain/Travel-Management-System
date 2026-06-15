<?php
// C:\travel\Admin\deletecategory.php
include('function.php');
check_admin();

$message = '';
$messageType = '';

if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $cat_id = isset($_POST["t1"]) ? (int)$_POST["t1"] : 0;
    if ($cat_id <= 0) {
        $message = "Please select a valid category.";
        $messageType = "danger";
    } else {
        // Prepare delete
        $ok = prepare_exec($cn, "DELETE FROM category WHERE Cat_id = ?", "i", [$cat_id]);
        if ($ok) {
            echo "<script>alert('Category deleted successfully!'); window.location.href='viewcategory.php';</script>";
            exit;
        } else {
            $message = "Failed to delete category. Verify if it has associated subcategories or packages.";
            $messageType = "danger";
        }
    }
}

// Fetch categories for selection dropdown list
$categories_list = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Delete Category</title>
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
        
        <form method="post" onsubmit="return confirm('Are you sure you want to permanently delete this category? All subcategories and packages under it might be affected.');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Delete Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select Category</td>
                        <td>
                            <select name="t1" required>
                                <option value="">Select Category to Delete</option>
                                <?php
                                if ($categories_list) {
                                    while ($cat = mysqli_fetch_assoc($categories_list)) {
                                        echo '<option value="' . (int)$cat['Cat_id'] . '">' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Delete Category" name="sbmt" style="background: linear-gradient(135deg, #EF4444, #b91c1c) !important; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25) !important;" />
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
