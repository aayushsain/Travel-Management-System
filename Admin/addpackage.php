<?php
// C:\travel\Admin\addpackage.php
include('function.php');
check_login();

$message = '';
$messageType = '';

// Secure Image Upload Helper
function upload_image($file_post_name, $target_dir = "packimages/") {
    if (!isset($_FILES[$file_post_name]) || $_FILES[$file_post_name]['error'] !== UPLOAD_ERR_OK) {
        return ['status' => false, 'error' => 'No file uploaded or error in upload.'];
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
    
    // Ensure unique name to prevent collisions
    $unique_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $target_file = $target_dir . $unique_name;
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return ['status' => true, 'filename' => $unique_name];
    } else {
        return ['status' => false, 'error' => 'Failed to save uploaded file.'];
    }
}

// Handle Form Submission
if (isset($_POST["sbmt"])) {
    // CSRF verification
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $name = trim($_POST["t1"] ?? '');
    $category = isset($_POST["t2"]) ? (int)$_POST["t2"] : 0;
    $subcategory = isset($_POST["t3"]) ? (int)$_POST["t3"] : 0;
    $price = (double)($_POST["t8"] ?? 0.0);
    $detail = trim($_POST["t7"] ?? '');
    
    // Validation
    if (strlen($name) < 3 || strlen($name) > 50) {
        $message = "Package name must be between 3 and 50 characters.";
        $messageType = "danger";
    } elseif ($category <= 0) {
        $message = "Please select a valid category.";
        $messageType = "danger";
    } elseif ($subcategory <= 0) {
        $message = "Please select a valid subcategory.";
        $messageType = "danger";
    } elseif ($price <= 0) {
        $message = "Please enter a valid package price.";
        $messageType = "danger";
    } else {
        // Upload the 3 pictures
        $upload1 = upload_image('t4');
        $upload2 = upload_image('t5');
        $upload3 = upload_image('t6');
        
        if (!$upload1['status']) {
            $message = "Picture 1: " . $upload1['error'];
            $messageType = "danger";
        } elseif (!$upload2['status']) {
            $message = "Picture 2: " . $upload2['error'];
            $messageType = "danger";
        } elseif (!$upload3['status']) {
            $message = "Picture 3: " . $upload3['error'];
            $messageType = "danger";
        } else {
            // Save to DB using prepared statements
            $ok = prepare_exec($cn,
                "INSERT INTO package (Packname, Category, Subcategory, Packprice, Pic1, Pic2, Pic3, Detail) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                "siiissss",
                [$name, $category, $subcategory, $price, $upload1['filename'], $upload2['filename'], $upload3['filename'], $detail]
            );
            
            if ($ok) {
                echo "<script>alert('Package saved successfully!'); window.location.href='viewpackage.php';</script>";
                exit;
            } else {
                $message = "Error saving package to database.";
                $messageType = "danger";
            }
        }
    }
}

// Fetch categories for dropdown
$categories_result = mysqli_query($cn, "SELECT Cat_id, Cat_name FROM category ORDER BY Cat_name");

// Selected Category (for dynamic subcategory fetching)
$selected_cat = isset($_POST["t2"]) ? (int)$_POST["t2"] : 0;
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
    <title>VoyageQuest - Add Package</title>
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
                        <th colspan="2" class="toptd">Create Travel Package</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="lefttxt" style="padding-top:1.5rem !important;">Package Name</td>
                        <td>
                            <input type="text" name="t1" required 
                                   value="<?php echo isset($_POST['t1']) ? h($_POST['t1']) : ''; ?>"
                                   pattern="[a-zA-Z0-9 _-]{3,50}" 
                                   title="3-50 alphanumeric characters, spaces, hyphens, or underscores only"
                                   placeholder="e.g. Exotic Bali Adventure" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Select Category</td>
                        <td>
                            <select name="t2" required onchange="this.form.submit();" style="margin-bottom:0.5rem;">
                                <option value="">Select Category</option>
                                <?php
                                if ($categories_result) {
                                    while ($cat = mysqli_fetch_assoc($categories_result)) {
                                        $selected = ($cat['Cat_id'] == $selected_cat) ? 'selected="selected"' : '';
                                        echo '<option value="' . (int)$cat['Cat_id'] . '" ' . $selected . '>' . h($cat['Cat_name']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <noscript><input type="submit" value="Load Subcategories" name="show" class="btn btn-secondary btn-sm w-auto py-1 px-3 mt-1" /></noscript>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Select Subcategory</td>
                        <td>
                            <select name="t3" required>
                                <option value="">Select Subcategory</option>
                                <?php
                                foreach ($subcategories as $subcat) {
                                    $selected = (isset($_POST['t3']) && $_POST['t3'] == $subcat['Subcatid']) ? 'selected="selected"' : '';
                                    echo '<option value="' . (int)$subcat['Subcatid'] . '" ' . $selected . '>' . h($subcat['Subcatname']) . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Package Price (INR)</td>
                        <td>
                            <input type="number" name="t8" required min="1" step="any"
                                   value="<?php echo isset($_POST['t8']) ? h($_POST['t8']) : ''; ?>"
                                   placeholder="e.g. 45000" />
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Upload Picture 1</td>
                        <td>
                            <input type="file" name="t4" required accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Upload Picture 2</td>
                        <td>
                            <input type="file" name="t5" required accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Upload Picture 3</td>
                        <td>
                            <input type="file" name="t6" required accept="image/*" />
                            <small class="text-muted d-block mt-1" style="font-size:0.75rem;">JPG, PNG, GIF, or WEBP (Max 5MB)</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="lefttxt">Package Details</td>
                        <td>
                            <textarea name="t7" required rows="5" placeholder="Enter detailed travel itinerary, inclusions, etc."><?php echo isset($_POST['t7']) ? h($_POST['t7']) : ''; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" value="Save Package" name="sbmt" />
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
