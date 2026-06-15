<?php
// C:\travel\Admin\deletesubcategory.php
include('function.php');
check_admin();

$message = '';
$messageType = '';

$selected_cat = isset($_POST["t2"]) ? (int)$POST["t2"] : 0;
$selected_sub = isset($_POST["s1"]) ? (int)$POST["s1"] : 0;

if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    if ($selected_sub <= 0) {
        $message = "Please select a valid subcategory.";
        $messageType = "danger";
    } else {
        $ok = prepare_exec($cn, "DELETE FROM subcategory WHERE Subcatid = ?", "i", [$selected_sub]);
        if ($ok) {
            echo "<script>alert('Subcategory deleted successfully!'); window.location.href='viewsubcategory.php';</script>";
            exit;
        } else {
            $message = "Failed to delete subcategory. Check if packages are linked to it.";
            $messageType = "danger";
        }
    }
}

// Fetch categories
$categories_list = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");

// Fetch subcategories for selected category
$subcategories = [];
if ($selected_cat > 0) {
    $sub_res = prepare_query($cn, "SELECT Subcatid, Subcatname FROM subcategory WHERE Catid = ? ORDER BY Subcatname", "i", [$selected_cat]);
    if ($sub_res) {
        while ($row = mysqli_fetch_assoc($sub_res)) {
            $subcategories[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Delete Subcategory</title>
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
        
        <form method="post" onsubmit="return confirm('Are you sure you want to permanently delete this subcategory? All packages under it will be affected.');">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Delete Subcategory</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select Category</td>
                        <td>
                            <select name="t2" required onchange="this.form.submit();" style="margin-bottom:0.5rem;">
                                <option value="">Select Category</option>
                                <?php
                                if ($categories_list) {
                                    while ($cat = mysqli_fetch_assoc($categories_list)) {
                                        $selected = ($cat['Cat_id'] == $selected_cat) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$cat['Cat_id'] . '" ' . $selected . '>' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <noscript><input type="submit" value="Show Subcategories" name="show" class="btn btn-secondary btn-sm w-auto py-1 px-3 mt-1" formnovalidate/></noscript>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="lefttxt">Select Subcategory</td>
                        <td>
                            <select name="s1" required>
                                <option value="">Select Subcategory</option>
                                <?php
                                foreach ($subcategories as $subcat) {
                                    $selected = ($subcat['Subcatid'] == $selected_sub) ? 'selected="selected"' : '';
                                    echo '<option value="' . (int)$subcat['Subcatid'] . '" ' . $selected . '>' . h($subcat['Subcatname']) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Delete Subcategory" name="sbmt" style="background: linear-gradient(135deg, #EF4444, #b91c1c) !important; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25) !important;" />
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