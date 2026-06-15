<?php
// C:\travel\Admin\updatesubcategory.php
include('function.php');
check_admin();

$message = '';
$messageType = '';
$show_data = false;

$Subcatid = 0;
$Subcatname = "";
$Catid = 0;
$Pic = "";
$Detail = "";

// Secure Image Upload Helper (retains old image if none is uploaded)
function upload_image_subcat_opt($file_post_name, $old_filename = '', $target_dir = "subcatimages/") {
    if (!isset($_FILES[$file_post_name]) || $_FILES[$file_post_name]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['status' => true, 'filename' => $old_filename];
    }
    
    if ($_FILES[$file_post_name]['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'error' => 'Error uploading file.'];
    }
    
    $file = $_FILES[$file_post_name];
    $filename = basename($file["name"]);
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // Check file size (max 5MB)
    if ($file["size"] > 5000000) {
        return ['status' => false, 'error' => 'File size exceeds 5MB limit.'];
    }
    
    // Check if actual image
    $check = getimagesize($file["tmp_name"]);
    if ($check === false) {
        return ['status' => false, 'error' => 'Uploaded file is not a valid image.'];
    }
    
    // Check MIME type
    $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($check['mime'], $allowed_mimes)) {
        return ['status' => false, 'error' => 'Only JPG, JPEG, PNG, GIF, and WEBP formats are allowed.'];
    }
    
    // Check extension
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed_exts)) {
        return ['status' => false, 'error' => 'Only JPG, JPEG, PNG, GIF, and WEBP extensions are allowed.'];
    }
    
    // Unique name
    $unique_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_file = $target_dir . $unique_name;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $unique_name];
    } else {
        return ['status' => false, 'error' => 'Failed to save uploaded file.'];
    }
}

// 1. Identify which subcategory we are editing
$selected_id = isset($_POST["s1"]) ? (int)$_POST["s1"] : (isset($_GET["sid"]) ? (int)$_GET["sid"] : 0);

// 2. Handle update submission
if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name = trim($_POST["t1"] ?? '');
    $cat = isset($_POST["t2"]) ? (int)$_POST["t2"] : 0;
    $det = trim($_POST["t4"] ?? '');
    $old_pic = trim($_POST["h1"] ?? '');
    
    if (strlen($name) < 2 || strlen($name) > 50) {
        $message = "Subcategory name must be between 2 and 50 characters.";
        $messageType = "danger";
    } elseif ($cat <= 0) {
        $message = "Please select a valid category.";
        $messageType = "danger";
    } else {
        $up = upload_image_subcat_opt('t3', $old_pic);
        if (!$up['status']) {
            $message = $up['error'];
            $messageType = "danger";
        } else {
            $ok = prepare_exec($cn,
                "UPDATE subcategory SET Subcatname=?, Catid=?, Pic=?, Detail=? WHERE Subcatid=?",
                "sissi",
                [$name, $cat, $up['filename'], $det, $selected_id]
            );
            if ($ok) {
                echo "<script>alert('Subcategory updated successfully!'); window.location.href='viewsubcategory.php';</script>";
                exit;
            } else {
                $message = "Failed to update subcategory in database.";
                $messageType = "danger";
            }
        }
    }
}

// 3. Load active subcategory values if we have a selection
if ($selected_id > 0) {
    $stmt_load = prepare_query($cn, "SELECT * FROM subcategory WHERE Subcatid = ?", "i", [$selected_id]);
    if ($stmt_load && mysqli_num_rows($stmt_load) > 0) {
        $subcat_data = mysqli_fetch_assoc($stmt_load);
        $Subcatid = (int)$subcat_data['Subcatid'];
        $Subcatname = $subcat_data['Subcatname'];
        $Catid = (int)$subcat_data['Catid'];
        $Pic = $subcat_data['Pic'];
        $Detail = $subcat_data['Detail'];
        $show_data = true;
    }
}

// Load subcategories for selection dropdown
$subcategories_list = mysqli_query($cn, "SELECT Subcatid, Subcatname FROM subcategory ORDER BY Subcatname");

// Load categories
$categories_list = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Update Subcategory</title>
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
        
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Update Subcategory</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select Subcategory</td>
                        <td>
                            <select name="s1" required onchange="this.form.submit();" style="margin-bottom:0.5rem;">
                                <option value="">Select Subcategory to Modify</option>
                                <?php
                                if ($subcategories_list) {
                                    while ($sub = mysqli_fetch_assoc($subcategories_list)) {
                                        $selected = ($sub['Subcatid'] == $selected_id) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$sub['Subcatid'] . '" ' . $selected . '>' . h($sub['Subcatname']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <noscript><input type="submit" value="Show Details" name="show" class="btn btn-secondary btn-sm w-auto py-1 px-3 mt-1" formnovalidate/></noscript>
                        </td>
                    </tr>
                    
                    <?php if ($show_data): ?>
                    <tr>
                        <td class="lefttxt">Subcategory Name</td>
                        <td>
                            <input type="text" name="t1" required 
                                   value="<?php echo h($Subcatname); ?>"
                                   pattern="[a-zA-Z0-9 _-]{2,50}" 
                                   title="Subcategory name must be between 2 and 50 characters" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Select Category</td>
                        <td>
                            <select name="t2" required>
                                <option value="">Select Category</option>
                                <?php
                                if ($categories_list) {
                                    while ($cat = mysqli_fetch_assoc($categories_list)) {
                                        $selected = ($cat['Cat_id'] == $Catid) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$cat['Cat_id'] . '" ' . $selected . '>' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="lefttxt">Current Picture</td>
                        <td>
                            <?php if ($Pic): ?>
                                <img src="subcatimages/<?php echo h($Pic); ?>" style="width:120px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color); margin-bottom:0.5rem;" alt="Subcat Pic" />
                            <?php endif; ?>
                            <input type="hidden" name="h1" value="<?php echo h($Pic); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Update Picture</td>
                        <td>
                            <input type="file" name="t3" accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">Leave empty to keep current picture. JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="lefttxt">Details / Description</td>
                        <td>
                            <textarea name="t4" required rows="4"><?php echo h($Detail); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Update Subcategory" name="sbmt" />
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