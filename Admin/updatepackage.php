<?php
// C:\travel\Admin\updatepackage.php
include('function.php');
check_admin();

$message = '';
$messageType = '';
$show_data = false;

$Packid = 0;
$Packname = "";
$Category = 0;
$Subcategory = 0;
$Packprice = 0.0;
$Pic1 = "";
$Pic2 = "";
$Pic3 = "";
$Detail = "";

// Secure Image Upload Helper (retains old image if none is uploaded)
function upload_image_opt($file_post_name, $old_filename = '', $target_dir = "packimages/") {
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

// 1. Identify which package we are editing
$selected_id = isset($_POST["s1"]) ? (int)$_POST["s1"] : (isset($_GET["pid"]) ? (int)$_GET["pid"] : 0);

// 2. Handle update submission
if (isset($_POST["sbmt"])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name = trim($_POST["t1"] ?? '');
    $cat = isset($_POST["t2"]) ? (int)$_POST["t2"] : 0;
    $subcat = isset($_POST["t3"]) ? (int)$_POST["t3"] : 0;
    $price = (double)($_POST["t8"] ?? 0.0);
    $det = trim($_POST["t7"] ?? '');
    $old_p1 = trim($_POST["h1"] ?? '');
    $old_p2 = trim($_POST["h2"] ?? '');
    $old_p3 = trim($_POST["h3"] ?? '');
    
    if (strlen($name) < 3 || strlen($name) > 50) {
        $message = "Package name must be between 3 and 50 characters.";
        $messageType = "danger";
    } elseif ($cat <= 0) {
        $message = "Please select a valid category.";
        $messageType = "danger";
    } elseif ($subcat <= 0) {
        $message = "Please select a valid subcategory.";
        $messageType = "danger";
    } elseif ($price <= 0) {
        $message = "Please enter a valid price.";
        $messageType = "danger";
    } else {
        // Upload images (will default to old filename if none is uploaded)
        $up1 = upload_image_opt('t4', $old_p1);
        $up2 = upload_image_opt('t5', $old_p2);
        $up3 = upload_image_opt('t6', $old_p3);
        
        if (!$up1['status']) {
            $message = "Picture 1: " . $up1['error'];
            $messageType = "danger";
        } elseif (!$up2['status']) {
            $message = "Picture 2: " . $up2['error'];
            $messageType = "danger";
        } elseif (!$up3['status']) {
            $message = "Picture 3: " . $up3['error'];
            $messageType = "danger";
        } else {
            $ok = prepare_exec($cn,
                "UPDATE package SET Packname=?, Category=?, Subcategory=?, Packprice=?, Pic1=?, Pic2=?, Pic3=?, Detail=? WHERE Packid=?",
                "siiissssi",
                [$name, $cat, $subcat, $price, $up1['filename'], $up2['filename'], $up3['filename'], $det, $selected_id]
            );
            if ($ok) {
                echo "<script>alert('Package updated successfully!'); window.location.href='viewpackage.php';</script>";
                exit;
            } else {
                $message = "Failed to update package in database.";
                $messageType = "danger";
            }
        }
    }
}

// 3. Load active package values if we have a selection
if ($selected_id > 0) {
    $stmt_load = prepare_query($cn, "SELECT * FROM package WHERE Packid = ?", "i", [$selected_id]);
    if ($stmt_load && mysqli_num_rows($stmt_load) > 0) {
        $package_data = mysqli_fetch_assoc($stmt_load);
        $Packid = (int)$package_data['Packid'];
        $Packname = $package_data['Packname'];
        $Category = (int)$package_data['Category'];
        $Subcategory = (int)$package_data['Subcategory'];
        $Packprice = (double)$package_data['Packprice'];
        $Pic1 = $package_data['Pic1'];
        $Pic2 = $package_data['Pic2'];
        $Pic3 = $package_data['Pic3'];
        $Detail = $package_data['Detail'];
        $show_data = true;
    }
}

// Load packages for selection list
$packages_list = mysqli_query($cn, "SELECT Packid, Packname FROM package ORDER BY Packname");

// Load categories
$categories_list = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");

// Load subcategories
$subcategories_list = mysqli_query($cn, "SELECT Subcatid, Subcatname FROM subcategory ORDER BY Subcatname");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VoyageQuest - Update Package</title>
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
            <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert" style="max-width: 600px; margin: 0 auto 1.5rem auto; border-radius: 12px;">
                <strong><i class="fa-solid fa-circle-exclamation"></i></strong> <?php echo h($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            
            <table border="0" width="100%" align="center" class="tableshadow">
                <thead>
                    <tr>
                        <th colspan="2" class="toptd">Update Travel Package</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Select Package</td>
                        <td>
                            <select name="s1" required onchange="this.form.submit();" style="margin-bottom:0.5rem;">
                                <option value="">Select Package to Modify</option>
                                <?php
                                if ($packages_list) {
                                    while ($pkg = mysqli_fetch_assoc($packages_list)) {
                                        $selected = ($pkg['Packid'] == $selected_id) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$pkg['Packid'] . '" ' . $selected . '>' . h($pkg['Packname']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <noscript><input type="submit" value="Show Details" name="show" class="btn btn-secondary btn-sm w-auto py-1 px-3 mt-1" formnovalidate/></noscript>
                        </td>
                    </tr>
                    
                    <?php if ($show_data): ?>
                    <tr>
                        <td class="lefttxt">Package Name</td>
                        <td>
                            <input type="text" name="t1" required 
                                   value="<?php echo h($Packname); ?>"
                                   pattern="[a-zA-Z0-9 _-]{3,50}" 
                                   title="3-50 alphanumeric characters, spaces, hyphens, or underscores only" />
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
                                        $selected = ($cat['Cat_id'] == $Category) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$cat['Cat_id'] . '" ' . $selected . '>' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Select Subcategory</td>
                        <td>
                            <select name="t3" required>
                                <option value="">Select Subcategory</option>
                                <?php
                                if ($subcategories_list) {
                                    while ($sub = mysqli_fetch_assoc($subcategories_list)) {
                                        $selected = ($sub['Subcatid'] == $Subcategory) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$sub['Subcatid'] . '" ' . $selected . '>' . h($sub['Subcatname']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Package Price (INR)</td>
                        <td>
                            <input type="number" name="t8" required min="1" step="any"
                                   value="<?php echo h($Packprice); ?>" />
                        </td>
                    </tr>
                    
                    <!-- Picture 1 -->
                    <tr>
                        <td class="lefttxt">Current Picture 1</td>
                        <td>
                            <?php if ($Pic1): ?>
                                <img src="packimages/<?php echo h($Pic1); ?>" style="width:120px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color); margin-bottom:0.5rem;" alt="Pic 1" />
                            <?php endif; ?>
                            <input type="hidden" name="h1" value="<?php echo h($Pic1); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Update Picture 1</td>
                        <td>
                            <input type="file" name="t4" accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">Leave empty to keep current picture. JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    
                    <!-- Picture 2 -->
                    <tr>
                        <td class="lefttxt">Current Picture 2</td>
                        <td>
                            <?php if ($Pic2): ?>
                                <img src="packimages/<?php echo h($Pic2); ?>" style="width:120px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color); margin-bottom:0.5rem;" alt="Pic 2" />
                            <?php endif; ?>
                            <input type="hidden" name="h2" value="<?php echo h($Pic2); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Update Picture 2</td>
                        <td>
                            <input type="file" name="t5" accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">Leave empty to keep current picture. JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    
                    <!-- Picture 3 -->
                    <tr>
                        <td class="lefttxt">Current Picture 3</td>
                        <td>
                            <?php if ($Pic3): ?>
                                <img src="packimages/<?php echo h($Pic3); ?>" style="width:120px; height:80px; object-fit:cover; border-radius:8px; border:1px solid var(--border-color); margin-bottom:0.5rem;" alt="Pic 3" />
                            <?php endif; ?>
                            <input type="hidden" name="h3" value="<?php echo h($Pic3); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Update Picture 3</td>
                        <td>
                            <input type="file" name="t6" accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">Leave empty to keep current picture. JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    
                    <tr>
                        <td class="lefttxt">Package Details</td>
                        <td>
                            <textarea name="t7" required rows="5"><?php echo h($Detail); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Update Package" name="sbmt" />
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
