<?php
// C:\travel\Admin\updatecategory.php
include('function.php');
check_admin();

$message = '';
$messageType = '';
$show_data = false;

$Cat_id = 0;
$Cat_name = "";

// 1. Check selected category ID
$selected_id = isset($_POST["t1"]) ? (int)$_POST["t1"] : 0;

// 2. Handle update submission
if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $cat_name = trim($_POST["t2"] ?? '');
    if (strlen($cat_name) < 3 || strlen($cat_name) > 50) {
        $message = "Category name must be between 3 and 50 characters.";
        $messageType = "danger";
    } else {
        $ok = prepare_exec($cn, "UPDATE category SET Cat_name = ? WHERE Cat_id = ?", "si", [$cat_name, $selected_id]);
        if ($ok) {
            echo "<script>alert('Category updated successfully!'); window.location.href='viewcategory.php';</script>";
            exit;
        } else {
            $message = "Failed to update category.";
            $messageType = "danger";
        }
    }
}

// 3. Load selected category name if applicable
if ($selected_id > 0) {
    $stmt_load = prepare_query($cn, "SELECT * FROM category WHERE Cat_id = ?", "i", [$selected_id]);
    if ($stmt_load && mysqli_num_rows($stmt_load) > 0) {
        $cat_data = mysqli_fetch_assoc($stmt_load);
        $Cat_id = (int)$cat_data['Cat_id'];
        $Cat_name = $cat_data['Cat_name'];
        $show_data = true;
    }
}

// Fetch all categories for selection dropdown
$categories_list = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Update Category</title>
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
                        <th colspan="2" class="toptd">Update Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select Category</td>
                        <td>
                            <select name="t1" required onchange="this.form.submit();" style="margin-bottom:0.5rem;">
                                <option value="">Select Category to Modify</option>
                                <?php
                                if ($categories_list) {
                                    while ($cat = mysqli_fetch_assoc($categories_list)) {
                                        $selected = ($cat['Cat_id'] == $selected_id) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$cat['Cat_id'] . '" ' . $selected . '>' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <noscript><input type="submit" value="Show Details" name="show" class="btn btn-secondary btn-sm w-auto py-1 px-3 mt-1" formnovalidate/></noscript>
                        </td>
                    </tr>
                    
                    <?php if ($show_data): ?>
                    <tr>
                        <td class="lefttxt">Category Name</td>
                        <td>
                            <input type="text" name="t2" required 
                                   value="<?php echo h($Cat_name); ?>" 
                                   pattern="[a-zA-Z0-9 _-]{3,50}" 
                                   title="Category name must be between 3 and 50 characters" />
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Update Category" name="sbmt" />
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<?php include('bottom.php'); ?>
</body>
</html>
<?php mysqli_close($cn); ?>
